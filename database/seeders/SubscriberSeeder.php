<?php

namespace Database\Seeders;

use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriberSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $subscribersData = [];
    for ($i = 0; $i < 30; $i++) {
      $date = Carbon::now()->subDays(rand(5, 20))->toDateString();

      $subscribersData[] = [
        'email' => fake()->unique()->email(),
        'token' => null,
        'subscribed' => fake()->randomElement([0, 1, 1, 1, 0]),
        'created_at' => $date,
        'updated_at' => $date,
      ];
    }

    // Insert the data into the subscribers table
    Subscriber::insert($subscribersData);
  }
}
