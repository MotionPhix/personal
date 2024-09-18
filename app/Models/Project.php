<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
// use Spatie\MediaLibrary\MediaCollections\Models\Media;
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
      /*set: function($value) {
        // Trim the input to remove any extra spaces
        $value = trim($value);

        // Check if the value is empty
        if (empty($value)) {
            return null; // or handle as needed
        }

        // Attempt to create a date from the format
        try {
            return \Carbon\Carbon::createFromFormat('F, Y', $value)->toDateString();
        } catch (\Exception $e) {
            // Handle the error, log it, or throw a custom exception
            throw new \InvalidArgumentException('Invalid date format provided: ' . $value);
        }
    }*/
    );
  }

  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
  }

  /*// Define media conversions (thumbnail generation)
  public function registerMediaConversions(Media $media = null): void
  {
    // Generate thumbnail conversion for images
    $this->addMediaConversion('thumb')
      ->width(150) // Width for the thumbnail
      ->height(150) // Height for the thumbnail
      ->sharpen(10) // Optional: Sharpen the thumbnail
      ->nonQueued(); // This ensures it's processed immediately (optional)
  }*/

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
