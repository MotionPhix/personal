<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \Sushi\Sushi;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

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
    'email_verified_at',
    'phone_number',
    'socials'
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
      'password' => 'hashed',
      'socials' => 'array',
    ];
  }

  /**
   * Define the rows to be used by Sushi.
   *
   * @return array<int, array<string, mixed>>
   */
  /*protected function getRows()
  {
    return [
      [
        'first_name' => 'Kingsley',
        'last_name' => 'Nyirenda',
        'email' => 'support@ultrashots.net',
        'phone_number' => '+265 996 727 163',
        'socials' =>  json_encode([
          'twitter' => '@ultrashoots',
          'facebook' => 'ultrashotz',
          'behance' => '@ultrashots',
        ]),
      ],
    ];
  }*/
}
