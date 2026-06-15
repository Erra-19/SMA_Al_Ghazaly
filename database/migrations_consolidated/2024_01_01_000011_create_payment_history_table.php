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
        Schema::create('payment_histories', function (Blueprint $table) {
		$table->id('payment_history_id');
		$table->unsignedBigInteger('payment_id');
$table->string('order_id', 100)->nullable();
$table->string('transaction_id', 100)->nullable();

		$table->string('old_status', 50)->nullable();
		$table->string('new_status', 50);
		$table->string('event_type', 100)->nullable();
		$table->json('payload')->nullable();

		$table->timestamps();

		$table->foreign('payment_id')->references('payment_id')->on('payments')->cascadeOnDelete();

		$table->index('payment_id');
$table->index('order_id');
    $table->index('transaction_id');
		$table->index('new_status');
	});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
