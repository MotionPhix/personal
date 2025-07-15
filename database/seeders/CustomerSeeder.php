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
        for ($i = 0; $i < rand(2, 4); $i++) {
          $project = $customer->projects()->create(Project::factory()->make()->toArray());
          $this->addProjectMedia($project);
        }
      });

    // Creative industry customers (4)
    Customer::factory(4)
      ->creativeIndustry()
      ->active()
      ->create()
      ->each(function ($customer) {
        // Each creative customer gets 1-3 projects
        for ($i = 0; $i < rand(1, 3); $i++) {
          $project = $customer->projects()->create(Project::factory()->make()->toArray());
          $this->addProjectMedia($project);
        }
      });

    // Individual clients (3)
    Customer::factory(3)
      ->individual()
      ->active()
      ->create()
      ->each(function ($customer) {
        // Individual clients typically have 1-2 projects
        for ($i = 0; $i < rand(1, 2); $i++) {
          $project = $customer->projects()->create(Project::factory()->make()->toArray());
          $this->addProjectMedia($project);
        }
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
          $project = $customer->projects()->create(Project::factory()->make()->toArray());
          $this->addProjectMedia($project);
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
    $project1 = $techCustomer->projects()->create(Project::factory()->make([
      'name' => 'Corporate Website Redesign',
      'production_type' => 'Web Development',
      'description' => 'Complete redesign and development of corporate website with modern UI/UX, improved performance, and mobile responsiveness.',
    ])->toArray());
    $this->addProjectMedia($project1);

    $project2 = $techCustomer->projects()->create(Project::factory()->make([
      'name' => 'Brand Identity System',
      'production_type' => 'Branding',
      'description' => 'Comprehensive brand identity including logo design, color palette, typography, and brand guidelines.',
    ])->toArray());
    $this->addProjectMedia($project2);

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

    $project3 = $creativeCustomer->projects()->create(
      Project::factory()->make([
        'name' => 'Interactive Portfolio Platform',
        'production_type' => 'Web Application',
        'description' => 'Custom portfolio platform with interactive galleries, client management, and project showcase features.',
      ])->toArray()
    );
    $this->addProjectMedia($project3);
  }

  /**
   * Add media to a project.
   */
  private function addProjectMedia($project): void
  {
    // Create temp directory if it doesn't exist
    $tempDir = storage_path('app/temp');
    if (!file_exists($tempDir)) {
      mkdir($tempDir, 0755, true);
    }

    // Add gallery images
    for ($i = 0; $i < rand(2, 5); $i++) {
      try {
        // Use a simple approach to create placeholder images
        $width = rand(800, 1200);
        $height = rand(600, 900);

        // Create a simple placeholder image URL (this won't actually download)
        $imageUrl = "https://picsum.photos/{$width}/{$height}";

        // For seeding purposes, we'll create a simple text file as placeholder
        $fileName = "project_{$project->id}_image_{$i}.jpg";
        $filePath = $tempDir . '/' . $fileName;

        // Create a simple placeholder file
        file_put_contents($filePath, "Placeholder image for project {$project->name}");

        // Add to media library
        if (file_exists($filePath)) {
          $project->addMedia($filePath)
            ->usingName("Project Image " . ($i + 1))
            ->usingFileName($fileName)
            ->toMediaCollection('gallery');

          // Clean up temp file
          unlink($filePath);
        }
      } catch (\Exception $e) {
        // Skip this image if there's an error
        continue;
      }
    }

    // Add a poster image
    try {
      $posterFileName = "project_{$project->id}_poster.jpg";
      $posterPath = $tempDir . '/' . $posterFileName;

      file_put_contents($posterPath, "Poster image for project {$project->name}");

      if (file_exists($posterPath)) {
        $project->addMedia($posterPath)
          ->usingName("Project Poster")
          ->usingFileName($posterFileName)
          ->toMediaCollection('poster');

        unlink($posterPath);
      }
    } catch (\Exception $e) {
      // Skip poster if there's an error
    }
  }
}
