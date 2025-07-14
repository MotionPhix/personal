<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('projects', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid')->unique();
      $table->foreignId('customer_id')->constrained()->onDelete('cascade');
      $table->string('name');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->string('short_description', 500)->nullable();
      $table->string('production_type')->nullable(); // Web Development, Mobile App, Branding, etc.
      $table->string('category')->nullable(); // E-commerce, Portfolio, Corporate, etc.
      $table->enum('status', ['not_started', 'in_progress', 'on_hold', 'completed', 'cancelled'])->default('not_started');
      $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->decimal('estimated_hours', 8, 2)->nullable();
      $table->decimal('actual_hours', 8, 2)->nullable();
      $table->decimal('budget', 10, 2)->nullable();
      $table->json('technologies')->nullable(); // Array of technologies used
      $table->json('features')->nullable(); // Array of key features
      $table->text('challenges')->nullable();
      $table->text('solutions')->nullable();
      $table->text('results')->nullable();
      $table->text('client_feedback')->nullable();
      $table->boolean('is_featured')->default(false);
      $table->boolean('is_public')->default(true);
      $table->integer('sort_order')->default(0);
      $table->string('meta_title')->nullable();
      $table->text('meta_description')->nullable();
      $table->string('poster_url')->nullable();
      $table->string('live_url')->nullable();
      $table->string('github_url')->nullable();
      $table->string('figma_url')->nullable();
      $table->string('behance_url')->nullable();
      $table->string('dribbble_url')->nullable();
      $table->timestamps();
      $table->softDeletes();

      // Indexes for better performance
      $table->index(['status', 'created_at']);
      $table->index(['is_featured', 'is_public']);
      $table->index(['production_type', 'category']);
      $table->index(['customer_id', 'status']);
      $table->index('sort_order');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('projects');
  }
};
