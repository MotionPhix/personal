<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
  /**
 * Define the model's default state.
 *
 * @return array<string, mixed>
 */
  public function definition(): array
  {
    $faker = \Faker\Factory::create();
    $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));

    return [
      'poster' => $faker->imageUrl(640, 480),
      'name' => fake()->sentence(3),
      'description' => fake()->paragraph(6),
    ];
  }
}
