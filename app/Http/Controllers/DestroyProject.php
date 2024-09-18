<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DestroyProject extends Controller
{
  public function __invoke(Project $project, Media $image = null)
  {
    // Check if the image parameter is provided
    if ($image) {

      // Delete the image record from the database
      if ($image->delete()) {

        return redirect()->back()->with('notify', [
          'type' => 'success',
          'title' => 'Image deleted',
          'message' => 'Image was deleted successfully!'
        ]);

      };

    }

    $project->delete();

    return redirect()->route('auth.projects.index')->with('notify', [
      'type' => 'success',
      'title' => 'Deletion succeeded',
      'message' => 'Project and its images were deleted successfully!'
    ]);
  }
}
