<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'cid' => $this->cid,
      'first_name' => $this->first_name,
      'last_name' => $this->last_name,
      'full_name' => $this->full_name,
      'display_name' => $this->display_name,
      'initials' => $this->initials,
      'job_title' => $this->job_title,
      'company_name' => $this->company_name,
      'email' => $this->email,
      'phone_number' => $this->phone_number,
      'website' => $this->website,
      'address' => $this->address,
      'formatted_address' => $this->formatted_address,
      'notes' => $this->notes,
      'status' => $this->status,
      'avatar_url' => $this->avatar_url,
      'total_projects' => $this->total_projects,
      'created_at' => $this->created_at?->toISOString(),
      'updated_at' => $this->updated_at?->toISOString(),

      // Conditionally include relationships
      'projects' => ProjectResource::collection($this->whenLoaded('projects')),
      'active_projects' => ProjectResource::collection($this->whenLoaded('activeProjects')),
      'completed_projects' => ProjectResource::collection($this->whenLoaded('completedProjects')),

      // Include project counts when available
      'projects_count' => $this->when(
        $this->relationLoaded('projects'),
        fn() => $this->projects->count()
      ),
      'active_projects_count' => $this->when(
        $this->relationLoaded('activeProjects'),
        fn() => $this->activeProjects->count()
      ),
      'completed_projects_count' => $this->when(
        $this->relationLoaded('completedProjects'),
        fn() => $this->completedProjects->count()
      ),
    ];
  }

  /**
   * Get additional data that should be returned with the resource array.
   *
   * @return array<string, mixed>
   */
  public function with(Request $request): array
  {
    return [
      'meta' => [
        'resource_type' => 'customer',
        'version' => '1.0',
      ],
    ];
  }
}
