<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Consolidated: create_posts_table
//   + add_extended_fields_to_posts_table

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id('post_id');
            $table->string('title', 255);
            $table->enum('type', ['news', 'article', 'event'])->default('news');
            $table->string('slug', 280)->unique();
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->string('category', 100)->nullable();
            $table->string('post_status', 50)->nullable();
            $table->tinyInteger('is_published')->default(0);
            $table->unsignedSmallInteger('order')->default(0);
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('meta_title', 160)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->dateTime('event_start_at')->nullable();
            $table->dateTime('event_end_at')->nullable();
            $table->string('event_location', 150)->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('is_published');
            $table->index('author_id');
            $table->index('category');

            $table->foreign('author_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
