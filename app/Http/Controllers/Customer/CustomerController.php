<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
  /**
   * Display the all available customers.
   */
  public function index(): Response
  {
    $customers = Customer::all();

    return Inertia::render('admin/customers/Index', [
      'customers' => $customers,
    ]);
  }

  /**
   * Edit the selected customer.
   */
  public function edit(Customer $customer): Response
  {
    return Inertia::render('admin/customers/Form', [
      'customer' => $customer,
    ]);
  }

  /**
   * Create and instance of the customer.
   */
  public function create(Request $request): Response
  {
    $customer = [
      'id' => '',
      'cid' => '',
      'first_name' => '',
      'last_name' => '',
      'job_title' => '',
      'company_name' => '',
      'address' => [
        'street' => '',
        'city' => '',
        'state' => '',
        'country' => '',
      ],
    ];

    $template = 'admin/customers/Form';

    if ($request->query->get('modal')) {

      $template = 'admin/customers/FormModal';

    }

    return Inertia::render($template, [
      'customer' => $customer,
    ]);

  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'job_title' => 'required|string|max:255',
      'company_name' => 'nullable|string|max:255',
      'address.street' => 'sometimes|required|string|max:255',
      'address.city' => 'sometimes|required|string|max:255',
      'address.state' => 'nullable|string|max:255',
      'address.country' => 'sometimes|required|string|max:255',
    ], [
      'job_title.required' => 'Provide customer\'s position',
      'first_name.required' => 'Provide customer\'s first name',
      'last_name.required' => 'Provide customer\'s last name',
      'address.street.required' => 'Enter customer address\' street name',
      'address.city.required' => 'Enter customer address\' city name',
      'address.country.required' => 'Enter customer address\' country name',
    ]);

    $customer = Customer::create($validated);

    session()->flash('notify', [
      'type' => 'success',
      'title' => 'Great',
      'message' => 'New customer was added!'
    ]);

    if($request->query->get('modal')) {

      session()->flash('selected', [
        'id' => $customer->cid
      ]);

      return redirect(route('auth.projects.create', $customer->cid));

    }

    return redirect(route('auth.customer.index'));
  }

  public function update(Request $request, Customer $customer)
  {
    $validated = $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'job_title' => 'required|string|max:255',
      'company_name' => 'nullable|string|max:255',
      'address.street' => 'required|string|max:255',
      'address.city' => 'required|string|max:255',
      'address.state' => 'nullable|string|max:255',
      'address.country' => 'required|string|max:255',
    ], [
      'job_title.required' => 'Provide customer\'s position',
      'first_name.required' => 'Provide customer\'s first name',
      'last_name.required' => 'Provide customer\'s last name',
      'address.street.required' => 'Enter customer address\' street name',
      'address.city.required' => 'Enter customer address\' city name',
      'address.country.required' => 'Enter customer address\' country name',
    ]);

    $customer->update($validated);

    session()->flash('notify', [
      'type' => 'success',
      'title' => 'Super',
      'message' => 'Customer was updated successfully!'
    ]);

    return redirect(route('auth.customer.index'));
  }

  /**
   * Remove the specified customer from database.
   */
  public function destroy(Customer $customer)
  {
    foreach ($customer->projects as $project) {

      foreach ($project->images as $image) {

        if (file_exists(public_path($image->src))) {

          unlink(public_path($image->src));
        }

        $image->delete();

      }

      if ($project->poster && file_exists(public_path($project->poster))) {

        unlink(public_path($project->poster));

      }

      // Delete the bucket itself
      $project->delete();
    }

    // Deleting the customer
    $customer->delete();

    // Flash success message
    session()->flash('notify', [
      'type' => 'success',
      'title' => 'Deleted',
      'message' => 'Customer and their related projects were successfully deleted!',
    ]);

    // Redirect to the customer index page
    return redirect()->route('auth.customer.index');
  }
}


namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CustomerController extends Controller
{
  /**
   * Display a listing of customers.
   */
  public function index(Request $request)
  {
    $query = Customer::query();

    // Apply search filter
    if ($request->filled('search')) {
      $query->search($request->search);
    }

    // Apply status filter
    if ($request->filled('status')) {
      $query->where('status', $request->status);
    }

    // Apply sorting
    $sortField = $request->get('sort_by', 'created_at');
    $sortDirection = $request->get('sort_direction', 'desc');

    $allowedSortFields = ['first_name', 'last_name', 'company_name', 'created_at', 'updated_at'];
    if (in_array($sortField, $allowedSortFields)) {
      $query->orderBy($sortField, $sortDirection);
    }

    // Include project counts
    $query->withCount(['projects', 'activeProjects', 'completedProjects']);

    $customers = $query->paginate($request->get('per_page', 15));

    return Inertia::render('Auth/Customers/Index', [
      'customers' => CustomerResource::collection($customers),
      'filters' => [
        'search' => $request->search,
        'status' => $request->status,
        'sort_by' => $sortField,
        'sort_direction' => $sortDirection,
      ],
      'stats' => [
        'total_customers' => Customer::count(),
        'active_customers' => Customer::where('status', 'active')->count(),
        'prospect_customers' => Customer::where('status', 'prospect')->count(),
        'inactive_customers' => Customer::where('status', 'inactive')->count(),
      ]
    ]);
  }

  /**
   * Show the form for creating a new customer.
   */
  public function create()
  {
    return Inertia::render('Auth/Customers/Create');
  }

  /**
   * Store a newly created customer.
   */
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'job_title' => 'required|string|max:255',
      'company_name' => 'nullable|string|max:255',
      'email' => 'required|email|unique:customers,email',
      'phone_number' => 'nullable|string|max:255',
      'website' => 'nullable|url|max:255',
      'address' => 'nullable|array',
      'address.street' => 'nullable|string|max:255',
      'address.city' => 'nullable|string|max:255',
      'address.state' => 'nullable|string|max:255',
      'address.postal_code' => 'nullable|string|max:20',
      'address.country' => 'nullable|string|max:255',
      'notes' => 'nullable|string',
      'status' => ['nullable', Rule::in(['active', 'inactive', 'prospect'])],
      'avatar_url' => 'nullable|url|max:255',
    ]);

    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }

    $customer = Customer::create($validator->validated());

    return redirect()->route('auth.customer.index')
      ->with('success', 'Customer created successfully.');
  }

  /**
   * Show the form for editing the specified customer.
   */
  public function edit(Customer $customer)
  {
    return Inertia::render('Auth/Customers/Edit', [
      'customer' => new CustomerResource($customer),
    ]);
  }

  /**
   * Update the specified customer.
   */
  public function update(Request $request, Customer $customer)
  {
    $validator = Validator::make($request->all(), [
      'first_name' => 'sometimes|required|string|max:255',
      'last_name' => 'sometimes|required|string|max:255',
      'job_title' => 'sometimes|required|string|max:255',
      'company_name' => 'nullable|string|max:255',
      'email' => ['sometimes', 'required', 'email', Rule::unique('customers')->ignore($customer->id)],
      'phone_number' => 'nullable|string|max:255',
      'website' => 'nullable|url|max:255',
      'address' => 'nullable|array',
      'address.street' => 'nullable|string|max:255',
      'address.city' => 'nullable|string|max:255',
      'address.state' => 'nullable|string|max:255',
      'address.postal_code' => 'nullable|string|max:20',
      'address.country' => 'nullable|string|max:255',
      'notes' => 'nullable|string',
      'status' => ['nullable', Rule::in(['active', 'inactive', 'prospect'])],
      'avatar_url' => 'nullable|url|max:255',
    ]);

    if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
    }

    $customer->update($validator->validated());

    return redirect()->route('auth.customer.index')
      ->with('success', 'Customer updated successfully.');
  }

  /**
   * Remove the specified customer.
   */
  public function destroy(Customer $customer)
  {
    // Check if customer has projects
    if ($customer->projects()->count() > 0) {
      return back()->with('error', 'Cannot delete customer with existing projects.');
    }

    $customer->delete();

    return redirect()->route('auth.customer.index')
      ->with('success', 'Customer deleted successfully.');
  }

  /**
   * Show customer details with projects.
   */
  public function show(Customer $customer)
  {
    $customer->load(['projects' => function ($query) {
      $query->orderBy('created_at', 'desc');
    }]);

    return Inertia::render('Auth/Customers/Show', [
      'customer' => new CustomerResource($customer),
    ]);
  }
}
