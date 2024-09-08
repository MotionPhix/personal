<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
    'production',
    'customer_id',
    'poster',
  ];

  protected function casts(): array
  {
    return [
      'description' => PurifyHtmlOnGet::class,
      'poster' => PurifyHtmlOnGet::class,
      'name' => PurifyHtmlOnGet::class,
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
