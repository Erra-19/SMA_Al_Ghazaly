<?php

use App\Models\Registration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('registrations', 'payment_total_amount')) {
                $table->decimal('payment_total_amount', 12, 2)->default(0)->after('major_choice');
            }

            if (! Schema::hasColumn('registrations', 'payment_paid_amount')) {
                $table->decimal('payment_paid_amount', 12, 2)->default(0)->after('payment_total_amount');
            }

            if (! Schema::hasColumn('registrations', 'payment_remaining_amount')) {
                $table->decimal('payment_remaining_amount', 12, 2)->default(0)->after('payment_paid_amount');
            }

            if (! Schema::hasColumn('registrations', 'payment_status')) {
                $table->string('payment_status', 30)->default('unpaid')->after('payment_remaining_amount');
            }

            if (! Schema::hasColumn('registrations', 'payment_method')) {
                $table->string('payment_method', 30)->default('unpaid')->after('payment_status');
            }
        });

        Registration::with('payments')->chunkById(100, function ($registrations) {
            $registrations->each->syncPaymentSummary();
        }, 'registration_id');
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            foreach ([
                'payment_total_amount',
                'payment_paid_amount',
                'payment_remaining_amount',
                'payment_status',
                'payment_method',
            ] as $column) {
                if (Schema::hasColumn('registrations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
