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

    // Define multiple sizes (e.g., 640x480, 800x600, 1024x768)
    // Define both landscape and portrait sizes
    $sizes = [
      [640, 480],  // Landscape
      [800, 600],  // Landscape
      [1024, 768], // Landscape
      [480, 640],  // Portrait
      [600, 800],  // Portrait
      [768, 1024], // Portrait
      [1280, 720], // Landscape
      [720, 1280], // Portrait
    ];

    // Randomly select a size for each generated image
    [$width, $height] = fake()->randomElement($sizes);

    $imagePath = $faker->image('public/bucket/images', $width, $height); // Image generation

    $relativePath = '/bucket/images/' . basename($imagePath);

    return [
      'src' => $relativePath, // File path for the generated image
      'mime_type' => 'image/jpeg', // MIME type, you can change based on file type
      'size' => "{$width}x{$height}",
      'model_id' => null, // To be filled when associated with a model
      'model_type' => null,
    ];
  }
}
