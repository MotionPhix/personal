<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Customer extends Model
{
  use HasFactory;

  protected $fillable = [
    'first_name',
    'last_name',
    'job_title',
    'company_name',
    'address',
  ];

  protected $casts = [
    'address' => 'array',
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($contact) {
      $contact->cid = Str::orderedUuid();
    });

    static::updating(function ($contact) {
      if (!isset($contact->cid)) {
        $contact->cid = Str::orderedUuid();
      }
    });

    static::forceDeleting(function ($contact) {
      $contact->load('phones', 'emails', 'tags');

      $contact->phones()->delete();

      $contact->emails()->delete();
    });
  }
}
