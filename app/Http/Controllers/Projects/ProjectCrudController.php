<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\StoreProjectRequest;
use App\Http\Requests\Projects\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ProjectCrudController extends Controller
{
  protected ProjectService $projectService;

  public function __construct(ProjectService $projectService)
  {
    $this->projectService = $projectService;
  }

  /**
   * Store a newly created project.
   */
  public function store(StoreProjectRequest $request): RedirectResponse
  {
    try {
      // Create the project first
      $project = $this->projectService->createProject($request->validated());

      // Handle media uploads if present
      $this->handleMediaUploads($request, $project);

      return redirect()
        ->route('admin.projects.show', $project)
        ->with('notify', [
          'type' => 'success',
          'title' => 'Project Created',
          'message' => 'Project has been successfully created!'
        ]);

    } catch (\Exception $e) {
      Log::error('Failed to create project', [
        'error' => $e->getMessage(),
        'request_data' => $request->validated()
      ]);

      return back()
        ->withInput()
        ->with('notify', [
          'type' => 'error',
          'title' => 'Error',
          'message' => 'Failed to create project: ' . $e->getMessage()
        ]);
    }
  }

  /**
   * Update the specified project.
   */
  public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
  {
    try {
      // Update project data
      $this->projectService->updateProject($project, $request->validated());

      // Handle media updates
      $this->handleMediaUpdates($request, $project);

      return redirect()
        ->route('admin.projects.show', $project)
        ->with('notify', [
          'type' => 'success',
          'title' => 'Project Updated',
          'message' => 'Project has been successfully updated!'
        ]);

    } catch (\Exception $e) {
      Log::error('Failed to update project', [
        'project_id' => $project->id,
        'error' => $e->getMessage(),
        'request_data' => $request->validated()
      ]);

      return back()
        ->withInput()
        ->with('notify', [
          'type' => 'error',
          'title' => 'Error',
          'message' => 'Failed to update project: ' . $e->getMessage()
        ]);
    }
  }

  /**
   * Handle media uploads for new projects.
   */
  private function handleMediaUploads(StoreProjectRequest $request, Project $project): void
  {
    $galleryFiles = $request->file('captured_media', []);
    $posterFile = $request->file('poster_image');

    if (!empty($galleryFiles) || $posterFile) {
      $this->projectService->updateProjectMedia(
        $project,
        $galleryFiles,
        $posterFile
      );
    }
  }

  /**
   * Handle media updates for existing projects.
   */
  private function handleMediaUpdates(UpdateProjectRequest $request, Project $project): void
  {
    // Get new files
    $newGalleryFiles = $request->file('captured_media', []);
    $newPosterFile = $request->file('poster_image');

    // Get existing files to preserve
    $existingGalleryUrls = $request->input('existing_gallery_images', []);
    $existingPosterUrl = $request->input('existing_poster_image');

    Log::info('Handling media updates', [
      'project_id' => $project->id,
      'new_gallery_count' => count($newGalleryFiles),
      'has_new_poster' => !!$newPosterFile,
      'existing_gallery_count' => count($existingGalleryUrls),
      'has_existing_poster' => !!$existingPosterUrl
    ]);

    // Update media using the service
    $this->projectService->updateProjectMedia(
      $project,
      $newGalleryFiles,
      $newPosterFile,
      $existingGalleryUrls,
      $existingPosterUrl
    );
  }
}
