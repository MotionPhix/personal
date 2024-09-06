<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Logo extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'poster',
      'file_path',
    ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($logo) {
      $logo->did = Str::orderedUuid();
    });

    static::updating(function ($logo) {
      if (!isset($logo->did)) {
        $logo->did = Str::orderedUuid();
      }
    });
  }
}
