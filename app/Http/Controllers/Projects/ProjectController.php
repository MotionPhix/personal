<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\CustomerResource;
use App\Services\ProjectService;
use App\Services\CustomerService;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
  protected ProjectService $projectService;
  protected CustomerService $customerService;

  public function __construct(ProjectService $projectService, CustomerService $customerService)
  {
    $this->projectService = $projectService;
    $this->customerService = $customerService;
  }

  /**
   * Display public portfolio projects.
   */
  public function index(Request $request): Response
  {
    $projects = $this->projectService->getPublicProjects($request);

    return Inertia::render('projects/Index', [
      'projects' => ProjectResource::collection($projects),
      'filters' => [
        'search' => $request->search,
        'production_type' => $request->production_type,
        'category' => $request->category,
        'featured' => $request->featured,
      ],
      'productionTypes' => $this->projectService->getProductionTypes(true),
      'categories' => $this->projectService->getCategories(true),
    ]);
  }

  /**
   * Display the specified project for public portfolio.
   */
  public function show(Project $project): Response
  {
    if (!$project->is_public) {
      abort(404);
    }

    $project = $this->projectService->getProject($project, ['customer', 'media']);
    $relatedProjects = $this->projectService->getRelatedProjects($project);

    return Inertia::render('projects/Show', [
      'project' => new ProjectResource($project),
      'relatedProjects' => ProjectResource::collection($relatedProjects),
    ]);
  }

  /**
   * Display projects for admin dashboard.
   */
  public function listing(Request $request): Response
  {
    $projects = $this->projectService->getAdminProjects($request);
    $stats = $this->projectService->getProjectStats();

    return Inertia::render('admin/projects/Index', [
      'projects' => ProjectResource::collection($projects),
      'filters' => [
        'search' => $request->search,
        'status' => $request->status,
        'production_type' => $request->production_type,
        'category' => $request->category,
        'priority' => $request->priority,
        'customer_id' => $request->customer_id,
        'featured' => $request->featured,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'sort_by' => $request->get('sort_by', 'created_at'),
        'sort_direction' => $request->get('sort_direction', 'desc'),
      ],
      'customers' => $this->customerService->getActiveCustomers(),
      'productionTypes' => $this->projectService->getProductionTypes(),
      'categories' => $this->projectService->getCategories(),
      'stats' => $stats,
      'can' => [
        'create_project' => Auth::user()?->can('create'),
      ],
    ]);
  }

  /**
   * Display the specified project for admin.
   */
  public function detail(Project $project): Response
  {
    $project = $this->projectService->getProject($project, ['customer', 'media']);

    return Inertia::render('admin/projects/Show', [
      'project' => new ProjectResource($project),
    ]);
  }

  /**
   * Show the form for creating a new project.
   */
  public function create(Customer $customer = null): Response
  {
    $customers = $this->customerService->getActiveCustomers();

    return Inertia::render('admin/projects/Create', [
      'customers' => $customers,
      'selectedCustomer' => $customer ? new CustomerResource($customer) : null,
      'productionTypes' => $this->projectService->getProductionTypes(),
      'categories' => $this->projectService->getCategories(),
    ]);
  }

  /**
   * Show the form for editing the specified project.
   */
  public function edit(Project $project): Response
  {
    $project = $this->projectService->getProject($project, ['customer', 'media']);
    $customers = $this->customerService->getActiveCustomers();

    return Inertia::render('admin/projects/Edit', [
      'project' => new ProjectResource($project),
      'customers' => $customers,
      'productionTypes' => $this->projectService->getProductionTypes(),
      'categories' => $this->projectService->getCategories(),
    ]);
  }
}
