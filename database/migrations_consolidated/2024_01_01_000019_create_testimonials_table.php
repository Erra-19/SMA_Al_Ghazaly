<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Consolidated: create_testimonials_table
//   + add_alumni_fields_to_testimonials_table
//   + add_alumnus_id_to_testimonials_table

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id('testimonial_id');

            // Optional link to alumni record — one-to-one
            $table->unsignedBigInteger('alumnus_id')->nullable();

            $table->string('name', 100);
            $table->string('role', 100)->nullable();

            // Universitas yang diterima: "Institut Teknologi Bandung (ITB)"
            $table->string('university', 150)->nullable();

            // Jurusan: "Teknik Informatika"
            $table->string('major', 150)->nullable();

            // Tahun lulus SMA: untuk label "Alumni 2024"
            $table->year('graduation_year')->nullable();

            $table->text('content');
            $table->string('photo', 255)->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->tinyInteger('is_published')->default(0);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();

            $table->index('is_published', 'idx_testimonial_is_published');
            $table->index('order', 'idx_testimonial_order');
            $table->index('graduation_year', 'idx_testimonial_graduation_year');

            $table->foreign('alumnus_id')
                ->references('alumnus_id')
                ->on('alumni')
                ->nullOnDelete();

            $table->unique('alumnus_id', 'uq_testimonial_alumnus_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
