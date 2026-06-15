<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_messages', function (Blueprint $table) {
            $table->id();

            // Pengirim — admin yang nulis pesan
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();

            // Penerima — null berarti broadcast ke semua admin
            $table->foreignId('receiver_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('subject', 150)->nullable();
            $table->text('body');

            // Status baca dari sisi penerima
            $table->tinyInteger('is_read')->default(0);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['receiver_id', 'is_read'], 'idx_adm_msg_receiver_read');
            $table->index('sender_id', 'idx_adm_msg_sender');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_messages');
    }
};
