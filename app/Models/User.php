<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
  use HasFactory, Notifiable, HasApiTokens, HasRoles, InteractsWithMedia;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'password',
    'phone_number',
    'bio',
    'website',
    'location',
    'timezone',
    'socials',
    'preferences',
    'last_login_at',
    'is_active',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'last_login_at' => 'datetime',
      'password' => 'hashed',
      'socials' => 'array',
      'preferences' => 'array',
      'is_active' => 'boolean',
    ];
  }

  /**
   * Boot the model.
   */
  protected static function boot()
  {
    parent::boot();

    static::created(function ($user) {
      // Assign admin role to first user
      if (static::count() === 1) {
        $user->assignRole('admin');
      }
    });
  }

  /**
   * Get the user's full name.
   */
  public function getFullNameAttribute(): string
  {
    return trim($this->first_name . ' ' . $this->last_name);
  }

  /**
   * Get the user's initials.
   */
  public function getInitialsAttribute(): string
  {
    return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
  }

  /**
   * Get the user's avatar URL.
   */
  public function getAvatarUrlAttribute(): ?string
  {
    $avatar = $this->getFirstMedia('avatars');
    return $avatar ? $avatar->getUrl('thumb') : null;
  }

  /**
   * Get the user's cover image URL.
   */
  public function getCoverImageUrlAttribute(): ?string
  {
    $cover = $this->getFirstMedia('covers');
    return $cover ? $cover->getUrl('large') : null;
  }

  /**
   * Check if user has a specific social platform.
   */
  public function hasSocial(string $platform): bool
  {
    return !empty($this->socials[$platform]);
  }

  /**
   * Get a specific social platform URL.
   */
  public function getSocial(string $platform): ?string
  {
    return $this->socials[$platform] ?? null;
  }

  /**
   * Check if user is online (logged in within last 15 minutes).
   */
  public function getIsOnlineAttribute(): bool
  {
    return $this->last_login_at && $this->last_login_at->gt(now()->subMinutes(15));
  }

  /**
   * Scope to get only active users.
   */
  public function scopeActive($query)
  {
    return $query->where('is_active', true);
  }

  /**
   * Scope to get users with a specific role.
   */
  public function scopeWithRole($query, string $role)
  {
    return $query->whereHas('roles', function ($q) use ($role) {
      $q->where('name', $role);
    });
  }

  /**
   * Register media collections.
   */
  public function registerMediaCollections(): void
  {
    $this->addMediaCollection('avatars')
      ->singleFile()
      ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

    $this->addMediaCollection('covers')
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
      ->performOnCollections('avatars');

    $this->addMediaConversion('medium')
      ->width(300)
      ->height(300)
      ->sharpen(10)
      ->performOnCollections('avatars');

    $this->addMediaConversion('large')
      ->width(1200)
      ->height(400)
      ->sharpen(10)
      ->performOnCollections('covers');
  }

  /**
   * Update last login timestamp.
   */
  public function updateLastLogin(): void
  {
    $this->update(['last_login_at' => now()]);
  }

  /**
   * Get user's preferred timezone.
   */
  public function getTimezoneAttribute($value): string
  {
    return $value ?: config('app.timezone', 'UTC');
  }

  /**
   * Get user's preferences with defaults.
   */
  public function getPreferencesAttribute($value): array
  {
    $defaults = [
      'theme' => 'light',
      'language' => 'en',
      'notifications' => [
        'email' => true,
        'browser' => true,
        'mobile' => true,
      ],
      'dashboard' => [
        'show_stats' => true,
        'show_recent_activity' => true,
        'items_per_page' => 15,
      ],
    ];

    return array_merge($defaults, json_decode($value, true) ?: []);
  }
}
