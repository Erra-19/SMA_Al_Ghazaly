<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id('facility_id');
            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->string('category', 50)->default('akademik');
            $table->string('image', 255)->nullable();
            $table->string('icon_name', 50)->nullable();
            $table->string('short_desc', 255)->nullable();
            $table->text('long_desc')->nullable();
            $table->string('capacity', 80)->nullable();
            $table->json('specs')->nullable();
            $table->string('operational_hours', 100)->nullable();
            $table->string('location', 150)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index(['category', 'is_published', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
