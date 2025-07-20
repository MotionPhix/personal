<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Download extends Model implements HasMedia
{
  use HasFactory, SoftDeletes, InteractsWithMedia;

  protected $fillable = [
    'uuid',
    'title',
    'description',
    'brand',
    'category',
    'file_type',
    'file_size',
    'download_count',
    'is_featured',
    'is_public',
    'sort_order',
    'meta_title',
    'meta_description',
    'tags',
  ];

  protected $casts = [
    'is_featured' => 'boolean',
    'is_public' => 'boolean',
    'download_count' => 'integer',
    'file_size' => 'integer',
    'sort_order' => 'integer',
    'tags' => 'array',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
  ];

  protected $appends = [
    'poster_url',
    'download_url',
    'formatted_file_size',
    'file_extension',
    'thumb_url',
    'medium_url',
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($download) {
      if (empty($download->uuid)) {
        $download->uuid = (string) Str::orderedUuid();
      }
    });
  }

  // Route key binding
  public function getRouteKeyName(): string
  {
    return 'uuid';
  }

  // Media Collections
  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('poster')
      ->singleFile()
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

    $this->addMediaCollection('file')
      ->singleFile();
  }

  public function registerMediaConversions(Media $media = null): void
  {
    $this->addMediaConversion('thumb')
      ->width(300)
      ->height(300)
      ->sharpen(10)
      ->performOnCollections('poster');

    $this->addMediaConversion('medium')
      ->width(600)
      ->height(600)
      ->sharpen(10)
      ->performOnCollections('poster');
  }

  // Accessors
  public function getPosterUrlAttribute(): ?string
  {
    $media = $this->getFirstMedia('poster');
    return $media ? $media->getUrl() : null;
  }

  public function getThumbUrlAttribute(): ?string
  {
    $media = $this->getFirstMedia('poster');
    return $media ? $media->getUrl('thumb') : null;
  }

  public function getMediumUrlAttribute(): ?string
  {
    $media = $this->getFirstMedia('poster');
    return $media ? $media->getUrl('medium') : null;
  }

  public function getDownloadUrlAttribute(): ?string
  {
    $media = $this->getFirstMedia('file');
    return $media ? $media->getUrl() : null;
  }

  public function getFormattedFileSizeAttribute(): string
  {
    if (!$this->file_size) {
      return 'Unknown';
    }

    $bytes = $this->file_size;
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
      $bytes /= 1024;
    }

    return round($bytes, 2) . ' ' . $units[$i];
  }

  public function getFileExtensionAttribute(): ?string
  {
    $media = $this->getFirstMedia('file');
    return $media ? strtoupper(pathinfo($media->file_name, PATHINFO_EXTENSION)) : null;
  }

  // Scopes
  public function scopePublic(Builder $query): Builder
  {
    return $query->where('is_public', true);
  }

  public function scopeFeatured(Builder $query): Builder
  {
    return $query->where('is_featured', true);
  }

  public function scopeByCategory(Builder $query, string $category): Builder
  {
    return $query->where('category', $category);
  }

  public function scopeByFileType(Builder $query, string $fileType): Builder
  {
    return $query->where('file_type', $fileType);
  }

  public function scopeSearch(Builder $query, string $search): Builder
  {
    return $query->where(function ($q) use ($search) {
      $q->where('title', 'like', "%{$search}%")
        ->orWhere('description', 'like', "%{$search}%")
        ->orWhere('brand', 'like', "%{$search}%")
        ->orWhere('category', 'like', "%{$search}%");
    });
  }

  public function scopeOrdered(Builder $query): Builder
  {
    return $query->orderBy('sort_order', 'asc')
      ->orderBy('created_at', 'desc');
  }

  public function scopePopular(Builder $query): Builder
  {
    return $query->orderBy('download_count', 'desc');
  }

  public function scopeRecent(Builder $query): Builder
  {
    return $query->orderBy('created_at', 'desc');
  }

  // Methods
  public function incrementDownloadCount(): void
  {
    $this->increment('download_count');
  }

  public function getCategories(): array
  {
    return self::distinct()
      ->whereNotNull('category')
      ->pluck('category')
      ->sort()
      ->values()
      ->toArray();
  }

  public function getFileTypes(): array
  {
    return self::distinct()
      ->whereNotNull('file_type')
      ->pluck('file_type')
      ->sort()
      ->values()
      ->toArray();
  }

  public function getBrands(): array
  {
    return self::distinct()
      ->whereNotNull('brand')
      ->pluck('brand')
      ->sort()
      ->values()
      ->toArray();
  }

  // Static methods
  public static function getStats(): array
  {
    return [
      'total_downloads' => self::count(),
      'public_downloads' => self::public()->count(),
      'featured_downloads' => self::featured()->count(),
      'total_download_count' => self::sum('download_count'),
      'categories_count' => self::distinct()->whereNotNull('category')->count('category'),
      'file_types_count' => self::distinct()->whereNotNull('file_type')->count('file_type'),
      'brands_count' => self::distinct()->whereNotNull('brand')->count('brand'),
      'downloads_by_category' => self::selectRaw('category, COUNT(*) as count')
        ->whereNotNull('category')
        ->groupBy('category')
        ->pluck('count', 'category'),
      'downloads_by_file_type' => self::selectRaw('file_type, COUNT(*) as count')
        ->whereNotNull('file_type')
        ->groupBy('file_type')
        ->pluck('count', 'file_type'),
      'most_downloaded' => self::orderBy('download_count', 'desc')
        ->limit(5)
        ->get(['uuid', 'title', 'download_count']),
      'recent_downloads' => self::orderBy('created_at', 'desc')
        ->limit(5)
        ->get(['uuid', 'title', 'created_at']),
    ];
  }
}
