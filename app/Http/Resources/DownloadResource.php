<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DownloadResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   */
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'uuid' => $this->uuid,
      'title' => $this->title,
      'description' => $this->description,
      'brand' => $this->brand,
      'category' => $this->category,
      'file_type' => $this->file_type,
      'file_size' => $this->file_size,
      'formatted_file_size' => $this->formatted_file_size,
      'file_extension' => $this->file_extension,
      'download_count' => $this->download_count,
      'is_featured' => $this->is_featured,
      'is_public' => $this->is_public,
      'sort_order' => $this->sort_order,
      'meta_title' => $this->meta_title,
      'meta_description' => $this->meta_description,
      'tags' => $this->tags ?? [],

      // URLs
      'poster_url' => $this->poster_url,
      'thumb_url' => $this->thumb_url,
      'medium_url' => $this->medium_url,
      'download_url' => $this->download_url,
      'public_url' => url("/downloads/{$this->uuid}"),

      // Timestamps
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at,
      'created_at_human' => $this->created_at->diffForHumans(),
      'updated_at_human' => $this->updated_at->diffForHumans(),
      'created_at_formatted' => $this->created_at->format('M j, Y'),
      'updated_at_formatted' => $this->updated_at->format('M j, Y'),

      // Media information
      'has_poster' => !is_null($this->poster_url),
      'has_file' => !is_null($this->download_url),
      'poster_media' => $this->getFirstMedia('poster'),
      'file_media' => $this->getFirstMedia('file'),

      // Computed properties
      'status' => $this->is_public ? 'public' : 'private',
      'status_color' => $this->is_public ? 'success' : 'secondary',
      'file_type_color' => $this->getFileTypeColor(),
      'completion_percentage' => $this->getCompletionPercentage(),

      // Include full media collection when requested
      'media' => $this->whenLoaded('media'),
    ];
  }

  /**
   * Get file type color for UI.
   */
  private function getFileTypeColor(): string
  {
    $colors = [
      'pdf' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
      'doc' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
      'docx' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
      'xls' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
      'xlsx' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
      'ppt' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
      'pptx' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
      'zip' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
      'rar' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
      'jpg' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
      'jpeg' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
      'png' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
      'gif' => 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200',
      'mp4' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
      'mp3' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    ];

    return $colors[strtolower($this->file_type ?? '')] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
  }

  /**
   * Calculate completion percentage for progress indicators.
   */
  private function getCompletionPercentage(): int
  {
    $completed = 0;
    $total = 6;

    if ($this->title) $completed++;
    if ($this->description) $completed++;
    if ($this->brand) $completed++;
    if ($this->category) $completed++;
    if ($this->poster_url) $completed++;
    if ($this->download_url) $completed++;

    return round(($completed / $total) * 100);
  }

  /**
   * Get the array of fields that should be included in the resource.
   */
  public function fields(): array
  {
    return [
      'id',
      'uuid',
      'title',
      'description',
      'brand',
      'category',
      'file_type',
      'file_size',
      'formatted_file_size',
      'file_extension',
      'download_count',
      'is_featured',
      'is_public',
      'sort_order',
      'meta_title',
      'meta_description',
      'tags',

      // URLs
      'poster_url',
      'thumb_url',
      'medium_url',
      'download_url',
      'public_url',

      // Timestamps
      'created_at',
      'updated_at',
    ];
  }
}
