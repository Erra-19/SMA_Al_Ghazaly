<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'nik')) {
                $table->string('nik', 20)->nullable()->after('nisn');
            }
            if (! Schema::hasColumn('students', 'email')) {
                $table->string('email', 100)->nullable()->after('phone');
            }
            if (! Schema::hasColumn('students', 'photo')) {
                $table->string('photo', 255)->nullable()->after('email');
            }
            if (! Schema::hasColumn('students', 'notes')) {
                $table->text('notes')->nullable()->after('previous_school');
            }
            if (! Schema::hasColumn('students', 'order')) {
                $table->smallInteger('order')->unsigned()->default(0)->index()->after('notes');
            }
            if (! Schema::hasColumn('students', 'is_active')) {
                $table->tinyInteger('is_active')->default(1)->index()->after('order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumnIfExists('nik');
            $table->dropColumnIfExists('email');
            $table->dropColumnIfExists('photo');
            $table->dropColumnIfExists('notes');
            $table->dropColumnIfExists('order');
            $table->dropColumnIfExists('is_active');
        });
    }
};
