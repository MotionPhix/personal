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
    Schema::create('photos', function (Blueprint $table) {
      $table->id();

      $table->uuid('fid')->nullable();

      $table->bigInteger('size')->nullable();

      $table->string('src');

      $table->string('mime_type');

      $table->nullableMorphs('model');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('photos');
  }
};
