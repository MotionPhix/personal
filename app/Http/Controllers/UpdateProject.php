<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;

class UpdateProject extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request, Project $project)
  {
    $customer = Customer::where('cid', $request->customer_id)->first();

    $request->merge(['customer_id' => $customer->id]);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'production' => 'required|date',
      'customer_id' => 'required|exists:customers,id',
      'description' => 'nullable|string',
      'media.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Update project details
    $project->update([
      'name' => $validated['name'],
      'production' => $validated['production'],
      'customer_id' => $customer->id,
      'description' => $validated['description'] ?? null,
    ]);

    // Handle image removals
    $existingMedia = $project->getMedia('bucket');
    // $existingMediaUuids = $existingMedia->pluck('uuid')->toArray();

    if (count($existingMedia)) {

      $requestImageUuids = array_map(fn($image) => $image['uuid'], $request->input('media', []));

      // Delete images that are not present in the request
      foreach ($existingMedia as $media) {
        if (!in_array($media->uuid, $requestImageUuids)) {
          $media->delete();
        }
      }

    }

    // Add new images
    if ($request->hasFile('media')) {

      foreach ($request->file('media') as $image) {

        // Check if the image is already present by its filename
        $fileName = $image->getClientOriginalName();
        $existingFileNames = $existingMedia->pluck('file_name')->toArray();

        // If the image file name is not in the existing list, upload it
        if (!in_array($fileName, $existingFileNames)) {
          $project->addMedia($image)->toMediaCollection('bucket');
        }
      }
    }

    return redirect()->route('auth.projects.index')->with('notify', [
      'type' => 'success',
      'title' => 'Update succeeded',
      'message' => 'Project has been successfully updated!'
    ]);
  }
}
