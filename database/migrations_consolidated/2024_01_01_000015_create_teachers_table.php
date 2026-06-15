<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Consolidated: create_teachers_table
//   + add_extended_fields_to_teachers_table

return new class extends Migration
{
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

            // Kelompok bidang mengajar: imtak | mipa | social-english | bk-staf
            $table->string('category', 50)->nullable();

            // Riwayat pendidikan: "S2 Fisika Teoretis, ITB"
            $table->string('education', 255)->nullable();

            // Filosofi / moto mengajar
            $table->text('philosophy')->nullable();

            // Lama pengabdian: "11 Tahun Mengabdi"
            $table->string('experience', 100)->nullable();

            // Tag keahlian — JSON: ["Fisika","Robotika","Astronomi"]
            $table->json('tags')->nullable();

            // Flag Kepala Sekolah / Wakasek (tampil di section Direksi)
            $table->tinyInteger('is_leadership')->default(0);

            $table->unsignedSmallInteger('order')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index('is_active', 'idx_teacher_is_active');
            $table->index('order', 'idx_teacher_order');
            $table->index('category', 'idx_teacher_category');
            $table->index('is_leadership', 'idx_teacher_is_leadership');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
