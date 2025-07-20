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
    $companyTypes = [
      'Tech', 'Solutions', 'Digital', 'Systems', 'Software', 'Apps',
      'Creative', 'Studio', 'Design', 'Media', 'Agency', 'Group',
      'Consulting', 'Services', 'Enterprise', 'Innovations', 'Labs'
    ];

    $companyName = $this->faker->company();
    $brandName = $companyName . ' ' . $this->faker->randomElement($companyTypes);

    return [
      'brand' => $brandName,
      'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
      'updated_at' => function (array $attributes) {
        return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
      },
    ];
  }

  /**
   * Create a PNG logo (will be handled via media library).
   */
  public function png(): static
  {
    return $this->state(fn(array $attributes) => [
      // PNG-specific branding
      'brand' => $attributes['brand'] . ' (PNG)',
    ]);
  }

  /**
   * Create an SVG logo (will be handled via media library).
   */
  public function svg(): static
  {
    return $this->state(fn(array $attributes) => [
      // SVG-specific branding
      'brand' => $attributes['brand'] . ' (SVG)',
    ]);
  }

  /**
   * Create a tech company logo.
   */
  public function techCompany(): static
  {
    return $this->state(fn(array $attributes) => [
      'brand' => $this->faker->company() . ' ' . $this->faker->randomElement([
          'Tech', 'Software', 'Systems', 'Digital', 'Apps', 'Labs'
        ]),
    ]);
  }

  /**
   * Create a creative agency logo.
   */
  public function creativeAgency(): static
  {
    return $this->state(fn(array $attributes) => [
      'brand' => $this->faker->company() . ' ' . $this->faker->randomElement([
          'Studio', 'Creative', 'Design', 'Media', 'Agency', 'Collective'
        ]),
    ]);
  }

  /**
   * Create a recent logo.
   */
  public function recent(): static
  {
    return $this->state(fn(array $attributes) => [
      'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
      'updated_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
    ]);
  }
}
