<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
  /**
   * Display a listing of projects.
   */
  public function index(Request $request)
  {
    $query = Project::query();

    // Apply search filter
    if ($request->filled('search')) {
      $query->search($request->search);
    }

    // Apply status filter
    if ($request->filled('status')) {
      $query->byStatus($request->status);
    }

    // Apply production type filter
    if ($request->filled('production_type')) {
      $query->byProductionType($request->production_type);
    }

    // Apply category filter
    if ($request->filled('category')) {
      $query->byCategory($request->category);
    }

    // Apply priority filter
    if ($request->filled('priority')) {
      $query->byPriority($request->priority);
    }

    // Apply customer filter
    if ($request->filled('customer_id')) {
      $query->where('customer_id', $request->customer_id);
    }

    // Apply featured filter
    if ($request->filled('featured')) {
      $query->featured();
    }

    // Apply public filter
    if ($request->filled('public')) {
      $query->public();
    }

    // Apply date range filter
    if ($request->filled('start_date')) {
      $query->where('start_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
      $query->where('end_date', '<=', $request->end_date);
    }

    // Apply sorting
    $sortField = $request->get('sort_by', 'created_at');
    $sortDirection = $request->get('sort_direction', 'desc');

    $allowedSortFields = [
      'name', 'status', 'priority', 'start_date', 'end_date',
      'estimated_hours', 'actual_hours', 'budget', 'sort_order',
      'created_at', 'updated_at'
    ];

    if (in_array($sortField, $allowedSortFields)) {
      $query->orderBy($sortField, $sortDirection);
    } else {
      $query->ordered();
    }

    // Include relationships if requested
    if ($request->filled('include')) {
      $includes = explode(',', $request->include);
      $allowedIncludes = ['customer', 'images'];
      $validIncludes = array_intersect($includes, $allowedIncludes);

      if (!empty($validIncludes)) {
        $query->with($validIncludes);
      }
    }

    $projects = $query->paginate($request->get('per_page', 15));

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

    $data = $validator->validated();

    // Generate slug if not provided
    if (empty($data['slug'])) {
      $data['slug'] = Str::slug($data['name']);
    }

    $project = Project::create($data);

    return new ProjectResource($project);
  }

  /**
   * Display the specified project.
   */
  public function show(Request $request, Project $project)
  {
    // Load relationships if requested
    if ($request->filled('include')) {
      $includes = explode(',', $request->include);
      $allowedIncludes = ['customer', 'images'];
      $validIncludes = array_intersect($includes, $allowedIncludes);

      if (!empty($validIncludes)) {
        $project->load($validIncludes);
      }
    }

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

    $data = $validator->validated();

    // Update slug if name changed
    if (isset($data['name']) && empty($data['slug'])) {
      $data['slug'] = Str::slug($data['name']);
    }

    $project->update($data);

    return new ProjectResource($project);
  }

  /**
   * Remove the specified project.
   */
  public function destroy(Project $project)
  {
    $project->delete();

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
    $stats = [
      'total_projects' => Project::count(),
      'active_projects' => Project::active()->count(),
      'completed_projects' => Project::completed()->count(),
      'featured_projects' => Project::featured()->count(),
      'overdue_projects' => Project::where('end_date', '<', now())
        ->whereNotIn('status', ['completed', 'cancelled'])
        ->count(),
      'projects_by_status' => Project::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status'),
      'projects_by_production_type' => Project::selectRaw('production_type, COUNT(*) as count')
        ->groupBy('production_type')
        ->pluck('count', 'production_type'),
      'average_project_duration' => Project::whereNotNull('start_date')
        ->whereNotNull('end_date')
        ->selectRaw('AVG(DATEDIFF(end_date, start_date)) as avg_duration')
        ->value('avg_duration'),
      'total_estimated_hours' => Project::sum('estimated_hours'),
      'total_actual_hours' => Project::sum('actual_hours'),
      'total_budget' => Project::sum('budget'),
    ];

    return response()->json($stats);
  }

  /**
   * Get production types.
   */
  public function productionTypes()
  {
    $types = Project::distinct()
      ->whereNotNull('production_type')
      ->pluck('production_type')
      ->sort()
      ->values();

    return response()->json($types);
  }

  /**
   * Get categories.
   */
  public function categories()
  {
    $categories = Project::distinct()
      ->whereNotNull('category')
      ->pluck('category')
      ->sort()
      ->values();

    return response()->json($categories);
  }

  /**
   * Get technologies.
   */
  public function technologies()
  {
    $technologies = Project::whereNotNull('technologies')
      ->pluck('technologies')
      ->flatten()
      ->unique()
      ->sort()
      ->values();

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

    $uploadedFiles = [];
    $collection = $request->input('collection', 'gallery');

    foreach ($request->file('files') as $file) {
      $media = $project->addMediaFromRequest('files')
        ->each(function ($fileAdder) use ($collection) {
          $fileAdder->toMediaCollection($collection);
        });

      $uploadedFiles[] = $media;
    }

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

    foreach ($request->input('projects') as $projectData) {
      Project::where('id', $projectData['id'])
        ->update(['sort_order' => $projectData['sort_order']]);
    }

    return response()->json([
      'message' => 'Projects reordered successfully'
    ]);
  }
}
