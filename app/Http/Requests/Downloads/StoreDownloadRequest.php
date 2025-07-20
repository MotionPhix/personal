<?php

namespace App\Http\Requests\Downloads;

use Illuminate\Foundation\Http\FormRequest;

class StoreDownloadRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      // Basic Information
      'title' => ['required', 'string', 'max:255'],
      'description' => ['nullable', 'string'],
      'brand' => ['nullable', 'string', 'max:100'],
      'category' => ['nullable', 'string', 'max:100'],

      // Settings
      'is_featured' => ['boolean'],
      'is_public' => ['boolean'],
      'sort_order' => ['nullable', 'integer', 'min:0'],

      // SEO
      'meta_title' => ['nullable', 'string', 'max:255'],
      'meta_description' => ['nullable', 'string', 'max:500'],

      // Tags
      'tags' => ['nullable', 'array'],
      'tags.*' => ['string', 'max:50'],

      // File Uploads
      'poster_image' => [
        'nullable',
        'file',
        'image',
        'mimes:jpeg,png,jpg,gif,webp',
        'max:5120' // 5MB
      ],
      'download_file' => [
        'required',
        'file',
        'max:51200' // 50MB
      ],
    ];
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    return [
      'title.required' => 'Download title is required.',
      'title.max' => 'Download title cannot exceed 255 characters.',
      'brand.max' => 'Brand name cannot exceed 100 characters.',
      'category.max' => 'Category name cannot exceed 100 characters.',
      'sort_order.integer' => 'Sort order must be a number.',
      'sort_order.min' => 'Sort order cannot be negative.',
      'meta_title.max' => 'Meta title cannot exceed 255 characters.',
      'meta_description.max' => 'Meta description cannot exceed 500 characters.',
      'tags.array' => 'Tags must be an array.',
      'tags.*.string' => 'Each tag must be a string.',
      'tags.*.max' => 'Each tag cannot exceed 50 characters.',
      'poster_image.file' => 'Poster must be a file.',
      'poster_image.image' => 'Poster must be an image.',
      'poster_image.mimes' => 'Poster image must be jpeg, png, jpg, gif, or webp format.',
      'poster_image.max' => 'Poster image must not exceed 5MB.',
      'download_file.required' => 'Download file is required.',
      'download_file.file' => 'Download must be a file.',
      'download_file.max' => 'Download file must not exceed 50MB.',
    ];
  }

  /**
   * Get custom attribute names for validation errors.
   */
  public function attributes(): array
  {
    return [
      'title' => 'download title',
      'brand' => 'brand name',
      'category' => 'category',
      'is_featured' => 'featured status',
      'is_public' => 'public status',
      'sort_order' => 'sort order',
      'meta_title' => 'meta title',
      'meta_description' => 'meta description',
      'poster_image' => 'poster image',
      'download_file' => 'download file',
    ];
  }

  /**
   * Prepare the data for validation.
   */
  protected function prepareForValidation(): void
  {
    // Convert string booleans to actual booleans
    $this->merge([
      'is_featured' => $this->boolean('is_featured'),
      'is_public' => $this->boolean('is_public'),
    ]);

    // Ensure tags array is properly formatted
    if ($this->has('tags') && is_string($this->tags)) {
      $this->merge([
        'tags' => json_decode($this->tags, true) ?: []
      ]);
    }

    // Auto-generate meta title if not provided
    if (empty($this->meta_title) && !empty($this->title)) {
      $this->merge([
        'meta_title' => $this->title . ' - Download'
      ]);
    }
  }
}
