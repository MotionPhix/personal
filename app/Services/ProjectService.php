<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
  /**
   * Get filtered and paginated projects for public portfolio.
   */
  public function getPublicProjects(Request $request): LengthAwarePaginator
  {
    $query = Project::query()
      ->public()
      ->with(['customer', 'media']);

    $this->applyFilters($query, $request);
    $query->ordered();

    return $query->paginate($request->get('per_page', 10));
  }

  /**
   * Get filtered and paginated projects for admin dashboard.
   */
  public function getAdminProjects(Request $request): LengthAwarePaginator
  {
    $query = Project::query()->with(['customer', 'media']);

    $this->applyFilters($query, $request);
    $this->applySorting($query, $request);

    return $query->paginate($request->get('per_page', 15));
  }

  /**
   * Get a single project with relationships.
   */
  public function getProject(Project $project, array $with = []): Project
  {
    if (!empty($with)) {
      $project->load($with);
    }

    return $project;
  }

  /**
   * Create a new project.
   */
  public function createProject(array $data): Project
  {
    // Generate slug if not provided
    if (empty($data['slug']) && !empty($data['name'])) {
      $data['slug'] = $this->generateUniqueSlug($data['name']);
    }

    // Generate PID if not provided
    if (empty($data['uuid'])) {
      $data['uuid'] = (string)Str::uuid();
    }

    return Project::create($data);
  }

  /**
   * Update an existing project.
   */
  public function updateProject(Project $project, array $data): Project
  {
    // Update slug if name changed and slug not provided
    if (isset($data['name']) && empty($data['slug'])) {
      $data['slug'] = $this->generateUniqueSlug($data['name'], $project->id);
    }

    $project->update($data);
    return $project->fresh();
  }

  /**
   * Delete a project.
   */
  public function deleteProject(Project $project): bool
  {
    return $project->delete();
  }

  /**
   * Get related projects based on production type and category.
   */
  public function getRelatedProjects(Project $project, int $limit = 3): Collection
  {
    return Project::public()
      ->where('id', '!=', $project->id)
      ->where(function ($query) use ($project) {
        $query->where('production_type', $project->production_type)
          ->orWhere('category', $project->category);
      })
      ->limit($limit)
      ->get();
  }

  /**
   * Get project statistics.
   */
  public function getProjectStats(): array
  {
    return [
      'total_projects' => Project::count(),
      'active_projects' => Project::active()->count(),
      'completed_projects' => Project::completed()->count(),
      'featured_projects' => Project::featured()->count(),
      'public_projects' => Project::public()->count(),
      'overdue_projects' => Project::where('end_date', '<', now())
        ->whereNotIn('status', ['completed', 'cancelled'])
        ->count(),
      'projects_by_status' => Project::selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status'),
      'projects_by_production_type' => Project::selectRaw('production_type, COUNT(*) as count')
        ->whereNotNull('production_type')
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
  }

  /**
   * Get distinct production types.
   */
  public function getProductionTypes(bool $publicOnly = false): \Illuminate\Support\Collection
  {
    $query = Project::distinct()->whereNotNull('production_type');

    if ($publicOnly) {
      $query->where('is_public', true);
    }

    return $query->pluck('production_type')->sort()->values();
  }

  /**
   * Get distinct categories.
   */
  public function getCategories(bool $publicOnly = false): \Illuminate\Support\Collection
  {
    $query = Project::distinct()->whereNotNull('category');

    if ($publicOnly) {
      $query->where('is_public', true);
    }

    return $query->pluck('category')->sort()->values();
  }

  /**
   * Get all technologies used across projects.
   */
  public function getTechnologies(): Collection
  {
    return Project::whereNotNull('technologies')
      ->pluck('technologies')
      ->flatten()
      ->unique()
      ->sort()
      ->values();
  }

  /**
   * Reorder projects.
   */
  public function reorderProjects(array $projects): bool
  {
    foreach ($projects as $projectData) {
      Project::where('id', $projectData['id'])
        ->update(['sort_order' => $projectData['sort_order']]);
    }

    return true;
  }

  /**
   * Upload media for a project.
   */
  public function uploadMedia(Project $project, array $files, string $collection = 'gallery'): array
  {
    $uploadedFiles = [];

    foreach ($files as $file) {
      $media = $project->addMedia($file)
        ->toMediaCollection($collection);

      $uploadedFiles[] = [
        'id' => $media->id,
        'name' => $media->name,
        'url' => $media->getUrl(),
        'thumb_url' => $media->getUrl('thumb'),
      ];
    }

    return $uploadedFiles;
  }

  /**
   * Apply filters to the query.
   */
  private function applyFilters($query, Request $request): void
  {
    if ($request->filled('search')) {
      $query->search($request->search);
    }

    if ($request->filled('status')) {
      $query->byStatus($request->status);
    }

    if ($request->filled('production_type')) {
      $query->byProductionType($request->production_type);
    }

    if ($request->filled('category')) {
      $query->byCategory($request->category);
    }

    if ($request->filled('priority')) {
      $query->byPriority($request->priority);
    }

    if ($request->filled('customer_id')) {
      $query->where('customer_id', $request->customer_id);
    }

    if ($request->filled('featured')) {
      $query->featured();
    }

    if ($request->filled('public')) {
      $query->public();
    }

    if ($request->filled('start_date')) {
      $query->where('start_date', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
      $query->where('end_date', '<=', $request->end_date);
    }
  }

  /**
   * Apply sorting to the query.
   */
  private function applySorting($query, Request $request): void
  {
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
  }

  /**
   * Generate a unique slug for a project.
   */
  private function generateUniqueSlug(string $name, ?int $excludeId = null): string
  {
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $counter = 1;

    while ($this->slugExists($slug, $excludeId)) {
      $slug = $originalSlug . '-' . $counter;
      $counter++;
    }

    return $slug;
  }

  /**
   * Check if a slug already exists.
   */
  private function slugExists(string $slug, ?int $excludeId = null): bool
  {
    $query = Project::where('slug', $slug);

    if ($excludeId) {
      $query->where('id', '!=', $excludeId);
    }

    return $query->exists();
  }
}
