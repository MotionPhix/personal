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
      'email' => 'hello@ultrashots.net',
      'phone_number' => '+265 888 166 911',
      'password' => 'run%$Ace5',
      'socials' => [
        'twitter' => 'ultrashoots',
        'linkedin' => 'ultrashots',
        'facebook' => 'ultrashotz',
        'behance' => 'ultrashots',
      ]
    ]);
  }
}
