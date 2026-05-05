<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id('album_id');
            $table->string('title', 255);
            $table->string('slug', 280);
            $table->string('cover', 255)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('is_published')->default(0);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();

            $table->unique('slug', 'idx_unique_album_slug');
            $table->index('is_published', 'idx_album_is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
