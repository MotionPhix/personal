<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomeController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke()
  {
    $user = User::where('email', 'support@ultrashots.net')->first();

    $canLogin = Route::has('login');
    $canRegister = Route::has('register');

    return Inertia::render('Index', [
      'user' => $user,
      'canLogin' => $canLogin,
      'canRegister' => $canRegister,
    ]);
  }
}
