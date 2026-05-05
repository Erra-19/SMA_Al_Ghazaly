<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id('page_id');
            $table->string('title', 255);
            $table->string('slug', 280);
            $table->longText('content');
            $table->string('thumbnail', 255)->nullable();
            $table->tinyInteger('is_published')->default(0);
            $table->unsignedSmallInteger('order')->default(0);
            $table->string('meta_title', 160)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->timestamps();

            $table->unique('slug', 'idx_unique_page_slug');
            $table->index('is_published', 'idx_is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
