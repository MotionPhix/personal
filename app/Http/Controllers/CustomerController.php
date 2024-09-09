<?php

namespace App\Http\Controllers;

use \App\Models\Customer;
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

    return Inertia::render('Admin/Customers/Index', [
      'customers' => $customers,
    ]);
  }

  /**
   * Edit the selected customer.
   */
  public function edit(Customer $customer): Response
  {
    return Inertia::render('Admin/Customers/Form', [
      'customer' => $customer,
    ]);
  }

  /**
   * Create and instance of the customer.
   */
  public function create(): Response
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

    return Inertia::render('Admin/Customers/Form', [
      'customer' => $customer,
    ]);
  }

  public function store(Request $request)
  {
    session()->flash('notify', [
      'type' => 'danger',
      'title' => 'Great',
      'message' => 'New customer was added!'
    ]);

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

    Customer::create($validated);

    session()->flash('notify', [
      'type' => 'success',
      'title' => 'Great',
      'message' => 'New customer was added!'
    ]);

    return redirect(route('auth.customer.index'));
  }
}
