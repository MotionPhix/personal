<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return $user->email === 'hello@ultrashots.net';
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Project $project): bool
  {
    return $project->user->id === $user->id;
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->role === 'admin';
  }

  /**
   * Determine whether the user can modify the model.
   */
  public function modify(User $user, Project $project): bool
  {
    return $user->id === $project->user_id || $user->role === 'admin';
  }

}
