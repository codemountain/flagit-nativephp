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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // Core report fields
            $table->string('report_id')->nullable(); // ULID from API
            $table->string('category')->nullable();
            $table->string('title');
            $table->text('description');

            // Location fields
            $table->string('network_name')->nullable();
            $table->string('trail_name')->nullable();
            $table->decimal('lat', 10, 7); // Latitude with 7 decimal places
            $table->decimal('long', 10, 7); // Longitude with 7 decimal places

            // Image fields
            $table->text('image')->nullable(); // Full URL can be long
            $table->text('thumb')->nullable(); // Thumbnail URL

            // Status and metadata
            $table->string('status')->default('draft');
            $table->boolean('is_urgent')->default(false);
            $table->string('slug')->nullable();

            // Change image fields to support base64 data for offline reports
            $table->longText('local_image')->nullable(); // Support base64 encoded images
            $table->longText('local_thumb')->nullable(); // Will be same as image for local reports

            // Relationship IDs (ULIDs)
            $table->string('team_id')->nullable();
            $table->string('network_id')->nullable();
            $table->string('created_by')->nullable(); // User ULID who created the report
            $table->text('assigned_user_ids')->nullable(); // Comma-separated ULIDs

            // Additional metadata
            $table->text('network_logo_url')->nullable();
            $table->decimal('distance', 10, 2)->nullable(); // Distance in appropriate units

            // Category and skill metadata
            $table->text('category_names')->nullable();
            $table->text('skill_names')->nullable();
            $table->text('material_names')->nullable();
            $table->text('task_names')->nullable();
            $table->text('equipment_names')->nullable();

            // Notes tracking
            $table->integer('notes_count')->default(0);

            // Date fields
            $table->dateTime('created_date')->nullable(); // The actual creation date from API
            $table->string('elapsed')->nullable(); // Human-readable elapsed time

            $table->timestamps(); // Laravel's created_at and updated_at

            // Indexes for performance
            $table->index('report_id');
            $table->index('status');
            $table->index(['lat', 'long']); // Spatial index for location queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
