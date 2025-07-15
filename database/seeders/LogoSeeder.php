<?php

namespace Database\Seeders;

use App\Models\Logo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Create tech company logos (8)
    Logo::factory(8)->techCompany()->create()->each(function ($logo) {
      $this->addLogoMedia($logo, 'tech');
    });

    // Create creative agency logos (6)
    Logo::factory(6)->creativeAgency()->create()->each(function ($logo) {
      $this->addLogoMedia($logo, 'creative');
    });

    // Create PNG logos (5)
    Logo::factory(5)->png()->create()->each(function ($logo) {
      $this->addLogoMedia($logo, 'png');
    });

    // Create SVG logos (4)
    Logo::factory(4)->svg()->create()->each(function ($logo) {
      $this->addLogoMedia($logo, 'svg');
    });

    // Create recent logos (3)
    Logo::factory(3)->recent()->create()->each(function ($logo) {
      $this->addLogoMedia($logo, 'recent');
    });

    // Create some specific showcase logos
    $this->createShowcaseLogos();

    $this->command->info('Created logos with media files:');
    $this->command->info('- 8 Tech company logos');
    $this->command->info('- 6 Creative agency logos');
    $this->command->info('- 5 PNG format logos');
    $this->command->info('- 4 SVG format logos');
    $this->command->info('- 3 Recent logos');
    $this->command->info('- 5 Showcase logos');
    $this->command->info('Total: 31 logos with media attachments');
  }

  /**
   * Create specific showcase logos.
   */
  private function createShowcaseLogos(): void
  {
    $showcaseLogos = [
      [
        'brand' => 'InnovateTech Solutions',
        'type' => 'svg',
      ],
      [
        'brand' => 'Pixel Perfect Studio',
        'type' => 'png',
      ],
      [
        'brand' => 'Digital Dynamics Agency',
        'type' => 'svg',
      ],
      [
        'brand' => 'Creative Minds Collective',
        'type' => 'png',
      ],
      [
        'brand' => 'NextGen Software Labs',
        'type' => 'svg',
      ],
    ];

    foreach ($showcaseLogos as $logoData) {
      $logo = Logo::factory()->create(['brand' => $logoData['brand']]);
      $this->addLogoMedia($logo, $logoData['type']);
    }
  }

  /**
   * Add media files to a logo.
   */
  private function addLogoMedia($logo, $type = 'general'): void
  {
    // Create temp directory if it doesn't exist
    $tempDir = storage_path('app/temp');
    if (!file_exists($tempDir)) {
      mkdir($tempDir, 0755, true);
    }

    try {
      // Determine file extension based on type
      $extension = match ($type) {
        'svg' => 'svg',
        'png' => 'png',
        default => fake()->randomElement(['png', 'jpg', 'svg'])
      };

      // Create logo file
      $logoFileName = "logo_{$logo->id}.{$extension}";
      $logoPath = $tempDir . '/' . $logoFileName;

      // Create placeholder content based on file type
      if ($extension === 'svg') {
        $svgContent = $this->generateSvgLogo($logo->brand);
        file_put_contents($logoPath, $svgContent);
      } else {
        // For raster images, create a simple placeholder
        file_put_contents($logoPath, "Logo file for {$logo->brand}");
      }

      // Add logo file to media library
      if (file_exists($logoPath)) {
        $logo->addMedia($logoPath)
          ->usingName($logo->brand . ' Logo')
          ->usingFileName($logoFileName)
          ->toMediaCollection('logo_files');

        // Clean up temp file
        unlink($logoPath);
      }

      // Create preview image (always raster for thumbnails)
      $previewFileName = "logo_{$logo->id}_preview.png";
      $previewPath = $tempDir . '/' . $previewFileName;

      file_put_contents($previewPath, "Preview image for {$logo->brand}");

      if (file_exists($previewPath)) {
        $logo->addMedia($previewPath)
          ->usingName($logo->brand . ' Preview')
          ->usingFileName($previewFileName)
          ->toMediaCollection('preview');

        unlink($previewPath);
      }

    } catch (\Exception $e) {
      // Skip this logo if there's an error
      $this->command->warn("Failed to add media for logo: {$logo->brand}");
    }
  }

  /**
   * Generate a simple SVG logo.
   */
  private function generateSvgLogo($brandName): string
  {
    $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4'];
    $color = fake()->randomElement($colors);
    $initials = strtoupper(substr($brandName, 0, 2));

    return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
  <rect width="200" height="200" fill="{$color}" rx="20"/>
  <text x="100" y="120" font-family="Arial, sans-serif" font-size="60" font-weight="bold"
        text-anchor="middle" fill="white">{$initials}</text>
</svg>
SVG;
  }
}
