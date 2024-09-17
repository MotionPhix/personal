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

    // Initialize an array to store old files (URLs or paths)
    $oldFiles = [];

    // Gather old file paths (URLs) from the request
    if ($request->has('captured_media')) {

      foreach ($request->captured_media as $media) {

        if (! $media instanceof \Illuminate\Http\UploadedFile) {

          // Add the old file path (URL) to the array
          $oldFiles[] = $media;

        }
      }

    }

    // Get the media currently associated with the project
    $projectMedia = $project->getMedia('bucket');

    // Compare and delete media that are no longer in the request
    foreach ($projectMedia as $image) {

      $mediaUrl = $image->getFullUrl();  // Get the full URL of the media

      // If the media URL is not in the list of old files, delete it
      if (!in_array($mediaUrl, $oldFiles)) {

        $image->delete();

      }

    }

    // Now add the new files from the request
    if ($request->hasFile('captured_media')) {

      foreach ($request->file('captured_media') as $newFile) {

        if ($newFile instanceof \Illuminate\Http\UploadedFile) {

          // Add the new media to the media collection
          $project->addMedia($newFile)
            ->toMediaCollection('bucket');

        }

      }

    }

    /*if ($request->has('captured_media')) {

      foreach ($request->captured_media as $media) {

        // Check if the file is not an instance of UploadedFile (i.e., it's an old file to keep)
        if (!$media instanceof \Illuminate\Http\UploadedFile) {

          $oldFiles[] = $media;  // Add the file path (URL) to the array

        }

        $projectMedia = $project->getMedia('bucket');

        // Compare and delete media that are in $projectMedia but not in the $oldFiles
        foreach ($projectMedia as $image) {

          $mediaUrl = $image->getFullUrl();  // Get the full URL of the media

          // If the media URL is not in the list of old files, delete it
          if (!in_array($mediaUrl, $oldFiles)) {

            $image->delete();

          }

        }

        // just add the new files here
        if ($media instanceof \Illuminate\Http\UploadedFile) {

          $project->addMedia($media)
            ->toMediaCollection('bucket');

        }

      }

    }*/

    // Add new images
    /*if ($request->hasFile('media')) {

      $project->addMultipleMediaFromRequest(['media'])->each(function ($fileAdder) {

        $fileAdder->toMediaCollection('bucket');

      });

    }*/

    return redirect()->route('auth.projects.index')->with('notify', [
      'type' => 'success',
      'title' => 'Update succeeded',
      'message' => 'Project has been successfully updated!'
    ]);
  }
}
