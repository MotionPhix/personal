<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * This migration enhances the subscribers table with additional fields
   * for better subscriber management, tracking, and preferences.
   */
  public function up(): void
  {
    Schema::table('subscribers', function (Blueprint $table) {
      // Subscription tracking
      $table->timestamp('subscribed_at')->nullable()->after('subscribed');
      $table->timestamp('unsubscribed_at')->nullable()->after('subscribed_at');

      // Source tracking
      $table->string('source')->default('website')->after('unsubscribed_at');
      $table->ipAddress('ip_address')->nullable()->after('source');
      $table->text('user_agent')->nullable()->after('ip_address');

      // Verification system
      $table->string('verification_token')->nullable()->after('token');
      $table->timestamp('verified_at')->nullable()->after('verification_token');

      // Subscriber preferences (JSON)
      $table->json('preferences')->nullable()->after('verified_at');

      // Additional metadata
      $table->string('first_name')->nullable()->after('email');
      $table->string('last_name')->nullable()->after('first_name');
      $table->string('status')->default('active')->after('preferences'); // active, unsubscribed, bounced, complained
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('subscribers', function (Blueprint $table) {
      $table->dropColumn([
        'subscribed_at',
        'unsubscribed_at',
        'source',
        'ip_address',
        'user_agent',
        'verification_token',
        'verified_at',
        'preferences',
        'first_name',
        'last_name',
        'status',
      ]);
    });
  }
};
