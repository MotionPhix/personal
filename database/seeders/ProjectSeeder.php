<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Note: Most projects are created via CustomerSeeder
    // This seeder creates additional standalone projects

    // Create some featured projects for portfolio showcase
    $this->createFeaturedProjects();

    // Create projects with different statuses
    $this->createProjectsByStatus();

    // Create projects with different production types
    $this->createProjectsByType();

    $this->command->info('Created additional projects:');
    $this->command->info('- 5 Featured projects');
    $this->command->info('- 10 Projects with various statuses');
    $this->command->info('- 15 Projects by production type');
  }

  /**
   * Create featured projects for portfolio showcase.
   */
  private function createFeaturedProjects(): void
  {
    // Get some existing customers or create new ones
    $customers = Customer::active()->take(5)->get();

    if ($customers->count() < 5) {
      $customers = $customers->merge(Customer::factory(5 - $customers->count())->active()->create());
    }

    $featuredProjects = [
      [
        'name' => 'E-commerce Platform for Fashion Retailer',
        'production_type' => 'Web Development',
        'category' => 'E-commerce',
        'description' => 'Complete e-commerce solution with custom product configurator, advanced filtering, and seamless checkout experience.',
        'technologies' => ['Laravel', 'Vue.js', 'TypeScript', 'Tailwind CSS', 'Stripe'],
        'status' => 'completed',
        'is_featured' => true,
        'is_public' => true,
      ],
      [
        'name' => 'Mobile Banking App UI/UX Design',
        'production_type' => 'UI/UX Design',
        'category' => 'Mobile Design',
        'description' => 'Modern, secure, and user-friendly mobile banking application with biometric authentication and real-time notifications.',
        'technologies' => ['Figma', 'Adobe XD', 'Principle', 'InVision'],
        'status' => 'completed',
        'is_featured' => true,
        'is_public' => true,
      ],
      [
        'name' => 'SaaS Dashboard for Analytics Platform',
        'production_type' => 'Web Application',
        'category' => 'Dashboard',
        'description' => 'Comprehensive analytics dashboard with real-time data visualization, custom reports, and team collaboration features.',
        'technologies' => ['React', 'TypeScript', 'D3.js', 'Node.js', 'PostgreSQL'],
        'status' => 'completed',
        'is_featured' => true,
        'is_public' => true,
      ],
      [
        'name' => 'Brand Identity for Tech Startup',
        'production_type' => 'Branding',
        'category' => 'Brand Identity',
        'description' => 'Complete brand identity system including logo, color palette, typography, and brand guidelines for innovative tech startup.',
        'technologies' => ['Adobe Illustrator', 'Adobe Photoshop', 'Figma'],
        'status' => 'completed',
        'is_featured' => true,
        'is_public' => true,
      ],
      [
        'name' => 'Restaurant Management System',
        'production_type' => 'Custom Software',
        'category' => 'Management System',
        'description' => 'Integrated restaurant management system with POS, inventory tracking, staff scheduling, and customer loyalty programs.',
        'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'Pusher'],
        'status' => 'completed',
        'is_featured' => true,
        'is_public' => true,
      ],
    ];

    foreach ($featuredProjects as $index => $projectData) {
      $customer = $customers[$index];
      $customer->projects()->create(
        array_merge(
          Project::factory()->make()->toArray(),
          $projectData,
          ['customer_id' => $customer->id]
        )
      );
    }
  }

  /**
   * Create projects with different statuses.
   */
  private function createProjectsByStatus(): void
  {
    $customers = Customer::active()->get();

    if ($customers->isEmpty()) {
      $customers = Customer::factory(5)->active()->create();
    }

    // In progress projects (3)
    Project::factory(3)
      ->inProgress()
      ->create(['customer_id' => $customers->random()->id]);

    // Completed projects (4)
    Project::factory(4)
      ->completed()
      ->create(['customer_id' => $customers->random()->id]);

    // Not started projects (2)
    Project::factory(2)
      ->create([
        'customer_id' => $customers->random()->id,
        'status' => 'not_started',
        'start_date' => now()->addDays(rand(1, 30)),
      ]);

    // On hold project (1)
    Project::factory(1)
      ->create([
        'customer_id' => $customers->random()->id,
        'status' => 'on_hold',
      ]);
  }

  /**
   * Create projects by production type.
   */
  private function createProjectsByType(): void
  {
    $customers = Customer::active()->get();

    if ($customers->isEmpty()) {
      $customers = Customer::factory(5)->active()->create();
    }

    // Web development projects (5)
    Project::factory(5)
      ->webDevelopment()
      ->create(['customer_id' => $customers->random()->id]);

    // Mobile app projects (3)
    Project::factory(3)
      ->mobileApp()
      ->create(['customer_id' => $customers->random()->id]);

    // Branding projects (4)
    Project::factory(4)
      ->branding()
      ->create(['customer_id' => $customers->random()->id]);

    // UI/UX Design projects (3)
    Project::factory(3)
      ->create([
        'customer_id' => $customers->random()->id,
        'production_type' => 'UI/UX Design',
        'category' => fake()->randomElement(['Web Design', 'Mobile Design', 'Dashboard Design']),
        'technologies' => ['Figma', 'Adobe XD', 'Sketch', 'InVision'],
      ]);
  }
}
