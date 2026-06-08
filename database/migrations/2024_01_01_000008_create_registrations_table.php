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
        Schema::create('registrations', function (Blueprint $table) {
    $table->id('registration_id');
    $table->unsignedBigInteger('user_id')->nullable();

    $table->string('registration_number', 50)->unique();

    $table->string('student_name', 150);
    $table->string('nisn', 20)->nullable();
    $table->string('birth_place', 100)->nullable();
    $table->date('birth_date')->nullable();
    $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
    $table->text('address')->nullable();
    $table->string('phone', 30)->nullable();

    $table->string('previous_school', 150)->nullable();

    $table->string('parent_name', 150)->nullable();
    $table->string('parent_phone', 30)->nullable();
    $table->string('parent_job', 100)->nullable();

    $table->string('academic_year', 20)->nullable();
    $table->string('wave', 50)->nullable();
    $table->string('major_choice', 100)->nullable();

    $table->decimal('payment_total_amount', 12, 2)->default(0);
    $table->decimal('payment_paid_amount', 12, 2)->default(0);
    $table->decimal('payment_remaining_amount', 12, 2)->default(0);
    $table->string('payment_status', 30)->default('unpaid');
    $table->string('payment_method', 30)->default('unpaid');

    $table->enum('status', [
        'draft',
        'submitted',
        'document_review',
        'verified',
        'accepted',
        'rejected'
    ])->default('draft');

    $table->timestamp('submitted_at')->nullable();
    $table->timestamp('verified_at')->nullable();
    $table->unsignedBigInteger('verified_by')->nullable();

    $table->timestamps();

    $table->index('user_id');
    $table->index('nisn');
    $table->index('status');
    $table->index('payment_status');
    $table->index('payment_method');
    $table->index('academic_year');
    $table->index('wave');
    $table->index('verified_by');

    $table->foreign('user_id')
        ->references('id')
        ->on('users')
        ->nullOnDelete();

    $table->foreign('verified_by')
        ->references('id')
        ->on('users')
        ->nullOnDelete();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
