<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id('alumni_id');
            $table->string('name', 100);
            $table->year('graduation_year');
            $table->string('photo', 255)->nullable();
            $table->string('current_institution', 150)->nullable();
            $table->string('major', 100)->nullable();
            $table->text('achievement')->nullable();
            $table->tinyInteger('is_published')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('graduation_year', 'idx_alumni_graduation_year');
            $table->index('is_published', 'idx_alumni_is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
