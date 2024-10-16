<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Project;
use App\Models\User;
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

    return Inertia::render('Projects/Index', [
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

    return Inertia::render('Admin/Projects/Index', [

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

    return Inertia::render('Projects/Show', [
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

    return Inertia::render('Admin/Projects/Show', [
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

    return Inertia::render('Admin/Projects/Form', [
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

    return Inertia::render('Admin/Projects/Form', [
      'project' => $project,
      'customers' => $customers
    ]);
  }
}
