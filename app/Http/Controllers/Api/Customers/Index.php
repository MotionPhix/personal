<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class Index extends Controller
{
  public function __invoke()
  {
    $customers = Customer::all();

    return response()->json([
      'contacts' => $customers
    ]);
  }
}
