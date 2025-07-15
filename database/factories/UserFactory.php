<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $socialPlatforms = ['twitter', 'linkedin', 'github', 'behance', 'dribbble', 'instagram'];
        $socials = [];

        // Randomly add 1-3 social platforms
        $numSocials = $this->faker->numberBetween(0, 3);
        $selectedPlatforms = $this->faker->randomElements($socialPlatforms, $numSocials);

        foreach ($selectedPlatforms as $platform) {
            $socials[$platform] = $this->generateSocialUrl($platform);
        }

        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => $this->faker->boolean(90) ? now() : null,
            'password' => static::$password ??= Hash::make('password'),
            'phone_number' => $this->faker->boolean(70) ? $this->faker->phoneNumber() : null,
            'bio' => $this->faker->boolean(60) ? $this->faker->paragraph(2) : null,
            'website' => $this->faker->boolean(40) ? $this->faker->url() : null,
            'location' => $this->faker->boolean(70) ? $this->faker->city() . ', ' . $this->faker->country() : null,
            'timezone' => $this->faker->randomElement([
                'UTC', 'America/New_York', 'America/Los_Angeles', 'Europe/London',
                'Europe/Paris', 'Asia/Tokyo', 'Australia/Sydney', 'Africa/Johannesburg'
            ]),
            'socials' => $socials,
            'preferences' => [
                'theme' => $this->faker->randomElement(['light', 'dark', 'auto']),
                'language' => $this->faker->randomElement(['en', 'es', 'fr', 'de']),
                'notifications' => [
                    'email' => $this->faker->boolean(80),
                    'browser' => $this->faker->boolean(70),
                    'mobile' => $this->faker->boolean(60),
                ],
                'dashboard' => [
                    'show_stats' => $this->faker->boolean(90),
                    'show_recent_activity' => $this->faker->boolean(85),
                    'items_per_page' => $this->faker->randomElement([10, 15, 25, 50]),
                ],
            ],
            'last_login_at' => $this->faker->boolean(80) ?
                $this->faker->dateTimeBetween('-30 days', 'now') : null,
            'is_active' => $this->faker->boolean(95),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Generate a social media URL for the given platform.
     */
    private function generateSocialUrl(string $platform): string
    {
        $username = $this->faker->userName();

        return match($platform) {
            'twitter' => "https://twitter.com/{$username}",
            'linkedin' => "https://linkedin.com/in/{$username}",
            'github' => "https://github.com/{$username}",
            'behance' => "https://behance.net/{$username}",
            'dribbble' => "https://dribbble.com/{$username}",
            'instagram' => "https://instagram.com/{$username}",
            default => "https://{$platform}.com/{$username}",
        };
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Create an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'is_active' => true,
            'bio' => 'System administrator with full access to all features.',
            'website' => 'https://example.com',
            'location' => 'San Francisco, CA',
        ]);
    }

    /**
     * Create an inactive user.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
            'last_login_at' => $this->faker->dateTimeBetween('-6 months', '-1 month'),
        ]);
    }

    /**
     * Create a recently active user.
     */
    public function recentlyActive(): static
    {
        return $this->state(fn(array $attributes) => [
            'last_login_at' => $this->faker->dateTimeBetween('-1 hour', 'now'),
            'is_active' => true,
        ]);
    }
}
