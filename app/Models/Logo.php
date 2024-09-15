<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Logo extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
      'brand',
    ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($logo) {
      $logo->lid = Str::orderedUuid();
    });

    static::updating(function ($logo) {
      if (!isset($logo->lid)) {
        $logo->lid = Str::orderedUuid();
      }
    });
  }
}
