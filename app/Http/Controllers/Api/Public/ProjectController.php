<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of public projects.
     */
    public function index(Request $request)
    {
        $projects = $this->projectService->getPublicProjects($request);

        return ProjectResource::collection($projects);
    }

    /**
     * Display the specified public project.
     */
    public function show(Project $project)
    {
        if (!$project->is_public) {
            abort(404, 'Project not found');
        }

        $project = $this->projectService->getProject($project, ['customer', 'media']);

        return new ProjectResource($project);
    }
}
