<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Kelompok bidang mengajar — untuk filter tab di halaman Pengajar
            // nilai: imtak | mipa | social-english | bk-staf
            $table->string('category', 50)->nullable()->after('is_active');

            // Riwayat pendidikan: "S2 Fisika Teoretis, ITB"
            $table->string('education', 255)->nullable()->after('category');

            // Filsafat / moto mengajar
            $table->text('philosophy')->nullable()->after('education');

            // Lama pengabdian: "11 Tahun Mengabdi"
            $table->string('experience', 100)->nullable()->after('philosophy');

            // Tag keahlian — disimpan JSON: ["Fisika","Robotika","Astronomi"]
            $table->json('tags')->nullable()->after('experience');

            // Flag untuk Kepala Sekolah / Wakasek (tampil di section Direksi)
            $table->tinyInteger('is_leadership')->default(0)->after('tags');

            $table->index('category', 'idx_teacher_category');
            $table->index('is_leadership', 'idx_teacher_is_leadership');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropIndex('idx_teacher_category');
            $table->dropIndex('idx_teacher_is_leadership');
            $table->dropColumn(['category', 'education', 'philosophy', 'experience', 'tags', 'is_leadership']);
        });
    }
};
