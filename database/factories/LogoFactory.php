<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Logo>
 */
class LogoFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    /*$faker = \Faker\Factory::create();
      $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));

      $imagePath = $faker->image('public/logos', 480, 480); // Image generation

      $relativePath = '/logos/' . basename($imagePath);*/

    return [
      'brand' => fake('MW')->company . ' Logo',
    ];
  }
}
