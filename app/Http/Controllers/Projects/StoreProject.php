<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;

class StoreProject extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|max:255',
      'production' => 'required|date',
      'customer_id' => 'required|exists:customers,cid',
      'description' => 'nullable|string',
      'captured_media' => 'required',
      'captured_media.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
    ], [
      'name.required' => 'Please provide the project name.',
      'name.max' => 'Please shorten your input to 255 characters long.',

      'production.required' => 'Specify the completion date for this project.',
      'production.date' => 'Please provide a valid date.',

      'customer_id.required' => 'Pick a customer for this project.',
      'customer_id.exists' => 'The provided customer does not exist.',

      'captured_media.required' => 'Please upload at least a poster image.',
      'captured_media.*.image' => 'The uploaded file is not an image.',
      'captured_media.*.mimes' => 'Supported formats are .jpeg, .png, .jpg, and .webp.',
      'captured_media.*.max' => 'The image size should not exceed 2MB.',
    ]);

    $customer = Customer::where('cid', $request->customer_id)->first();
    $request->merge(['customer_id' => $customer->id]);

    $project = Project::create([
      'name' => $validated['name'],
      'production' => $validated['production'],
      'customer_id' => $customer->id,
      'description' => $validated['description'] ?? null,
    ]);

    if ($request->hasFile('captured_media')) {

      foreach ($request->file('captured_media') as $image) {

        $project->addMedia($image)->toMediaCollection('bucket');
      }
    }

    return redirect()->route('auth.projects.index')->with('notify', [
      'type' => 'success',
      'title' => 'New project',
      'message' => 'Project has been successfully added!'
    ]);
  }
}


namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class StoreProject extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'customer_id' => 'required|exists:customers,id',
      'name' => 'required|string|max:255',
      'slug' => 'nullable|string|max:255|unique:projects,slug',
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
    ]);

    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }

    $data = $validator->validated();

    // Generate slug if not provided
    if (empty($data['slug'])) {
      $data['slug'] = Str::slug($data['name']);
    }

    // Set default values
    $data['status'] = $data['status'] ?? 'not_started';
    $data['priority'] = $data['priority'] ?? 'medium';
    $data['is_featured'] = $data['is_featured'] ?? false;
    $data['is_public'] = $data['is_public'] ?? true;

    $project = Project::create($data);

    return redirect()->route('auth.projects.index')
      ->with('success', 'Project created successfully.');
  }
}
