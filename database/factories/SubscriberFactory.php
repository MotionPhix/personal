<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscriber>
 */
class SubscriberFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $subscribed = $this->faker->boolean(85); // 85% are subscribed
    $subscribedAt = $subscribed ? $this->faker->dateTimeBetween('-2 years', 'now') : null;
    $unsubscribedAt = !$subscribed && $this->faker->boolean(60) ?
      $this->faker->dateTimeBetween($subscribedAt ?? '-1 year', 'now') : null;

    // Sometimes include name (60% chance)
    $hasName = $this->faker->boolean(60);
    $firstName = $hasName ? $this->faker->firstName() : null;
    $lastName = $hasName ? $this->faker->lastName() : null;

    return [
      'email' => $this->faker->unique()->safeEmail(),
      'first_name' => $firstName,
      'last_name' => $lastName,
      'subscribed' => $subscribed,
      'subscribed_at' => $subscribedAt,
      'unsubscribed_at' => $unsubscribedAt,
      'source' => $this->faker->randomElement([
        'website_footer',
        'contact_form',
        'newsletter_popup',
        'social_media',
        'referral',
        'manual_import',
        'api'
      ]),
      'ip_address' => $this->faker->ipv4(),
      'user_agent' => $this->faker->userAgent(),
      'token' => $this->faker->boolean(20) ? Str::random(32) : null, // Legacy token field
      'verification_token' => $subscribed ? null : Str::random(32),
      'verified_at' => $subscribed ? $subscribedAt : null,
      'preferences' => [
        'frequency' => $this->faker->randomElement(['daily', 'weekly', 'monthly']),
        'topics' => $this->faker->randomElements([
          'web_development',
          'design_tips',
          'project_updates',
          'industry_news',
          'tutorials',
          'case_studies'
        ], $this->faker->numberBetween(1, 4)),
        'format' => $this->faker->randomElement(['html', 'text']),
      ],
      'status' => $this->determineStatus($subscribed, $unsubscribedAt),
      'created_at' => $subscribedAt ?? $this->faker->dateTimeBetween('-2 years', 'now'),
      'updated_at' => function (array $attributes) {
        return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
      },
    ];
  }

  /**
   * Determine subscriber status based on subscription state.
   */
  private function determineStatus(bool $subscribed, $unsubscribedAt): string
  {
    if ($subscribed) {
      return 'active';
    }

    if ($unsubscribedAt) {
      return 'unsubscribed';
    }

    // Small chance of bounced or complained status
    return $this->faker->randomElement(['pending', 'pending', 'pending', 'bounced', 'complained']);
  }

  /**
   * Indicate that the subscriber is active.
   */
  public function subscribed(): static
  {
    return $this->state(fn(array $attributes) => [
      'subscribed' => true,
      'subscribed_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
      'unsubscribed_at' => null,
      'verification_token' => null,
      'verified_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
    ]);
  }

  /**
   * Indicate that the subscriber has unsubscribed.
   */
  public function unsubscribed(): static
  {
    $subscribedAt = $this->faker->dateTimeBetween('-2 years', '-1 month');

    return $this->state(fn(array $attributes) => [
      'subscribed' => false,
      'subscribed_at' => $subscribedAt,
      'unsubscribed_at' => $this->faker->dateTimeBetween($subscribedAt, 'now'),
      'verified_at' => $subscribedAt,
    ]);
  }

  /**
   * Indicate that the subscriber is pending verification.
   */
  public function pending(): static
  {
    return $this->state(fn(array $attributes) => [
      'subscribed' => false,
      'subscribed_at' => null,
      'unsubscribed_at' => null,
      'verification_token' => Str::random(32),
      'verified_at' => null,
    ]);
  }

  /**
   * Create a recent subscriber.
   */
  public function recent(): static
  {
    return $this->state(fn(array $attributes) => [
      'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
      'subscribed_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
      'subscribed' => true,
    ]);
  }

  /**
   * Create a subscriber from website footer.
   */
  public function fromWebsite(): static
  {
    return $this->state(fn(array $attributes) => [
      'source' => 'website_footer',
      'subscribed' => true,
    ]);
  }

  /**
   * Create a subscriber from social media.
   */
  public function fromSocialMedia(): static
  {
    return $this->state(fn(array $attributes) => [
      'source' => 'social_media',
      'subscribed' => true,
    ]);
  }

  /**
   * Create a subscriber with specific preferences.
   */
  public function withPreferences(array $preferences): static
  {
    return $this->state(fn(array $attributes) => [
      'preferences' => array_merge($attributes['preferences'], $preferences),
    ]);
  }

  /**
   * Create a subscriber with full name.
   */
  public function withName(): static
  {
    return $this->state(fn(array $attributes) => [
      'first_name' => $this->faker->firstName(),
      'last_name' => $this->faker->lastName(),
    ]);
  }

  /**
   * Create a bounced subscriber.
   */
  public function bounced(): static
  {
    return $this->state(fn(array $attributes) => [
      'subscribed' => false,
      'status' => 'bounced',
      'subscribed_at' => $this->faker->dateTimeBetween('-6 months', '-1 month'),
      'unsubscribed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
    ]);
  }

  /**
   * Create a complained subscriber.
   */
  public function complained(): static
  {
    return $this->state(fn(array $attributes) => [
      'subscribed' => false,
      'status' => 'complained',
      'subscribed_at' => $this->faker->dateTimeBetween('-6 months', '-1 month'),
      'unsubscribed_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
    ]);
  }

  /**
   * Create a subscriber with daily frequency preference.
   */
  public function dailyFrequency(): static
  {
    return $this->state(fn(array $attributes) => [
      'preferences' => array_merge($attributes['preferences'], [
        'frequency' => 'daily',
      ]),
    ]);
  }

  /**
   * Create a subscriber with weekly frequency preference.
   */
  public function weeklyFrequency(): static
  {
    return $this->state(fn(array $attributes) => [
      'preferences' => array_merge($attributes['preferences'], [
        'frequency' => 'weekly',
      ]),
    ]);
  }

  /**
   * Create a subscriber with monthly frequency preference.
   */
  public function monthlyFrequency(): static
  {
    return $this->state(fn(array $attributes) => [
      'preferences' => array_merge($attributes['preferences'], [
        'frequency' => 'monthly',
      ]),
    ]);
  }
}
