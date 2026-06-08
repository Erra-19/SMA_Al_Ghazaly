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
        Schema::create('registration_documents', function (Blueprint $table) {
    $table->id('document_id');
    $table->unsignedBigInteger('registration_id');

    $table->string('document_type', 100);
    $table->string('file_path', 255);
    $table->string('original_name', 255)->nullable();
    $table->string('mime_type', 100)->nullable();
    $table->unsignedBigInteger('file_size')->nullable();

    $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
    $table->text('notes')->nullable();

    $table->timestamp('verified_at')->nullable();
    $table->unsignedBigInteger('verified_by')->nullable();

    $table->timestamps();

    $table->foreign('registration_id')
        ->references('registration_id')
        ->on('registrations')
        ->cascadeOnDelete();

    $table->foreign('verified_by')
        ->references('id')
        ->on('users')
        ->nullOnDelete();

    $table->index('registration_id');
    $table->index('document_type');
    $table->index('status');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_documents');
    }
};
