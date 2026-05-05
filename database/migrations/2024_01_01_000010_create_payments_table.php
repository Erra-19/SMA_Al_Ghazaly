<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('order_id', 100);
            $table->string('transaction_id', 100)->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('IDR');
            $table->string('payment_type', 50)->nullable();
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('snap_token', 255)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique('order_id', 'idx_unique_order_id');
            $table->index('registration_id', 'idx_payment_registration_id');
            $table->index('user_id', 'idx_payment_user_id');
            $table->index('status', 'idx_payment_status');

            $table->foreign('registration_id')->references('registration_id')->on('registrations')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
