<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DestroyProject extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Project $project, Media $image = null)
  {
    // Check if the image parameter is provided
    if ($image) {
      // If the image is provided, delete the specific image
      $imageRecord = $project->media()->where('uuid', $image->uuid)->first();

      if ($imageRecord) {
        // Delete the image record from the database
        $imageRecord->delete();

        return redirect()->back()->with('notify', [
          'type' => 'success',
          'title' => 'Image deleted',
          'message' => 'Image was deleted successfully!'
        ]);
      }

      return redirect()->back()->with('notify', [
        'type' => 'danger',
        'title' => 'Deletion failed',
        'message' => 'Image could not be found!'
      ]);
    }

    $project->delete();

    return redirect()->route('auth.projects.index')->with('notify', [
      'type' => 'success',
      'title' => 'Deletion succeeded',
      'message' => 'Project and its images were deleted successfully!'
    ]);

  }
}
