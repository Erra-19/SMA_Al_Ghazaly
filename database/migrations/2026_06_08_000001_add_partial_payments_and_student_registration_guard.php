<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'paid_amount')) {
                $table->decimal('paid_amount', 12, 2)->default(0)->after('amount');
            }
        });

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY status ENUM('pending', 'partial', 'paid', 'failed', 'expired', 'refunded') NOT NULL DEFAULT 'pending'");
        }

        if ($driver !== 'sqlite') {
            try {
                Schema::table('students', function (Blueprint $table) {
                    $table->dropIndex(['registration_id']);
                });
            } catch (Throwable) {
                //
            }

            try {
                Schema::table('students', function (Blueprint $table) {
                    $table->unique('registration_id');
                });
            } catch (Throwable) {
                //
            }
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE payments MODIFY status ENUM('pending', 'paid', 'failed', 'expired', 'refunded') NOT NULL DEFAULT 'pending'");
        }

        if ($driver !== 'sqlite') {
            try {
                Schema::table('students', function (Blueprint $table) {
                    $table->dropUnique(['registration_id']);
                });
            } catch (Throwable) {
                //
            }

            try {
                Schema::table('students', function (Blueprint $table) {
                    $table->index('registration_id');
                });
            } catch (Throwable) {
                //
            }
        }

        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
        });
    }
};
