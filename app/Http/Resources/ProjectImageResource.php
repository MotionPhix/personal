<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectImageResource extends JsonResource
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
      'project_id' => $this->project_id,
      'src' => $this->src,
      'alt' => $this->alt,
      'title' => $this->title,
      'description' => $this->description,
      'sort_order' => $this->sort_order,
      'is_featured' => $this->is_featured,
      'created_at' => $this->created_at?->toISOString(),
      'updated_at' => $this->updated_at?->toISOString(),

      // Conditionally include relationships
      'project' => new ProjectResource($this->whenLoaded('project')),
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
        'resource_type' => 'project_image',
        'version' => '1.0',
      ],
    ];
  }
}
