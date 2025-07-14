<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
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


namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class DestroyProject extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request, Project $project, $image = null)
  {
    // If image parameter is provided, delete specific image
    if ($image) {
      $media = $project->getMedia()->where('id', $image)->first();
      if ($media) {
        $media->delete();
        return back()->with('success', 'Image deleted successfully.');
      }
      return back()->with('error', 'Image not found.');
    }

    // Delete the entire project
    $project->delete();

    return redirect()->route('auth.projects.index')
      ->with('success', 'Project deleted successfully.');
  }
}
