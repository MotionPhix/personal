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

    $template = 'Admin/Customers/Form';

    if ($request->query->get('modal')) {

      $template = 'Admin/Customers/FormModal';

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

    if($request->query->get('modal')) return redirect(route('auth.projects.create', $customer->cid));

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
