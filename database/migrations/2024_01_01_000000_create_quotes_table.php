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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->enum('project_type', ['web_design', 'branding', 'photography', 'marketing', 'print_design']);
            $table->enum('budget_range', ['under_1000', '1000_5000', '5000_10000', '10000_25000', 'over_25000', 'discuss']);
            $table->enum('timeline', ['asap', '1_2_weeks', '1_month', '2_3_months', '3_6_months', 'flexible']);
            $table->text('description');
            $table->text('goals')->nullable();
            $table->string('target_audience')->nullable();
            $table->text('additional_info')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'quoted', 'accepted', 'declined', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
