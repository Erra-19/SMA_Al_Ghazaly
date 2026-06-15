<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Consolidated: create_students_table
//   + add_missing_columns_to_students_table
//   + add_partial_payments_and_student_registration_guard (unique registration_id)

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('registration_id')->nullable();

            $table->string('nis', 30)->nullable()->unique();
            $table->string('nisn', 20)->nullable()->unique();
            $table->string('nik', 20)->nullable();
            $table->string('name', 150);
            $table->string('photo', 255)->nullable();
            $table->string('email', 100)->nullable();

            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();

            $table->text('address')->nullable();
            $table->string('phone', 30)->nullable();

            $table->string('parent_name', 150)->nullable();
            $table->string('parent_phone', 30)->nullable();

            $table->string('previous_school', 150)->nullable();
            $table->text('notes')->nullable();

            $table->string('academic_year', 20)->nullable();
            $table->string('grade_level', 20)->nullable();
            $table->string('major', 100)->nullable();

            $table->enum('status', [
                'active',
                'inactive',
                'graduated',
                'transferred',
                'dropped_out',
            ])->default('active');

            $table->smallInteger('order')->unsigned()->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index('user_id');
            $table->unique('registration_id');
            $table->index('nisn');
            $table->index('academic_year');
            $table->index('grade_level');
            $table->index('major');
            $table->index('status');
            $table->index('order');
            $table->index('is_active');

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('registration_id')
                ->references('registration_id')
                ->on('registrations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
