<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            // API has "id": 13 (int)
            $table->unsignedBigInteger('id')->primary();

            // attachment_id looks like a ULID
            $table->string('attachment_id', 26)->unique()->index();

            // app_key: "actionit"
            $table->string('app_key', 50)->index();

            // url + file_path
            $table->text('url')->nullable();
            $table->string('file_path', 2048)->nullable()->index();

            // Polymorphic relation (attachable)
            $table->string('attachable_type')->index();
            $table->string('attachable_id', 26)->index();
            $table->index(['attachable_type', 'attachable_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
