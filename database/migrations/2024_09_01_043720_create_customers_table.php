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
    Schema::create('customers', function (Blueprint $table) {
      $table->id();
      $table->uuid('uuid')->unique();
      $table->string('first_name');
      $table->string('last_name');
      $table->string('job_title');
      $table->string('company_name')->nullable();
      $table->string('email')->unique();
      $table->string('phone_number')->nullable();
      $table->string('website')->nullable();
      $table->json('address')->nullable();
      $table->text('notes')->nullable();
      $table->enum('status', ['active', 'inactive', 'prospect'])->default('active');
      $table->string('avatar_url')->nullable();
      $table->timestamps();
      $table->softDeletes();

      // Indexes for better performance
      $table->index(['status', 'created_at']);
      $table->index(['company_name', 'status']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('customers');
  }
};
