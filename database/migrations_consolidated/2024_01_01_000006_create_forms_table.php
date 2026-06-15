<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Consolidated: create_forms_table
//   + add_steps_and_type_to_forms_table

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id('form_id');
            $table->string('name', 150);

            // Tipe form: ppdb | contact | general
            $table->string('type', 30)->default('general');

            $table->string('slug', 120)->unique('idx_unique_form_slug');

            // Fields untuk single-step form (legacy)
            $table->json('fields');

            // Multi-step definition; jika null, gunakan fields
            $table->json('steps')->nullable();

            // Deskripsi / instruksi form
            $table->text('description')->nullable();

            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
