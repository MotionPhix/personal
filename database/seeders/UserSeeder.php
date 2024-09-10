<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    User::factory(1)->create([
      'first_name' => 'Kingsley',
      'last_name' => 'Nyirenda',
      'email' => 'support@ultrashots.net',
      'phone_number' => '+265 996 727 163',
      'password' => 'run%$Ace5',
      'socials' => [
        'twitter' => '@ultrashoots',
        'facebook' => 'ultrashotz',
        'behance' => '@ultrashots',
      ]
    ]);
  }
}
