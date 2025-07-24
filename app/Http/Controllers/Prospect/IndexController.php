<?php

namespace App\Http\Controllers\Prospect;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IndexController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    return Inertia::render('ask/Hello', [
      'user' => fn() => User::select(['phone_number', 'email'])->first()
    ]);
  }
}
