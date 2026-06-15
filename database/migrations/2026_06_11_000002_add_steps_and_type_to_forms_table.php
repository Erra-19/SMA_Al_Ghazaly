<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            // Tipe form: ppdb | contact | general
            $table->string('type', 30)->default('general')->after('name');
            // Multi-step definition; jika null, gunakan fields (legacy single-step)
            $table->json('steps')->nullable()->after('fields');
            // Deskripsi / instruksi form
            $table->text('description')->nullable()->after('steps');
        });
    }

    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn(['type', 'steps', 'description']);
        });
    }
};
