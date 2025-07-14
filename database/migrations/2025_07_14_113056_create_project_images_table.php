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
    Schema::create('project_images', function (Blueprint $table) {
      $table->id();
      $table->foreignId('project_id')->constrained()->onDelete('cascade');
      $table->string('src');
      $table->string('alt')->nullable();
      $table->string('title')->nullable();
      $table->text('description')->nullable();
      $table->integer('sort_order')->default(0);
      $table->boolean('is_featured')->default(false);
      $table->timestamps();
      $table->softDeletes();

      // Indexes
      $table->index(['project_id', 'sort_order']);
      $table->index(['project_id', 'is_featured']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('project_images');
  }
};
