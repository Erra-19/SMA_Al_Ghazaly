<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category_name', 100);
            $table->string('slug', 120);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            $table->unique('slug', 'idx_unique_category_slug');
            $table->index('parent_id', 'idx_parent_id');

            $table->foreign('parent_id')->references('category_id')->on('categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
