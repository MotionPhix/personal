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
    $user = User::where('email', 'support@ultrashots.net')->first();

    $projects = Project::inRandomOrder()->take(6)->select('id', 'pid', 'poster')->get();;

    return Inertia::render('Index', [
      'user' => $user,
      'projects' => $projects,
    ]);
  }
}
