<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->id('calendar_id');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            // Ujian, Libur, Kegiatan, Akademik, Ekstrakurikuler, dll
            $table->string('category', 50)->nullable()->default('Akademik');
            // green | blue | red | amber | purple
            $table->string('color', 20)->nullable()->default('green');
            $table->string('academic_year', 20)->nullable();
            $table->tinyInteger('is_published')->default(1);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();

            $table->index('start_date');
            $table->index(['is_published', 'start_date']);
            $table->index('academic_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_calendars');
    }
};
