<?php

namespace App\Http\Requests\Projects;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
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
    $projectId = $this->route('project')?->id;

    return [
      // Basic Information
      'name' => ['required', 'string', 'max:255'],
      'description' => ['nullable', 'string'],
      'short_description' => ['nullable', 'string', 'max:500'],
      'customer_id' => ['nullable', 'string', 'exists:customers,uuid'],

      // Project Details
      'production_type' => ['nullable', 'string', 'max:100'],
      'category' => ['nullable', 'string', 'max:100'],
      'status' => ['required', 'string', Rule::in(['not_started', 'in_progress', 'on_hold', 'completed', 'cancelled'])],
      'priority' => ['required', 'string', Rule::in(['low', 'medium', 'high', 'urgent'])],

      // Timeline
      'start_date' => ['nullable', 'date'],
      'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],

      // Resources
      'estimated_hours' => ['nullable', 'numeric', 'min:0', 'max:99999.99'],
      'actual_hours' => ['nullable', 'numeric', 'min:0', 'max:99999.99'],
      'budget' => ['nullable', 'numeric', 'min:0', 'max:999999999.99'],

      // Technical Details
      'technologies' => ['nullable', 'array'],
      'technologies.*' => ['string', 'max:100'],
      'features' => ['nullable', 'array'],
      'features.*' => ['string', 'max:255'],

      // Project Story
      'challenges' => ['nullable', 'string'],
      'solutions' => ['nullable', 'string'],
      'results' => ['nullable', 'string'],
      'client_feedback' => ['nullable', 'string'],

      // Settings
      'is_featured' => ['boolean'],
      'is_public' => ['boolean'],
      'sort_order' => ['nullable', 'integer', 'min:0'],

      // SEO
      'meta_title' => ['nullable', 'string', 'max:255'],
      'meta_description' => ['nullable', 'string', 'max:500'],

      // External Links
      'live_url' => ['nullable', 'url', 'max:255'],
      'github_url' => ['nullable', 'url', 'max:255'],
      'figma_url' => ['nullable', 'url', 'max:255'],
      'behance_url' => ['nullable', 'url', 'max:255'],
      'dribbble_url' => ['nullable', 'url', 'max:255'],

      // New Media Files
      'captured_media' => ['nullable', 'array', 'max:10'],
      'captured_media.*' => [
        'file',
        'image',
        'mimes:jpeg,png,jpg,gif,webp',
        'max:5120' // 5MB
      ],
      'poster_image' => [
        'nullable',
        'file',
        'image',
        'mimes:jpeg,png,jpg,gif,webp',
        'max:5120' // 5MB
      ],

      // Existing Media URLs (for preservation)
      'existing_gallery_images' => ['nullable', 'array'],
      'existing_gallery_images.*' => ['string', 'url'],
      'existing_poster_image' => ['nullable', 'string', 'url'],
    ];
  }

  /**
   * Get custom validation messages.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'Project name is required.',
      'name.max' => 'Project name cannot exceed 255 characters.',
      'customer_id.exists' => 'Selected customer does not exist.',
      'status.required' => 'Project status is required.',
      'status.in' => 'Invalid project status selected.',
      'priority.required' => 'Project priority is required.',
      'priority.in' => 'Invalid project priority selected.',
      'end_date.after_or_equal' => 'End date must be after or equal to start date.',
      'estimated_hours.numeric' => 'Estimated hours must be a number.',
      'estimated_hours.min' => 'Estimated hours cannot be negative.',
      'actual_hours.numeric' => 'Actual hours must be a number.',
      'actual_hours.min' => 'Actual hours cannot be negative.',
      'budget.numeric' => 'Budget must be a number.',
      'budget.min' => 'Budget cannot be negative.',
      'technologies.array' => 'Technologies must be an array.',
      'features.array' => 'Features must be an array.',
      'live_url.url' => 'Live URL must be a valid URL.',
      'github_url.url' => 'GitHub URL must be a valid URL.',
      'figma_url.url' => 'Figma URL must be a valid URL.',
      'behance_url.url' => 'Behance URL must be a valid URL.',
      'dribbble_url.url' => 'Dribbble URL must be a valid URL.',
      'captured_media.max' => 'You can upload a maximum of 10 gallery images.',
      'captured_media.*.file' => 'Each gallery item must be a file.',
      'captured_media.*.image' => 'Each gallery item must be an image.',
      'captured_media.*.mimes' => 'Gallery images must be jpeg, png, jpg, gif, or webp format.',
      'captured_media.*.max' => 'Each gallery image must not exceed 5MB.',
      'poster_image.file' => 'Poster must be a file.',
      'poster_image.image' => 'Poster must be an image.',
      'poster_image.mimes' => 'Poster image must be jpeg, png, jpg, gif, or webp format.',
      'poster_image.max' => 'Poster image must not exceed 5MB.',
      'existing_gallery_images.array' => 'Existing gallery images must be an array.',
      'existing_gallery_images.*.url' => 'Each existing gallery image must be a valid URL.',
      'existing_poster_image.url' => 'Existing poster image must be a valid URL.',
    ];
  }

  /**
   * Get custom attribute names for validation errors.
   */
  public function attributes(): array
  {
    return [
      'name' => 'project name',
      'customer_id' => 'customer',
      'production_type' => 'production type',
      'start_date' => 'start date',
      'end_date' => 'end date',
      'estimated_hours' => 'estimated hours',
      'actual_hours' => 'actual hours',
      'is_featured' => 'featured status',
      'is_public' => 'public status',
      'sort_order' => 'sort order',
      'meta_title' => 'meta title',
      'meta_description' => 'meta description',
      'live_url' => 'live URL',
      'github_url' => 'GitHub URL',
      'figma_url' => 'Figma URL',
      'behance_url' => 'Behance URL',
      'dribbble_url' => 'Dribbble URL',
      'captured_media' => 'gallery images',
      'poster_image' => 'poster image',
      'existing_gallery_images' => 'existing gallery images',
      'existing_poster_image' => 'existing poster image',
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

    // Ensure arrays are properly formatted
    if ($this->has('technologies') && is_string($this->technologies)) {
      $this->merge([
        'technologies' => json_decode($this->technologies, true) ?: []
      ]);
    }

    if ($this->has('features') && is_string($this->features)) {
      $this->merge([
        'features' => json_decode($this->features, true) ?: []
      ]);
    }

    // Ensure existing media arrays are properly formatted
    if ($this->has('existing_gallery_images') && is_string($this->existing_gallery_images)) {
      $this->merge([
        'existing_gallery_images' => json_decode($this->existing_gallery_images, true) ?: []
      ]);
    }
  }
}
