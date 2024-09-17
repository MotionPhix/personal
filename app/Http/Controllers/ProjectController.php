<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\Request;
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
      'projects' => $projects,
    ]);
  }

  /**
   * Display the selected bucket.
   */
  public function show(Project $project): Response
  {
    return Inertia::render('Projects/Show', [
      'project' => $project->load('customer', 'media'),
    ]);
  }

  /**
   * Display the selected bucket.
   */
  public function detail(Project $project): Response
  {
    return Inertia::render('Admin/Projects/Show', [
      'project' => $project->load('customer', 'media')
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

    return Inertia::render('Admin/Projects/Form', [
      'project' => $project,
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

    return Inertia::render('Admin/Projects/Form', [
      'project' => $project,
    ]);
  }

  /**
   * Store a project
   */
  public function store(Request $request)
  {
    $customer = Customer::where('cid', $request->customer_id)->first();
    $request->merge(['customer_id' => $customer->id]);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'production' => 'required|date',
      'customer_id' => 'required|exists:customers,id',
      'description' => 'nullable|string',
      'media.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $project = Project::create([
      'name' => $validated['name'],
      'production' => $validated['production'],
      'customer_id' => $customer->id,
      'description' => $validated['description'] ?? null,
    ]);

    if ($request->hasFile('media')) {

      foreach ($request->file('media') as $image) {

        $project->addMedia($image)->toMediaCollection('bucket');

      }
    }

    return redirect()->route('auth.projects.index')->with('notify', [
      'type' => 'success',
      'title' => 'New project',
      'message' => 'Project has been successfully added!'
    ]);
  }

}
