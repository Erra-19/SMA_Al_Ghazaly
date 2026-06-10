<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $columns = [
                'student_name' => fn () => $table->string('student_name', 150)->nullable(),
                'birth_place' => fn () => $table->string('birth_place', 100)->nullable(),
                'birth_date' => fn () => $table->date('birth_date')->nullable(),
                'gender' => fn () => $table->string('gender', 30)->nullable(),
                'address' => fn () => $table->text('address')->nullable(),
                'phone' => fn () => $table->string('phone', 30)->nullable(),
                'previous_school' => fn () => $table->string('previous_school', 150)->nullable(),
                'parent_name' => fn () => $table->string('parent_name', 150)->nullable(),
                'parent_phone' => fn () => $table->string('parent_phone', 30)->nullable(),
                'parent_job' => fn () => $table->string('parent_job', 100)->nullable(),
                'academic_year' => fn () => $table->string('academic_year', 20)->nullable(),
                'wave' => fn () => $table->string('wave', 50)->nullable(),
                'major_choice' => fn () => $table->string('major_choice', 100)->nullable(),
                'payment_total_amount' => fn () => $table->decimal('payment_total_amount', 12, 2)->default(0),
                'payment_paid_amount' => fn () => $table->decimal('payment_paid_amount', 12, 2)->default(0),
                'payment_remaining_amount' => fn () => $table->decimal('payment_remaining_amount', 12, 2)->default(0),
                'payment_status' => fn () => $table->string('payment_status', 30)->default('unpaid'),
                'payment_method' => fn () => $table->string('payment_method', 30)->default('unpaid'),
                'submitted_at' => fn () => $table->timestamp('submitted_at')->nullable(),
                'verified_at' => fn () => $table->timestamp('verified_at')->nullable(),
                'verified_by' => fn () => $table->unsignedBigInteger('verified_by')->nullable(),
            ];

            foreach ($columns as $column => $definition) {
                if (! Schema::hasColumn('registrations', $column)) {
                    $definition();
                }
            }
        });

        if (Schema::hasColumn('registrations', 'gender')) {
            DB::statement('ALTER TABLE registrations MODIFY gender VARCHAR(30) NULL');
        }

        if (Schema::hasColumn('registrations', 'status')) {
            DB::statement("ALTER TABLE registrations MODIFY status VARCHAR(30) NOT NULL DEFAULT 'draft'");
        }
    }

    public function down(): void
    {
        // Intentionally left blank: this migration normalizes legacy databases.
    }
};
