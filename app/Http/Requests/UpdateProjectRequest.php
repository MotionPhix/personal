<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true; // Add proper authorization logic here
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    $project = $this->route('project');

    return [
      'customer_id' => 'sometimes|required|exists:customers,uuid',
      'name' => 'sometimes|required|string|max:255',
      'slug' => ['nullable', 'string', 'max:255', Rule::unique('projects')->ignore($project->id)],
      'description' => 'nullable|string',
      'short_description' => 'nullable|string|max:500',
      'production_type' => 'nullable|string|max:255',
      'category' => 'nullable|string|max:255',
      'status' => ['nullable', Rule::in(['not_started', 'in_progress', 'on_hold', 'completed', 'cancelled'])],
      'priority' => ['nullable', Rule::in(['low', 'medium', 'high', 'urgent'])],
      'start_date' => 'nullable|date',
      'end_date' => 'nullable|date|after_or_equal:start_date',
      'estimated_hours' => 'nullable|numeric|min:0',
      'actual_hours' => 'nullable|numeric|min:0',
      'budget' => 'nullable|numeric|min:0',
      'technologies' => 'nullable|array',
      'technologies.*' => 'string|max:255',
      'features' => 'nullable|array',
      'features.*' => 'string|max:255',
      'challenges' => 'nullable|string',
      'solutions' => 'nullable|string',
      'results' => 'nullable|string',
      'client_feedback' => 'nullable|string',
      'is_featured' => 'boolean',
      'is_public' => 'boolean',
      'sort_order' => 'nullable|integer|min:0',
      'meta_title' => 'nullable|string|max:255',
      'meta_description' => 'nullable|string',
      'live_url' => 'nullable|url|max:255',
      'github_url' => 'nullable|url|max:255',
      'figma_url' => 'nullable|url|max:255',
      'behance_url' => 'nullable|url|max:255',
      'dribbble_url' => 'nullable|url|max:255',
      'captured_media' => 'nullable|array',
      'captured_media.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
      'poster_image.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
    ];
  }

  /**
   * Get custom error messages for validation rules.
   */
  public function messages(): array
  {
    return [
      'name.required' => 'Please provide the project name.',
      'name.max' => 'Please shorten your input to 255 characters long.',
      'customer_id.required' => 'Pick a customer for this project.',
      'customer_id.exists' => 'The provided customer does not exist.',
      'start_date.date' => 'Please provide a valid start date.',
      'end_date.date' => 'Please provide a valid end date.',
      'end_date.after_or_equal' => 'End date must be after or equal to start date.',
      'captured_media.*.image' => 'The uploaded file is not an image.',
      'captured_media.*.mimes' => 'Supported formats are .jpeg, .png, .jpg, and .webp.',
      'captured_media.*.max' => 'The image size should not exceed 10MB.',
      'poster_image.*.image' => 'The uploaded file is not an image.',
      'poster_image.*.mimes' => 'Supported formats are .jpeg, .png, .jpg, and .webp.',
      'poster_image.*.max' => 'The image size should not exceed 10MB.',
    ];
  }

  /**
   * Prepare the data for validation.
   */
  protected function prepareForValidation(): void
  {
    // Convert boolean values
    if ($this->has('is_featured')) {
      $this->merge(['is_featured' => $this->boolean('is_featured')]);
    }

    if ($this->has('is_public')) {
      $this->merge(['is_public' => $this->boolean('is_public')]);
    }

    // Convert customer CID to ID if needed
    if ($this->has('customer_id') && is_string($this->customer_id)) {
      $customer = \App\Models\Customer::where('uuid', $this->customer_cid)->first();

      if ($customer) {
        $this->merge(['customer_id' => $customer->id]);
      }
    }
  }
}
