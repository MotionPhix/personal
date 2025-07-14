<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Customer::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $firstName = fake()->firstName();
    $lastName = fake()->lastName();
    $companyName = fake()->boolean(70) ? fake()->company() : null;

    return [
      'cid' => (string) Str::uuid(),
      'first_name' => $firstName,
      'last_name' => $lastName,
      'job_title' => fake()->jobTitle(),
      'company_name' => $companyName,
      'email' => fake()->unique()->safeEmail(),
      'phone_number' => fake()->phoneNumber(),
      'website' => fake()->boolean(60) ? fake()->url() : null,
      'address' => [
        'street' => fake()->streetAddress(),
        'city' => fake()->city(),
        'state' => fake()->state(),
        'postal_code' => fake()->postcode(),
        'country' => fake()->country(),
      ],
      'notes' => fake()->boolean(40) ? fake()->paragraph() : null,
      'status' => fake()->randomElement(['active', 'inactive', 'prospect']),
      'avatar_url' => fake()->boolean(30) ? fake()->imageUrl(200, 200, 'people') : null,
    ];
  }

  /**
   * Indicate that the customer is active.
   */
  public function active(): static
  {
    return $this->state(fn (array $attributes) => [
      'status' => 'active',
    ]);
  }

  /**
   * Indicate that the customer is inactive.
   */
  public function inactive(): static
  {
    return $this->state(fn (array $attributes) => [
      'status' => 'inactive',
    ]);
  }

  /**
   * Indicate that the customer is a prospect.
   */
  public function prospect(): static
  {
    return $this->state(fn (array $attributes) => [
      'status' => 'prospect',
    ]);
  }

  /**
   * Indicate that the customer has a company.
   */
  public function withCompany(): static
  {
    return $this->state(fn (array $attributes) => [
      'company_name' => fake()->company(),
      'website' => fake()->url(),
    ]);
  }

  /**
   * Indicate that the customer is an individual (no company).
   */
  public function individual(): static
  {
    return $this->state(fn (array $attributes) => [
      'company_name' => null,
      'website' => null,
      'job_title' => fake()->randomElement([
        'Freelancer',
        'Consultant',
        'Entrepreneur',
        'Creative Director',
        'Independent Artist'
      ]),
    ]);
  }

  /**
   * Create a customer with specific industry focus.
   */
  public function techIndustry(): static
  {
    return $this->state(fn (array $attributes) => [
      'company_name' => fake()->company() . ' ' . fake()->randomElement(['Tech', 'Solutions', 'Systems', 'Digital']),
      'job_title' => fake()->randomElement([
        'CTO',
        'Software Engineer',
        'Product Manager',
        'UX Designer',
        'DevOps Engineer',
        'Data Scientist'
      ]),
      'website' => fake()->url(),
    ]);
  }

  /**
   * Create a customer from creative industry.
   */
  public function creativeIndustry(): static
  {
    return $this->state(fn (array $attributes) => [
      'company_name' => fake()->boolean(80) ?
        fake()->company() . ' ' . fake()->randomElement(['Studio', 'Creative', 'Design', 'Media']) :
        null,
      'job_title' => fake()->randomElement([
        'Creative Director',
        'Art Director',
        'Graphic Designer',
        'Brand Manager',
        'Marketing Director',
        'Content Creator'
      ]),
    ]);
  }
}
