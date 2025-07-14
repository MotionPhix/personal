<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Customer extends Model
{
  use HasFactory, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'uuid',
    'first_name',
    'last_name',
    'job_title',
    'company_name',
    'email',
    'phone_number',
    'website',
    'address',
    'notes',
    'status',
  ];

  /**
   * The attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'address' => 'array',
      'created_at' => 'datetime',
      'updated_at' => 'datetime',
      'deleted_at' => 'datetime',
    ];
  }

  /**
   * Boot the model.
   */
  protected static function boot()
  {
    parent::boot();

    static::creating(function ($customer) {
      if (empty($customer->uuid)) {
        $customer->uuid = (string) Str::orderedUuid();
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
   * Get the customer's full name.
   */
  public function getFullNameAttribute(): string
  {
    return "{$this->first_name} {$this->last_name}";
  }

  /**
   * Get the customer's display name (company or full name).
   */
  public function getDisplayNameAttribute(): string
  {
    return $this->company_name ?: $this->full_name;
  }

  /**
   * Get the customer's initials.
   */
  public function getInitialsAttribute(): string
  {
    return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
  }

  /**
   * Get all projects for this customer.
   */
  public function projects(): HasMany
  {
    return $this->hasMany(Project::class);
  }

  /**
   * Get active projects for this customer.
   */
  public function activeProjects(): HasMany
  {
    return $this->projects()->where('status', 'active');
  }

  /**
   * Get completed projects for this customer.
   */
  public function completedProjects(): HasMany
  {
    return $this->projects()->where('status', 'completed');
  }

  /**
   * Scope a query to only include active customers.
   */
  public function scopeActive($query)
  {
    return $query->where('status', 'active');
  }

  /**
   * Scope a query to search customers.
   */
  public function scopeSearch($query, $search)
  {
    return $query->where(function ($q) use ($search) {
      $q->where('first_name', 'like', "%{$search}%")
        ->orWhere('last_name', 'like', "%{$search}%")
        ->orWhere('company_name', 'like', "%{$search}%")
        ->orWhere('email', 'like', "%{$search}%");
    });
  }

  /**
   * Get the total number of projects for this customer.
   */
  public function getTotalProjectsAttribute(): int
  {
    return $this->projects()->count();
  }

  /**
   * Get the formatted address.
   */
  public function getFormattedAddressAttribute(): ?string
  {
    if (!$this->address) {
      return null;
    }

    $parts = [];

    if (!empty($this->address['street'])) {
      $parts[] = $this->address['street'];
    }

    if (!empty($this->address['city'])) {
      $parts[] = $this->address['city'];
    }

    if (!empty($this->address['state'])) {
      $parts[] = $this->address['state'];
    }

    if (!empty($this->address['postal_code'])) {
      $parts[] = $this->address['postal_code'];
    }

    if (!empty($this->address['country'])) {
      $parts[] = $this->address['country'];
    }

    return implode(', ', $parts);
  }
}
