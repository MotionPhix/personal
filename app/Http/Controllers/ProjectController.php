<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
  /**
   * Display the all available projects.
   */
  public function index(): Response
  {
    $projects = Project::all();

    return Inertia::render('Projects/Index', [
      'projects' => $projects,
    ]);
  }

  /**
   * Display the selected project.
   */
  public function show(Project $project): Response
  {
    return Inertia::render('Projects/Show', [
      'project' => $project->load('customer', 'images'),
    ]);
  }
}
