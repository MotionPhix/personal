<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Services\ProjectService;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;

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
      $project = $this->projectService->createProject($request->validated());

      // Handle media uploads if present
      if ($request->hasFile('captured_media')) {
        $this->projectService->uploadMedia(
          $project,
          $request->file('captured_media'),
          'gallery'
        );
      }

      return redirect()
        ->route('admin.projects.index')
        ->with('notify', [
          'type' => 'success',
          'title' => 'Project Created',
          'message' => 'Project has been successfully created!'
        ]);

    } catch (\Exception $e) {
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
      $this->projectService->updateProject($project, $request->validated());

      // Handle media uploads if present
      if ($request->hasFile('captured_media')) {
        $this->projectService->uploadMedia(
          $project,
          $request->file('captured_media'),
          'gallery'
        );
      }

      // Handle project poster upload if present
      if ($request->hasFile('poster_image')) {
        $this->projectService->uploadMedia(
          $project,
          $request->file('poster_image'),
          'poster'
        );
      }

      return redirect()
        ->route('admin.projects.show', $project)
        ->with('notify', [
          'type' => 'success',
          'title' => 'Project Updated',
          'message' => 'Project has been successfully updated!'
        ]);

    } catch (\Exception $e) {
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
   * Remove the specified project.
   */
  public function destroy(Project $project): RedirectResponse
  {
    try {
      $this->projectService->deleteProject($project);

      return redirect()
        ->route('admin.projects.index')
        ->with('notify', [
          'type' => 'success',
          'title' => 'Project Deleted',
          'message' => 'Project has been successfully deleted!'
        ]);

    } catch (\Exception $e) {
      return back()
        ->with('notify', [
          'type' => 'error',
          'title' => 'Error',
          'message' => 'Failed to delete project: ' . $e->getMessage()
        ]);
    }
  }
}
