<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'first_name' => fake('ZA')->firstName(),
      'last_name' => fake('ZA')->lastName(),
      'job_title' => fake()->jobTitle(),
      'company_name' => fake('ZA')->company(),
      'address' => [
        'street' => fake('ZA')->streetAddress(),
        'city' => fake('MW')->city(),
        'state' => fake('MW')->state(),
        'country' => fake()->country(),
      ],
    ];
  }
}
