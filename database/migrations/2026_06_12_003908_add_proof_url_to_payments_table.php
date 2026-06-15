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
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'proof_url')) {
                $table->string('proof_url')->nullable()->after('snap_token');
            }
            if (! Schema::hasColumn('payments', 'rejected_reason')) {
                $table->string('rejected_reason')->nullable()->after('proof_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            foreach (['proof_url', 'rejected_reason'] as $col) {
                if (Schema::hasColumn('payments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
