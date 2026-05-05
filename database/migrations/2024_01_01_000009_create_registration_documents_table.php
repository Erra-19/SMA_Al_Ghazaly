<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id');
            $table->string('document_type', 50);
            $table->unsignedBigInteger('media_id');
            $table->timestamp('uploaded_at')->useCurrent();

            $table->index('registration_id', 'idx_registration_id');
            $table->index('media_id', 'idx_media_id');

            $table->foreign('registration_id')->references('registration_id')->on('registrations')->cascadeOnDelete();
            $table->foreign('media_id')->references('media_id')->on('medias')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_documents');
    }
};
