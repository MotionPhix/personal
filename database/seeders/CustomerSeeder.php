<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Photo;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Customer::factory(2)
      ->create()
      ->each(function ($customer) {
        $customer->projects()->createMany(
          Project::factory(rand(1, 3))->make()->toArray()
        )->each(function ($project) {
          Photo::factory(rand(1, 2))
            ->create([
              'model_id' => $project->id,
              'model_type' => Project::class,
            ]);
        });
      });
  }
}
