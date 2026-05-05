<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id('registration_id');
            $table->string('registration_number', 30);
            $table->string('full_name', 100);
            $table->date('birth_date');
            $table->string('birth_place', 100);
            $table->enum('gender', ['L', 'P']);
            $table->text('address');
            $table->string('phone', 20);
            $table->string('parent_name', 100);
            $table->string('parent_phone', 20);
            $table->string('previous_school', 150);
            $table->string('academic_year', 10);
            $table->enum('status', ['pending', 'verified', 'accepted', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('registration_number', 'idx_registration_number');
            $table->index('status', 'idx_status');
            $table->index('academic_year', 'idx_academic_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
