<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Project::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $name = $this->generateProjectName();
    $productionType = fake()->randomElement([
      'Web Development',
      'Mobile App',
      'Branding',
      'UI/UX Design',
      'E-commerce',
      'WordPress',
      'Custom Software',
      'API Development'
    ]);

    $category = $this->getCategoryForProductionType($productionType);
    $technologies = $this->getTechnologiesForProductionType($productionType);
    $features = $this->getFeaturesForProductionType($productionType);

    $startDate = fake()->dateTimeBetween('-2 years', 'now');
    $endDate = fake()->dateTimeBetween($startDate, '+6 months');
    $estimatedHours = fake()->numberBetween(20, 500);
    $actualHours = fake()->boolean(70) ?
      $estimatedHours + fake()->numberBetween(-50, 100) :
      null;

    return [
      'customer_id' => Customer::factory(),
      'name' => $name,
      'slug' => Str::slug($name),
      'description' => fake()->paragraphs(3, true),
      'short_description' => fake()->sentence(20),
      'production_type' => $productionType,
      'category' => $category,
      'status' => fake()->randomElement(['not_started', 'in_progress', 'on_hold', 'completed', 'cancelled']),
      'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
      'start_date' => $startDate,
      'end_date' => $endDate,
      'estimated_hours' => $estimatedHours,
      'actual_hours' => $actualHours,
      'budget' => fake()->numberBetween(1000, 50000),
      'technologies' => $technologies,
      'features' => $features,
      'challenges' => fake()->paragraph(),
      'solutions' => fake()->paragraph(),
      'results' => fake()->paragraph(),
      'client_feedback' => fake()->boolean(60) ? fake()->paragraph() : null,
      'is_featured' => fake()->boolean(20),
      'is_public' => fake()->boolean(80),
      'sort_order' => fake()->numberBetween(1, 100),
      'meta_title' => $name . ' - Portfolio Project',
      'meta_description' => fake()->sentence(15),
      'live_url' => fake()->boolean(70) ? fake()->url() : null,
      'github_url' => fake()->boolean(50) ? 'https://github.com/username/' . Str::slug($name) : null,
      'figma_url' => fake()->boolean(30) ? 'https://figma.com/file/' . fake()->uuid() : null,
      'behance_url' => fake()->boolean(20) ? 'https://behance.net/gallery/' . fake()->randomNumber(8) : null,
      'dribbble_url' => fake()->boolean(15) ? 'https://dribbble.com/shots/' . fake()->randomNumber(8) : null,
    ];
  }

  /**
   * Generate a realistic project name.
   */
  private function generateProjectName(): string
  {
    $prefixes = [
      'Corporate Website for',
      'E-commerce Platform for',
      'Mobile App for',
      'Brand Identity for',
      'Portfolio Website for',
      'Custom Dashboard for',
      'API Development for',
      'WordPress Site for',
      'Landing Page for',
      'Web Application for'
    ];

    $suffixes = [
      'Solutions',
      'Technologies',
      'Innovations',
      'Systems',
      'Digital',
      'Studio',
      'Agency',
      'Group',
      'Company',
      'Enterprise'
    ];

    $prefix = fake()->randomElement($prefixes);
    $company = fake()->company();

    // Sometimes add suffix
    if (fake()->boolean(30)) {
      $suffix = fake()->randomElement($suffixes);
      $company .= ' ' . $suffix;
    }

    return $prefix . ' ' . $company;
  }

  /**
   * Get category based on production type.
   */
  private function getCategoryForProductionType(string $productionType): string
  {
    $categories = [
      'Web Development' => ['Corporate', 'Portfolio', 'Blog', 'News', 'Educational'],
      'Mobile App' => ['Social', 'Productivity', 'E-commerce', 'Health', 'Entertainment'],
      'Branding' => ['Logo Design', 'Brand Identity', 'Brand Guidelines', 'Rebranding'],
      'UI/UX Design' => ['Web Design', 'Mobile Design', 'Dashboard Design', 'Prototype'],
      'E-commerce' => ['Online Store', 'Marketplace', 'B2B Platform', 'Subscription'],
      'WordPress' => ['Business Site', 'Blog', 'Portfolio', 'E-commerce'],
      'Custom Software' => ['CRM', 'ERP', 'Management System', 'Analytics'],
      'API Development' => ['REST API', 'GraphQL', 'Microservices', 'Integration']
    ];

    return fake()->randomElement($categories[$productionType] ?? ['General']);
  }

  /**
   * Get technologies based on production type.
   */
  private function getTechnologiesForProductionType(string $productionType): array
  {
    $techStacks = [
      'Web Development' => ['Laravel', 'Vue.js', 'TypeScript', 'Tailwind CSS', 'MySQL', 'Redis'],
      'Mobile App' => ['React Native', 'Flutter', 'Swift', 'Kotlin', 'Firebase', 'SQLite'],
      'Branding' => ['Adobe Illustrator', 'Adobe Photoshop', 'Figma', 'Sketch'],
      'UI/UX Design' => ['Figma', 'Adobe XD', 'Sketch', 'InVision', 'Principle'],
      'E-commerce' => ['Laravel', 'Vue.js', 'Stripe', 'PayPal', 'MySQL', 'Redis'],
      'WordPress' => ['WordPress', 'PHP', 'MySQL', 'jQuery', 'CSS3', 'HTML5'],
      'Custom Software' => ['Laravel', 'React', 'PostgreSQL', 'Docker', 'AWS'],
      'API Development' => ['Laravel', 'Node.js', 'PostgreSQL', 'Redis', 'Docker']
    ];

    $baseTech = $techStacks[$productionType] ?? ['HTML', 'CSS', 'JavaScript'];

    // Return 3-6 technologies
    return fake()->randomElements($baseTech, fake()->numberBetween(3, min(6, count($baseTech))));
  }

  /**
   * Get features based on production type.
   */
  private function getFeaturesForProductionType(string $productionType): array
  {
    $featureSets = [
      'Web Development' => [
        'Responsive Design',
        'SEO Optimization',
        'Contact Forms',
        'Content Management',
        'Performance Optimization',
        'Security Features',
        'Analytics Integration',
        'Social Media Integration'
      ],
      'Mobile App' => [
        'User Authentication',
        'Push Notifications',
        'Offline Support',
        'In-App Purchases',
        'Social Login',
        'Real-time Updates',
        'Location Services',
        'Camera Integration'
      ],
      'Branding' => [
        'Logo Design',
        'Color Palette',
        'Typography System',
        'Brand Guidelines',
        'Business Cards',
        'Letterhead Design',
        'Social Media Assets',
        'Brand Applications'
      ],
      'E-commerce' => [
        'Product Catalog',
        'Shopping Cart',
        'Payment Gateway',
        'Order Management',
        'Inventory Tracking',
        'Customer Accounts',
        'Wishlist',
        'Reviews & Ratings'
      ]
    ];

    $baseFeatures = $featureSets[$productionType] ?? [
      'Custom Design',
      'User-Friendly Interface',
      'Performance Optimization',
      'Security Features'
    ];

    // Return 4-8 features
    return fake()->randomElements($baseFeatures, fake()->numberBetween(4, min(8, count($baseFeatures))));
  }

  /**
   * Indicate that the project is completed.
   */
  public function completed(): static
  {
    return $this->state(fn (array $attributes) => [
      'status' => 'completed',
      'actual_hours' => $attributes['estimated_hours'] + fake()->numberBetween(-20, 50),
      'client_feedback' => fake()->paragraph(),
      'results' => fake()->paragraph(),
    ]);
  }

  /**
   * Indicate that the project is in progress.
   */
  public function inProgress(): static
  {
    return $this->state(fn (array $attributes) => [
      'status' => 'in_progress',
      'start_date' => fake()->dateTimeBetween('-3 months', 'now'),
      'end_date' => fake()->dateTimeBetween('now', '+3 months'),
    ]);
  }

  /**
   * Indicate that the project is featured.
   */
  public function featured(): static
  {
    return $this->state(fn (array $attributes) => [
      'is_featured' => true,
      'is_public' => true,
      'status' => 'completed',
    ]);
  }

  /**
   * Create a web development project.
   */
  public function webDevelopment(): static
  {
    return $this->state(fn (array $attributes) => [
      'production_type' => 'Web Development',
      'category' => fake()->randomElement(['Corporate', 'Portfolio', 'E-commerce', 'Blog']),
      'technologies' => ['Laravel', 'Vue.js', 'TypeScript', 'Tailwind CSS', 'MySQL'],
      'live_url' => fake()->url(),
      'github_url' => 'https://github.com/username/' . Str::slug($attributes['name']),
    ]);
  }

  /**
   * Create a mobile app project.
   */
  public function mobileApp(): static
  {
    return $this->state(fn (array $attributes) => [
      'production_type' => 'Mobile App',
      'category' => fake()->randomElement(['Social', 'Productivity', 'E-commerce', 'Health']),
      'technologies' => ['React Native', 'TypeScript', 'Firebase', 'Redux'],
      'github_url' => 'https://github.com/username/' . Str::slug($attributes['name']),
    ]);
  }

  /**
   * Create a branding project.
   */
  public function branding(): static
  {
    return $this->state(fn (array $attributes) => [
      'production_type' => 'Branding',
      'category' => fake()->randomElement(['Logo Design', 'Brand Identity', 'Rebranding']),
      'technologies' => ['Adobe Illustrator', 'Adobe Photoshop', 'Figma'],
      'behance_url' => 'https://behance.net/gallery/' . fake()->randomNumber(8),
      'dribbble_url' => 'https://dribbble.com/shots/' . fake()->randomNumber(8),
    ]);
  }
}
