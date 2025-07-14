<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
  /**
   * Display the all available projects.
   */
  public function index(): Response
  {
    $projects = Project::with('media')->get(['id', 'pid']);

    return Inertia::render('projects/Index', [
      'projects' => $projects,
    ]);
  }

  /**
   * Display the available projects in admin for crud.
   */
  public function listing(): Response
  {
    // Fetch all projects and append media URLs
    $projects = Project::with('customer')->get()->map(function ($project) {
      return [
        'id' => $project->id,
        'pid' => $project->pid,
        'name' => $project->name,
        'customer' => $project->customer,
        'poster_url' => $project->getFirstMediaUrl('bucket')
        // $project->getMedia('bucket')->random()->getUrl(),
      ];
    });

    // $projects = Project::with('customer')->get();

    return Inertia::render('admin/projects/Index', [

      'can' => [
        'create_project' => Auth::user()->can('create'),
      ],

      'projects' => $projects,

    ]);
  }

  /**
   * Display the selected bucket.
   */
  public function show(Project $project): Response
  {
    $project->load('customer', 'media');

    $modify_project = [
      'pid' => $project->pid,  // Assuming 'pid' is the same as 'id'
      'name' => $project->name,
      'description' => $project->description,
      'completion_date' => $project->production->format('M, Y'),
      'customer' => [
        'name' => $project->customer->first_name . ' ' .$project->customer->last_name,
        'company_name' => $project->customer->company_name,
      ],
      // 'poster_url' => $project->getFirstMediaUrl('bucket'),
      'media' => $project->getMedia('bucket'),
    ];

    return Inertia::render('projects/Show', [
      'project' => $modify_project
    ]);
  }

  /**
   * Display the selected project for admin.
   */
  public function detail(Project $project): Response
  {
    $project->load('customer', 'media');

    $modify_project = [
      'pid' => $project->pid,  // Assuming 'pid' is the same as 'id'
      'name' => $project->name,
      'description' => $project->description,
      'completion_date' => $project->production->format('M, Y'),
      'customer' => [
        'first_name' => $project->customer->first_name,
        'last_name' => $project->customer->last_name,
        'company_name' => $project->customer->company_name,
      ],
      // 'poster_url' => $project->getFirstMediaUrl('bucket'),
      'media' => $project->getMedia('bucket'),
    ];

    return Inertia::render('admin/projects/Show', [
      'project' => $modify_project
    ]);
  }

  /**
   * Create a new instance of the bucket.
   */
  public function create(Customer $customer = null): Response
  {
    $project = [
      'id' => '',
      'pid' => '',
      'name' => '',
      'description' => '',
      'production' => '',
      'customer_id' => $customer->cid ?? '',
      'media' => [],
    ];

    $customers = Customer::select([
      'cid',
      'first_name',
      'last_name',
      'company_name'
    ])->get()
      ->transform(function ($customer) {
        return [
          'label' => trim($customer->first_name . ' ' . $customer->last_name),
          'company' => $customer->company_name,
          'value' => $customer->cid,
        ];
      });

    return Inertia::render('admin/projects/Form', [
      'project' => fn() => $project,
      'customers' => fn() => $customers
    ]);
  }

  /**
   * Edit the selected bucket.
   */
  public function edit(Project $project): Response
  {
    $customerId = Customer::where('id', $project->customer_id)->first()->cid;
    $project->customer_id = $customerId;

    $project->load('media');

    $customers = Customer::select([
      'cid',
      'first_name',
      'last_name',
      'company_name'
    ])->get()
      ->transform(function ($customer) {
        return [
          'label' => trim($customer->first_name . ' ' . $customer->last_name),
          'company' => $customer->company_name,
          'value' => $customer->cid,
        ];
      });

    return Inertia::render('admin/projects/Form', [
      'project' => $project,
      'customers' => $customers
    ]);
  }
}


namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\CustomerResource;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProjectController extends Controller
{
  /**
   * Display a listing of projects for public portfolio.
   */
  public function index(Request $request)
  {
    $query = Project::query()->public()->with('customer');

    // Apply search filter
    if ($request->filled('search')) {
      $query->search($request->search);
    }

    // Apply production type filter
    if ($request->filled('production_type')) {
      $query->byProductionType($request->production_type);
    }

    // Apply category filter
    if ($request->filled('category')) {
      $query->byCategory($request->category);
    }

    // Apply featured filter
    if ($request->filled('featured')) {
      $query->featured();
    }

    $query->ordered();

    $projects = $query->paginate($request->get('per_page', 12));

    return Inertia::render('Projects/Index', [
      'projects' => ProjectResource::collection($projects),
      'filters' => [
        'search' => $request->search,
        'production_type' => $request->production_type,
        'category' => $request->category,
        'featured' => $request->featured,
      ],
      'productionTypes' => Project::distinct()
        ->whereNotNull('production_type')
        ->where('is_public', true)
        ->pluck('production_type')
        ->sort()
        ->values(),
      'categories' => Project::distinct()
        ->whereNotNull('category')
        ->where('is_public', true)
        ->pluck('category')
        ->sort()
        ->values(),
    ]);
  }

  /**
   * Display the specified project for public portfolio.
   */
  public function show(Project $project)
  {
    if (!$project->is_public) {
      abort(404);
    }

    $project->load('customer');

    // Get related projects
    $relatedProjects = Project::public()
      ->where('id', '!=', $project->id)
      ->where(function ($query) use ($project) {
        $query->where('production_type', $project->production_type)
          ->orWhere('category', $project->category);
      })
      ->limit(3)
      ->get();

    return Inertia::render('Projects/Show', [
      'project' => new ProjectResource($project),
      'relatedProjects' => ProjectResource::collection($relatedProjects),
    ]);
  }

  /**
   * Display a listing of projects for authenticated users.
   */
  public function listing(Request $request)
  {
    $query = Project::query()->with('customer');

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

    $projects = $query->paginate($request->get('per_page', 15));

    return Inertia::render('Auth/Projects/Index', [
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
        'sort_by' => $sortField,
        'sort_direction' => $sortDirection,
      ],
      'customers' => CustomerResource::collection(Customer::active()->get()),
      'productionTypes' => Project::distinct()
        ->whereNotNull('production_type')
        ->pluck('production_type')
        ->sort()
        ->values(),
      'categories' => Project::distinct()
        ->whereNotNull('category')
        ->pluck('category')
        ->sort()
        ->values(),
      'stats' => [
        'total_projects' => Project::count(),
        'active_projects' => Project::active()->count(),
        'completed_projects' => Project::completed()->count(),
        'overdue_projects' => Project::where('end_date', '<', now())
          ->whereNotIn('status', ['completed', 'cancelled'])
          ->count(),
      ]
    ]);
  }

  /**
   * Show the form for creating a new project.
   */
  public function create(Request $request, Customer $customer = null)
  {
    $customers = Customer::active()->get();

    return Inertia::render('Auth/Projects/Create', [
      'customers' => CustomerResource::collection($customers),
      'selectedCustomer' => $customer ? new CustomerResource($customer) : null,
      'productionTypes' => Project::distinct()
        ->whereNotNull('production_type')
        ->pluck('production_type')
        ->sort()
        ->values(),
      'categories' => Project::distinct()
        ->whereNotNull('category')
        ->pluck('category')
        ->sort()
        ->values(),
    ]);
  }

  /**
   * Show the form for editing the specified project.
   */
  public function edit(Project $project)
  {
    $project->load('customer');
    $customers = Customer::active()->get();

    return Inertia::render('Auth/Projects/Edit', [
      'project' => new ProjectResource($project),
      'customers' => CustomerResource::collection($customers),
      'productionTypes' => Project::distinct()
        ->whereNotNull('production_type')
        ->pluck('production_type')
        ->sort()
        ->values(),
      'categories' => Project::distinct()
        ->whereNotNull('category')
        ->pluck('category')
        ->sort()
        ->values(),
    ]);
  }

  /**
   * Display the specified project for authenticated users.
   */
  public function detail(Project $project)
  {
    $project->load(['customer', 'images']);

    return Inertia::render('Auth/Projects/Show', [
      'project' => new ProjectResource($project),
    ]);
  }
}
