<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            // Optional link to alumni record — one-to-one
            $table->unsignedBigInteger('alumnus_id')->nullable()->after('testimonial_id');

            $table->foreign('alumnus_id')
                ->references('alumnus_id')
                ->on('alumni')
                ->nullOnDelete();

            // Unique: each alumnus can link to at most one testimonial
            $table->unique('alumnus_id', 'uq_testimonial_alumnus_id');
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropForeign(['alumnus_id']);
            $table->dropUnique('uq_testimonial_alumnus_id');
            $table->dropColumn('alumnus_id');
        });
    }
};
