<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Create a mix of different customer types

    // Tech industry customers (5)
    Customer::factory(5)
      ->techIndustry()
      ->active()
      ->create()
      ->each(function ($customer) {
        // Each tech customer gets 2-4 projects
        $customer->projects()->createMany(
          Project::factory(rand(2, 4))->make()->toArray()
        )->each(function ($project) {
          $this->addProjectMedia($project);
        });
      });

    // Creative industry customers (4)
    Customer::factory(4)
      ->creativeIndustry()
      ->active()
      ->create()
      ->each(function ($customer) {
        // Each creative customer gets 1-3 projects
        $customer->projects()->createMany(
          Project::factory(rand(1, 3))->make()->toArray()
        )->each(function ($project) {
          $this->addProjectMedia($project);
        });
      });

    // Individual clients (3)
    Customer::factory(3)
      ->individual()
      ->active()
      ->create()
      ->each(function ($customer) {
        // Individual clients typically have 1-2 projects
        $customer->projects()->createMany(
          Project::factory(rand(1, 2))->make()->toArray()
        )->each(function ($project) {
          $this->addProjectMedia($project);
        });
      });

    // Prospect customers (2) - no projects yet
    Customer::factory(2)
      ->prospect()
      ->create();

    // Inactive customers (1) - might have old projects
    Customer::factory(1)
      ->inactive()
      ->create()
      ->each(function ($customer) {
        if (rand(0, 1)) {
          $customer->projects()->createMany(
            Project::factory(1)->make()->toArray()
          )->each(function ($project) {
            $this->addProjectMedia($project);
          });
        }
      });

    // Create some specific showcase customers for portfolio
    $this->createShowcaseCustomers();
  }

  /**
   * Create specific customers for portfolio showcase.
   */
  private function createShowcaseCustomers(): void
  {
    // High-profile tech company
    $techCustomer = Customer::factory()->create([
      'first_name' => 'Sarah',
      'last_name' => 'Johnson',
      'job_title' => 'Head of Digital Marketing',
      'company_name' => 'InnovateTech Solutions',
      'email' => 'sarah.johnson@innovatetech.com',
      'phone_number' => '+1 (555) 123-4567',
      'website' => 'https://innovatetech.com',
      'status' => 'active',
      'address' => [
        'street' => '123 Innovation Drive',
        'city' => 'San Francisco',
        'state' => 'CA',
        'postal_code' => '94105',
        'country' => 'United States',
      ],
      'notes' => 'Long-term client with multiple successful projects. Excellent communication and clear requirements.',
    ]);

    // Create flagship projects for this customer
    $techCustomer->projects()->createMany([
      Project::factory()->make([
        'name' => 'Corporate Website Redesign',
        'production' => 'Web Development',
        'description' => 'Complete redesign and development of corporate website with modern UI/UX, improved performance, and mobile responsiveness.',
      ])->toArray(),
      Project::factory()->make([
        'name' => 'Brand Identity System',
        'production' => 'Branding',
        'description' => 'Comprehensive brand identity including logo design, color palette, typography, and brand guidelines.',
      ])->toArray(),
    ])->each(function ($project) {
      $this->addProjectMedia($project);
    });

    // Creative agency
    $creativeCustomer = Customer::factory()->create([
      'first_name' => 'Marcus',
      'last_name' => 'Chen',
      'job_title' => 'Creative Director',
      'company_name' => 'Pixel Perfect Studio',
      'email' => 'marcus@pixelperfect.studio',
      'phone_number' => '+1 (555) 987-6543',
      'website' => 'https://pixelperfect.studio',
      'status' => 'active',
      'address' => [
        'street' => '456 Creative Boulevard',
        'city' => 'New York',
        'state' => 'NY',
        'postal_code' => '10001',
        'country' => 'United States',
      ],
      'notes' => 'Creative agency specializing in digital art and interactive experiences. Values innovative design solutions.',
    ]);

    $creativeCustomer->projects()->create(
      Project::factory()->make([
        'name' => 'Interactive Portfolio Platform',
        'production' => 'Web Application',
        'description' => 'Custom portfolio platform with interactive galleries, client management, and project showcase features.',
      ])->toArray()
    );
  }

  /**
   * Add media to a project.
   */
  private function addProjectMedia($project): void
  {
    for ($i = 0; $i < rand(2, 7); $i++) {
      $faker = \Faker\Factory::create();
      $faker->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider($faker));

      // Randomly choose image size
      $sizes = [
        [640, 480],
        [800, 600],
        [1024, 768],
        [480, 640],
        [600, 800],
        [768, 1024],
        [1280, 720],
        [720, 1280]
      ];

      [$width, $height] = $faker->randomElement($sizes);

      // Generate image and add to Spatie Media Library
      $imagePath = $faker->image('public/tmp', $width, $height);
      $project->addMedia($imagePath)->toMediaCollection('bucket');
    }
  }
}
