<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * This migration enhances the users table with additional profile fields,
   * social media integration, user preferences, and activity tracking.
   * These fields support the enhanced User model with Spatie integration.
   */
  public function up(): void
  {
    Schema::table('users', function (Blueprint $table) {
      // Contact and profile information
      $table->string('phone_number')->nullable()->after('email_verified_at');
      $table->text('bio')->nullable()->after('phone_number');
      $table->string('website')->nullable()->after('bio');
      $table->string('location')->nullable()->after('website');
      $table->string('timezone')->default('UTC')->after('location');

      // Social media links (JSON)
      $table->json('socials')->nullable()->after('password');

      // User preferences and settings (JSON)
      $table->json('preferences')->nullable()->after('socials');

      // Activity tracking

      $table->timestamp('last_login_at')->nullable()->after('preferences');

      // Account status
      $table->boolean('is_active')->default(true)->after('last_login_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('users', function (Blueprint $table) {
      $table->dropColumn([
        'phone_number',
        'bio',
        'website',
        'location',
        'timezone',
        'socials',
        'preferences',
        'last_login_at',
        'is_active',
      ]);
    });
  }
};
