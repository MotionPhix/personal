<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Logo extends Model implements HasMedia
{
  use HasFactory, InteractsWithMedia;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'brand',
  ];

  /**
   * The attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'created_at' => 'datetime',
      'updated_at' => 'datetime',
    ];
  }

  /**
   * Boot the model.
   */
  protected static function boot()
  {
    parent::boot();

    static::creating(function ($logo) {
      if (empty($logo->uuid)) {
        $logo->uuid = (string)Str::orderedUuid();
      }
    });
  }

  /**
   * Get the route key for the model.
   */
  public function getRouteKeyName(): string
  {
    return 'uuid';
  }

  /**
   * Register media collections.
   */
  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('logo_files')
      ->singleFile()
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp']);

    $this->addMediaCollection('preview')
      ->singleFile()
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
  }

  /**
   * Register media conversions.
   */
  public function registerMediaConversions(Media $media = null): void
  {
    $this->addMediaConversion('thumb')
      ->width(150)
      ->height(150)
      ->sharpen(10)
      ->performOnCollections('logo_files', 'preview');

    $this->addMediaConversion('medium')
      ->width(400)
      ->height(400)
      ->sharpen(10)
      ->performOnCollections('logo_files', 'preview');

    $this->addMediaConversion('large')
      ->width(800)
      ->height(600)
      ->sharpen(10)
      ->performOnCollections('logo_files', 'preview');
  }

  /**
   * Get the logo file URL.
   */
  public function getLogoUrlAttribute(): ?string
  {
    $logoFile = $this->getFirstMedia('logo_files');
    return $logoFile ? $logoFile->getUrl() : null;
  }

  /**
   * Get the logo preview URL.
   */
  public function getPreviewUrlAttribute(): ?string
  {
    $preview = $this->getFirstMedia('preview');
    if ($preview) {
      return $preview->getUrl('medium');
    }

    // Fallback to logo file if no preview
    $logoFile = $this->getFirstMedia('logo_files');
    return $logoFile ? $logoFile->getUrl('medium') : null;
  }

  /**
   * Get the logo thumbnail URL.
   */
  public function getThumbUrlAttribute(): ?string
  {
    $logoFile = $this->getFirstMedia('logo_files');
    return $logoFile ? $logoFile->getUrl('thumb') : null;
  }

  /**
   * Get the logo file type.
   */
  public function getFileTypeAttribute(): ?string
  {
    $logoFile = $this->getFirstMedia('logo_files');
    return $logoFile ? $logoFile->mime_type : null;
  }

  /**
   * Get the logo file size in bytes.
   */
  public function getFileSizeAttribute(): ?int
  {
    $logoFile = $this->getFirstMedia('logo_files');
    return $logoFile ? $logoFile->size : null;
  }

  /**
   * Get formatted file size.
   */
  public function getFormattedFileSizeAttribute(): ?string
  {
    if (!$this->file_size) {
      return null;
    }

    $bytes = $this->file_size;
    $units = ['B', 'KB', 'MB', 'GB'];

    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
      $bytes /= 1024;
    }

    return round($bytes, 2) . ' ' . $units[$i];
  }

  /**
   * Check if the logo is an SVG.
   */
  public function getIsSvgAttribute(): bool
  {
    return $this->file_type === 'image/svg+xml';
  }

  /**
   * Check if the logo is a raster image.
   */
  public function getIsRasterAttribute(): bool
  {
    return in_array($this->file_type, ['image/jpeg', 'image/png', 'image/webp']);
  }

  /**
   * Scope to search logos.
   */
  public function scopeSearch($query, $search)
  {
    return $query->where('brand', 'like', "%{$search}%");
  }

  /**
   * Scope to get recent logos.
   */
  public function scopeRecent($query, $days = 30)
  {
    return $query->where('created_at', '>=', now()->subDays($days));
  }

  /**
   * Scope to order by brand name.
   */
  public function scopeOrderByBrand($query, $direction = 'asc')
  {
    return $query->orderBy('brand', $direction);
  }
}
