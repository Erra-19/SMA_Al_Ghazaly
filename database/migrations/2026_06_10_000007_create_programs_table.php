<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id('program_id');
            $table->string('title', 150);
            $table->string('slug', 170)->unique();
            $table->string('type', 30)->default('unggulan');
            $table->string('subtitle', 180)->nullable();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('badge', 80)->nullable();
            $table->string('stats', 120)->nullable();
            $table->json('features')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index(['type', 'is_published', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
