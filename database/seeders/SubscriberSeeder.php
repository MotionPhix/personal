<?php

namespace Database\Seeders;

use App\Models\Subscriber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create active subscribers with names (30)
        Subscriber::factory(30)->subscribed()->withName()->create();

        // Create active subscribers without names (15)
        Subscriber::factory(15)->subscribed()->create();

        // Create recent subscribers (8)
        Subscriber::factory(8)->recent()->withName()->create();

        // Create unsubscribed users (6)
        Subscriber::factory(6)->unsubscribed()->create();

        // Create pending verification (3)
        Subscriber::factory(3)->pending()->create();

        // Create bounced subscribers (2)
        Subscriber::factory(2)->bounced()->create();

        // Create complained subscribers (1)
        Subscriber::factory(1)->complained()->create();

        // Create subscribers from different sources
        Subscriber::factory(8)->fromWebsite()->withName()->create();
        Subscriber::factory(5)->fromSocialMedia()->create();

        // Create subscribers with specific frequency preferences
        Subscriber::factory(5)->dailyFrequency()->withName()->create();
        Subscriber::factory(10)->weeklyFrequency()->create();
        Subscriber::factory(8)->monthlyFrequency()->create();

        // Create subscribers with specific topic preferences
        Subscriber::factory(4)->withPreferences([
            'frequency' => 'weekly',
            'topics' => ['web_development', 'tutorials'],
            'format' => 'html',
        ])->withName()->create();

        Subscriber::factory(3)->withPreferences([
            'frequency' => 'monthly',
            'topics' => ['project_updates', 'case_studies'],
            'format' => 'text',
        ])->create();

        Subscriber::factory(2)->withPreferences([
            'frequency' => 'daily',
            'topics' => ['design_tips', 'industry_news'],
            'format' => 'html',
        ])->withName()->create();

        // Create some VIP subscribers (with all topics)
        Subscriber::factory(3)->withPreferences([
            'frequency' => 'weekly',
            'topics' => [
                'web_development',
                'design_tips',
                'project_updates',
                'industry_news',
                'tutorials',
                'case_studies'
            ],
            'format' => 'html',
        ])->withName()->subscribed()->create();

        $this->command->info('Created subscribers with enhanced data:');
        $this->command->info('- 45 Active subscribers (30 with names, 15 without)');
        $this->command->info('- 8 Recent subscribers');
        $this->command->info('- 6 Unsubscribed users');
        $this->command->info('- 3 Pending verification');
        $this->command->info('- 2 Bounced subscribers');
        $this->command->info('- 1 Complained subscriber');
        $this->command->info('- 13 From various sources');
        $this->command->info('- 23 With frequency preferences');
        $this->command->info('- 12 With topic preferences');
        $this->command->info('- 3 VIP subscribers (all topics)');
        $this->command->info('Total: 116 subscribers with realistic data');
    }
}
