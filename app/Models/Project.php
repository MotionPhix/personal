<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;

class Project extends Model implements HasMedia
{
  use HasFactory;
  use InteractsWithMedia;

  protected $fillable = [
    'name',
    'description',
    'production',
    'customer_id',
  ];

  protected function casts(): array
  {
    return [
      'description' => PurifyHtmlOnGet::class,
      'name' => PurifyHtmlOnGet::class,
      'production' => 'datetime',
    ];
  }

  protected function production(): Attribute
  {
    return Attribute::make(
      get: fn($value) => \Carbon\Carbon::parse($value)->format('F, Y'),
    );
  }

  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
  }

  /*public function images()
  {
    return $this->morphMany(Photo::class, 'model');
  }*/

  // Define media conversions (thumbnail generation)
  public function registerMediaConversions(Media $media = null): void
  {
    // Generate thumbnail conversion for images
    $this->addMediaConversion('thumb')
      ->width(150) // Width for the thumbnail
      ->height(150) // Height for the thumbnail
      ->sharpen(10) // Optional: Sharpen the thumbnail
      ->nonQueued(); // This ensures it's processed immediately (optional)
  }

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($project) {
      $project->pid = Str::orderedUuid();
    });

    static::updating(function ($project) {
      if (!isset($project->pid)) {
        $project->pid = Str::orderedUuid();
      }
    });
  }
}
