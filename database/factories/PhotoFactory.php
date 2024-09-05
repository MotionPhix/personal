<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class PhotoFactory extends Factory
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

    $imagePath = $faker->image('public/photos', 640, 480); // Image generation

    $relativePath = '/photos/' . basename($imagePath);

    return [
      'src' => $relativePath, // File path for the generated image
      'mime_type' => 'image/jpeg', // MIME type, you can change based on file type
      'model_id' => null, // To be filled when associated with a model
      'model_type' => null,
    ];
  }
}
