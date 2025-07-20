<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // Rename the table
    Schema::rename('logos', 'downloads');

    // Add new columns and modify existing ones
    Schema::table('downloads', function (Blueprint $table) {
      // Rename columns
      $table->renameColumn('brand', 'brand');

      // Add new columns
      $table->string('title')->after('uuid');
      $table->text('description')->nullable()->after('title');
      $table->string('category')->nullable()->after('brand');
      $table->string('file_type')->nullable()->after('category');
      $table->bigInteger('file_size')->nullable()->after('file_type');
      $table->integer('download_count')->default(0)->after('file_size');
      $table->boolean('is_featured')->default(false)->after('download_count');
      $table->boolean('is_public')->default(true)->after('is_featured');
      $table->integer('sort_order')->default(0)->after('is_public');
      $table->string('meta_title')->nullable()->after('sort_order');
      $table->text('meta_description')->nullable()->after('meta_title');
      $table->json('tags')->nullable()->after('meta_description');

      // Add soft deletes
      $table->softDeletes();

      // Add indexes
      $table->index(['is_public', 'is_featured']);
      $table->index(['category']);
      $table->index(['file_type']);
      $table->index(['brand']);
      $table->index(['sort_order']);
      $table->index(['download_count']);
      $table->index(['created_at']);
    });

    // Update existing records with default title
    DB::table('downloads')->whereNull('title')->update([
      'title' => DB::raw("CONCAT(COALESCE(brand, 'Download'), ' - ', id)")
    ]);

    // Make title not nullable
    Schema::table('downloads', function (Blueprint $table) {
      $table->string('title')->nullable(false)->change();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Remove new columns
    Schema::table('downloads', function (Blueprint $table) {
      $table->dropColumn([
        'title', 'description', 'category', 'file_type', 'file_size',
        'download_count', 'is_featured', 'is_public', 'sort_order',
        'meta_title', 'meta_description', 'tags', 'deleted_at'
      ]);

      // Drop indexes
      $table->dropIndex(['is_public', 'is_featured']);
      $table->dropIndex(['category']);
      $table->dropIndex(['file_type']);
      $table->dropIndex(['brand']);
      $table->dropIndex(['sort_order']);
      $table->dropIndex(['download_count']);
      $table->dropIndex(['created_at']);
    });

    // Rename back to logos
    Schema::rename('downloads', 'logos');
  }
};
