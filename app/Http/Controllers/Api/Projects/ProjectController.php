<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of projects.
     */
    public function index(Request $request)
    {
        $projects = $this->projectService->getAdminProjects($request);

        // Include relationships if requested
        if ($request->filled('include')) {
            $includes = explode(',', $request->include);
            $allowedIncludes = ['customer', 'media'];
            $validIncludes = array_intersect($includes, $allowedIncludes);

            if (!empty($validIncludes)) {
                $projects->load($validIncludes);
            }
        }

        return ProjectResource::collection($projects);
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
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
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $project = $this->projectService->createProject($validator->validated());

        return new ProjectResource($project);
    }

    /**
     * Display the specified project.
     */
    public function show(Request $request, Project $project)
    {
        $includes = [];
        if ($request->filled('include')) {
            $requestedIncludes = explode(',', $request->include);
            $allowedIncludes = ['customer', 'media'];
            $includes = array_intersect($requestedIncludes, $allowedIncludes);
        }

        $project = $this->projectService->getProject($project, $includes);

        return new ProjectResource($project);
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes|required|exists:customers,id',
            'name' => 'sometimes|required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('projects')->ignore($project->id)],
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
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $project = $this->projectService->updateProject($project, $validator->validated());

        return new ProjectResource($project);
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Project $project)
    {
        $this->projectService->deleteProject($project);

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }

  /**
   * Restore a soft-deleted project.
   */
  public function restore($pid)
  {
    $project = Project::withTrashed()->where('pid', $pid)->firstOrFail();
    $project->restore();

    return new ProjectResource($project);
  }

    /**
     * Get project statistics.
     */
    public function stats()
    {
        $stats = $this->projectService->getProjectStats();
        return response()->json($stats);
    }

    /**
     * Get production types.
     */
    public function productionTypes()
    {
        $types = $this->projectService->getProductionTypes();
        return response()->json($types);
    }

    /**
     * Get categories.
     */
    public function categories()
    {
        $categories = $this->projectService->getCategories();
        return response()->json($categories);
    }

    /**
     * Get technologies.
     */
    public function technologies()
    {
        $technologies = $this->projectService->getTechnologies();
        return response()->json($technologies);
    }

    /**
     * Upload project media.
     */
    public function uploadMedia(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'collection' => 'required|in:gallery,poster,documents',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $uploadedFiles = $this->projectService->uploadMedia(
            $project,
            $request->file('files'),
            $request->input('collection', 'gallery')
        );

        return response()->json([
            'message' => 'Files uploaded successfully',
            'files' => $uploadedFiles
        ]);
    }

    /**
     * Reorder projects.
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'projects' => 'required|array',
            'projects.*.id' => 'required|exists:projects,id',
            'projects.*.sort_order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->projectService->reorderProjects($request->input('projects'));

        return response()->json([
            'message' => 'Projects reordered successfully'
        ]);
    }
}
