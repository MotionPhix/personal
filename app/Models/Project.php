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

  public function completionDate(): Attribute
  {
    return new Attribute(
      get: fn() => $this->production->format('F, Y')
    );
  }

  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
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
