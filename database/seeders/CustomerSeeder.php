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
    // Customer::factory(5)->create();

    // Customer::factory(5)
    //   ->hasProjects(rand(2, 5))
    //   ->create();

    Customer::factory(5)
      ->create()
      ->each(function ($customer) {
        // For each customer, create 3 to 5 projects
        $customer->projects()->createMany(
          Project::factory(rand(3, 5))->make()->toArray()
        )->each(function ($project) {
          // For each project, create 3 to 5 photos
          Photo::factory(rand(2, 4))
            ->create([
              'model_id' => $project->id,
              'model_type' => Project::class,  // Polymorphic type
            ]);
        });
      });
  }
}
