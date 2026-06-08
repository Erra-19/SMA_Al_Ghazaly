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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id('testimonial_id');
            $table->string('name', 100);
            $table->string('role', 100)->nullable();
            $table->text('content');
            $table->string('photo', 255)->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->tinyInteger('is_published')->default(0);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();

            $table->index('is_published', 'idx_testimonial_is_published');
            $table->index('order', 'idx_testimonial_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
