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
        Schema::create('user_syncs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('model');
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamp('notified_at')->nullable()->after('last_synced_at');
            $table->timestamps();

            $table->unique(['user_id', 'model']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_syncs');
    }
};
