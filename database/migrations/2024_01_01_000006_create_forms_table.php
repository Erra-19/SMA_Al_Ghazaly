<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id('form_id');
            $table->string('name', 150);
            $table->string('slug', 120);
            $table->json('fields');
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->unique('slug', 'idx_unique_form_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
