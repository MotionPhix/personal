<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    protected CustomerService $customerService;

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

        // Include relationships if requested
        if ($request->filled('include')) {
            $includes = explode(',', $request->include);
            $allowedIncludes = ['projects'];
            $validIncludes = array_intersect($includes, $allowedIncludes);

            if (!empty($validIncludes)) {
                $customers->load($validIncludes);
            }
        }

        return CustomerResource::collection($customers);
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
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $customer = $this->customerService->createCustomer($validator->validated());

        return new CustomerResource($customer);
    }

    /**
     * Display the specified customer.
     */
    public function show(Request $request, Customer $customer)
    {
        $includes = [];
        if ($request->filled('include')) {
            $requestedIncludes = explode(',', $request->include);
            $allowedIncludes = ['projects'];
            $includes = array_intersect($requestedIncludes, $allowedIncludes);
        }

        $customer = $this->customerService->getCustomer($customer, $includes);

        return new CustomerResource($customer);
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
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $customer = $this->customerService->updateCustomer($customer, $validator->validated());

        return new CustomerResource($customer);
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer)
    {
        $this->customerService->deleteCustomer($customer);

        return response()->json([
            'message' => 'Customer deleted successfully'
        ]);
    }

    /**
     * Restore a soft-deleted customer.
     */
    public function restore($cid)
    {
        $customer = Customer::withTrashed()->where('cid', $cid)->firstOrFail();
        $customer->restore();

        return new CustomerResource($customer);
    }

    /**
     * Get customer statistics.
     */
    public function stats()
    {
        $stats = $this->customerService->getCustomerStats();
        return response()->json($stats);
    }
}
