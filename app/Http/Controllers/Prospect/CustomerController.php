<?php

namespace App\Http\Controllers\Prospect;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CustomerController extends Controller
{
  protected $customerService;

  public function __construct(CustomerService $customerService)
  {
    $this->customerService = $customerService;
  }

  /**
   * Display a listing of customers.
   */
  public function index(Request $request)
  {
    $customers = $this->customerService->getCustomers($request);
    $stats = $this->customerService->getCustomerStats();

    return Inertia::render('admin/customers/Index', [
      'customers' => CustomerResource::collection($customers),
      'filters' => [
        'search' => $request->search,
        'status' => $request->status,
        'sort_by' => $request->get('sort_by', 'created_at'),
        'sort_direction' => $request->get('sort_direction', 'desc'),
      ],
      'stats' => $stats
    ]);
  }

  /**
   * Show the form for creating a new customer.
   */
  public function create()
  {
    return Inertia::render('admin/customers/Create');
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

    $customer = $this->customerService->createCustomer($validator->validated());

    return redirect()->route('admin.customers.show', $customer->uuid)
      ->with('success', 'Customer created successfully.');
  }

  /**
   * Show the form for editing the specified customer.
   */
  public function edit(Customer $customer)
  {
    return Inertia::render('admin/customers/Edit', [
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

    $this->customerService->updateCustomer($customer, $validator->validated());

    return redirect()->route('admin.customers.show', $customer->uuid)
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

    $this->customerService->deleteCustomer($customer);

    return redirect()->route('admin.customer.index')
      ->with('success', 'Customer deleted successfully.');
  }

  /**
   * Show customer details with projects.
   */
  public function show(Customer $customer)
  {
    $customer = $this->customerService->getCustomer($customer, ['projects']);

    return Inertia::render('admin/customers/Show', [
      'customer' => new CustomerResource($customer),
    ]);
  }
}
