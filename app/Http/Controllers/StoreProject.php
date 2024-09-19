<?php

namespace App\Http\Controllers;

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
