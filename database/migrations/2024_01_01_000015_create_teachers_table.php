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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id('teacher_id');
            $table->string('nip', 50)->nullable()->unique();
            $table->string('name', 100);
            $table->string('photo', 255)->nullable();
            $table->string('position', 100);
            $table->string('subject', 100)->nullable();
			$table->string('phone', 30)->nullable();
			$table->string('email', 100)->nullable();
            $table->text('bio')->nullable();
            $table->unsignedSmallInteger('order')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index('is_active', 'idx_teacher_is_active');
            $table->index('order', 'idx_teacher_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
