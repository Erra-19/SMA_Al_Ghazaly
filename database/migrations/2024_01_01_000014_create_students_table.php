<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
    $table->id('student_id');

    $table->unsignedBigInteger('user_id')->nullable();
    $table->unsignedBigInteger('registration_id')->nullable();

    $table->string('nis', 30)->nullable()->unique();
    $table->string('nisn', 20)->nullable()->unique();
    $table->string('name', 150);

    $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
    $table->string('birth_place', 100)->nullable();
    $table->date('birth_date')->nullable();

    $table->text('address')->nullable();
    $table->string('phone', 30)->nullable();

    $table->string('parent_name', 150)->nullable();
    $table->string('parent_phone', 30)->nullable();

    $table->string('previous_school', 150)->nullable();

    $table->string('academic_year', 20)->nullable(); 
    // contoh: 2026/2027

    $table->string('grade_level', 20)->nullable(); 
    // contoh: X, XI, XII

    $table->string('major', 100)->nullable(); 
    // contoh: IPA, IPS, Bahasa, atau nullable dulu

    $table->enum('status', [
        'active',
        'inactive',
        'graduated',
        'transferred',
        'dropped_out'
    ])->default('active');

    $table->timestamps();

    $table->index('user_id');
    $table->unique('registration_id');
    $table->index('nisn');
    $table->index('academic_year');
    $table->index('grade_level');
    $table->index('major');
    $table->index('status');

    $table->foreign('user_id')
        ->references('id')
        ->on('users')
        ->nullOnDelete();

    $table->foreign('registration_id')
        ->references('registration_id')
        ->on('registrations')
        ->nullOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
