<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worklogs', function (Blueprint $table) {
            // Primary identifier from API
            $table->unsignedBigInteger('id')->primary();

            // External / API reference
            $table->unsignedBigInteger('worklog_id')->index();

            // Polymorphic relation (workable)
            $table->string('workable_type')->index();
            $table->string('workable_id')->index();

            $table->text('description')->nullable();

            // Duration in minutes
            $table->unsignedInteger('duration')->default(0);

            // ISO8601 timestamp from API
            $table->timestamp('performed_at')->nullable()->index();

            // ULID user reference
            $table->string('performed_by', 26)->nullable()->index();

            $table->boolean('paid')->default(false);
            $table->boolean('solo')->default(false);
            $table->boolean('approved')->default(false);

            // Can be false or a user id → nullable string
            $table->string('approved_by', 26)->nullable()->index();

            // Composite index for morph resolution
            $table->index(['workable_type', 'workable_id']);

            // Local sync bookkeeping
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worklogs');
    }
};
