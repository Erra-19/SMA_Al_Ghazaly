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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id('submission_id');
            $table->unsignedBigInteger('form_id');
            $table->json('data');
            $table->string('submitter_ip', 45)->nullable();
            $table->string('submitter_email', 100)->nullable();
            $table->tinyInteger('is_read')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('form_id', 'idx_form_id');
            $table->index('is_read', 'idx_is_read');
            $table->index('created_at', 'idx_submission_created_at');

            $table->foreign('form_id')->references('form_id')->on('forms')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
