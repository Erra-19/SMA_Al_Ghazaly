<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id('setting_id');
            $table->string('key', 100);
            $table->text('value')->nullable();
            $table->string('group', 50)->nullable();

            $table->unique('key', 'idx_unique_setting_key');
            $table->index('group', 'idx_setting_group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
