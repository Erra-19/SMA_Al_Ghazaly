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
        Schema::create('medias', function (Blueprint $table) {
            $table->id('media_id');
            $table->unsignedBigInteger('uploader_id')->nullable();
            $table->string('filename', 255);
            $table->string('path', 500);
            $table->string('mime_type', 100);
            $table->integer('size');
            $table->timestamps();

            $table->index('uploader_id', 'idx_uploader_id');
            $table->index('mime_type', 'idx_mime_type');

            $table->foreign('uploader_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};
