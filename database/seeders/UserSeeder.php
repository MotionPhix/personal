<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Create the main admin user
    $admin = User::factory()->create([
      'first_name' => 'Kingsley',
      'last_name' => 'Nyirenda',
      'email' => 'hello@ultrashots.net',
      'phone_number' => '+265 888 166 911',
      'password' => 'run%$Ace5',
      'bio' => 'Full-stack developer and designer passionate about creating exceptional digital experiences.',
      'website' => 'https://ultrashots.net',
      'location' => 'Lilongwe, Malawi',
      'timezone' => 'Africa/Blantyre',
      'socials' => [
        'twitter' => 'https://twitter.com/ultrashoots',
        'linkedin' => 'https://linkedin.com/in/ultrashots',
        'github' => 'https://github.com/ultrashots',
        'behance' => 'https://behance.net/ultrashots',
      ],
      'preferences' => [
        'theme' => 'dark',
        'language' => 'en',
        'notifications' => [
          'email' => true,
          'browser' => true,
          'mobile' => true,
        ],
        'dashboard' => [
          'show_stats' => true,
          'show_recent_activity' => true,
          'items_per_page' => 25,
        ],
      ],
      'is_active' => true,
      'last_login_at' => now(),
    ]);

    // Assign super-admin role
    $admin->assignRole('super-admin');

    // Create additional test users with different roles
    $manager = User::factory()->create([
      'first_name' => 'Sarah',
      'last_name' => 'Johnson',
      'email' => 'manager@example.com',
      'bio' => 'Project manager with 5+ years of experience in digital agencies.',
      'location' => 'New York, USA',
    ]);
    $manager->assignRole('manager');

    $editor = User::factory()->create([
      'first_name' => 'Mike',
      'last_name' => 'Chen',
      'email' => 'editor@example.com',
      'bio' => 'Content editor and UX writer focused on creating compelling digital narratives.',
      'location' => 'San Francisco, USA',
    ]);
    $editor->assignRole('editor');

    $viewer = User::factory()->create([
      'first_name' => 'Emma',
      'last_name' => 'Davis',
      'email' => 'viewer@example.com',
      'bio' => 'Marketing specialist interested in portfolio analytics and insights.',
      'location' => 'London, UK',
    ]);
    $viewer->assignRole('viewer');

    // Create some additional random users
    User::factory(5)->create()->each(function ($user) {
      $roles = ['editor', 'viewer'];
      $user->assignRole(fake()->randomElement($roles));
    });

    // Create some inactive users
    User::factory(2)->inactive()->create()->each(function ($user) {
      $user->assignRole('viewer');
    });

    $this->command->info('Created users with roles:');
    $this->command->info('- 1 Super Admin (hello@ultrashots.net)');
    $this->command->info('- 1 Manager (manager@example.com)');
    $this->command->info('- 1 Editor (editor@example.com)');
    $this->command->info('- 1 Viewer (viewer@example.com)');
    $this->command->info('- 5 Random users with editor/viewer roles');
    $this->command->info('- 2 Inactive users');
    $this->command->info('Default password for all users: password');
  }

  /**
   * Get a faker instance.
   */
  private function faker()
  {
    return \Faker\Factory::create();
  }
}
