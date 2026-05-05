<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('album_medias', function (Blueprint $table) {
            $table->unsignedBigInteger('album_id');
            $table->unsignedBigInteger('media_id');
            $table->unsignedSmallInteger('order')->default(0);

            $table->primary(['album_id', 'media_id']);

            $table->foreign('album_id')->references('album_id')->on('albums')->cascadeOnDelete();
            $table->foreign('media_id')->references('media_id')->on('medias')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('album_medias');
    }
};
