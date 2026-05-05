<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->id('media_id');
            $table->unsignedBigInteger('uploader_id');
            $table->string('filename', 255);
            $table->string('path', 500);
            $table->string('mime_type', 100);
            $table->integer('size');
            $table->timestamp('created_at')->useCurrent();

            $table->index('uploader_id', 'idx_uploader_id');
            $table->index('mime_type', 'idx_mime_type');

            $table->foreign('uploader_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};
