<?php

namespace Database\Seeders;

use App\Models\Customer;
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

          for ($i = 0; $i < rand(2, 7); $i++) {
            $faker = \Faker\Factory::create();
            $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));

            // Randomly choose image size
            $sizes = [
              [640, 480],
              [800, 600],
              [1024, 768],
              [480, 640],
              [600, 800],
              [768, 1024],
              [1280, 720],
              [720, 1280]
            ];

            [$width, $height] = $faker->randomElement($sizes);

            // Generate image and add to Spatie Media Library
            $imagePath = $faker->image('public/tmp', $width, $height);
            $project->addMedia($imagePath)->toMediaCollection('bucket');
          }

        });

      });

  }

}
