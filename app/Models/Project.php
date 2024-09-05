<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;

class Project extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'description',
    'poster',
  ];

  protected function casts(): array
  {
    return [
      'description' => PurifyHtmlOnGet::class,
      'name' => PurifyHtmlOnGet::class,
    ];
  }

  public function customer(): BelongsTo
  {
    return $this->belongsTo(Customer::class);
  }

  public function images()
  {
    return $this->morphMany(Photo::class, 'model');
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
