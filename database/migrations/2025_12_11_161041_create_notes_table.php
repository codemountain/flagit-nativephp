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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();

            // External API identification
            $table->string('note_id')->nullable(); // API note ID

            // User information from API
            $table->string('from_user_id'); // API user ID (external_user_id)
            $table->string('from_name'); // User's name from API

            // Note content
            $table->string('app_key')->default('flagit');
            $table->text('content');
            $table->string('default_image')->nullable();

            // Polymorphic relationship - can belong to any model
            $table->string('noteable_type')->default('App\Models\Report');
            $table->string('noteable_id');

            $table->timestamps();

            // Indexes for better performance
            $table->index('note_id');
            $table->index('from_user_id');
            $table->index(['noteable_type', 'noteable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
