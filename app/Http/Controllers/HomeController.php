<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Inertia\Inertia;

class HomeController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke()
  {
    return Inertia::render('Index', [
      'user' => fn() => User::where('email', 'hello@ultrashots.net')->first(),
      'projects' => fn() => Project::with('media')
        ->inRandomOrder()
        ->take(6)->select('id', 'pid')
        ->get(),
    ]);
  }
}
