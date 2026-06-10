<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Nama universitas yang diterima: "Institut Teknologi Bandung (ITB)"
            $table->string('university', 150)->nullable()->after('role');

            // Jurusan: "Teknik Informatika"
            $table->string('major', 150)->nullable()->after('university');

            // Tahun lulus SMA: untuk label "Alumni 2024"
            $table->year('graduation_year')->nullable()->after('major');

            $table->index('graduation_year', 'idx_testimonial_graduation_year');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex('idx_testimonial_graduation_year');
            $table->dropColumn(['university', 'major', 'graduation_year']);
        });
    }
};
