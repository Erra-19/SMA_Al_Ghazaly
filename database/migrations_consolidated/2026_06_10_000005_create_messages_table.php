<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // Data pengirim
            $table->string('name', 100);
            $table->string('email', 150);
            $table->string('phone', 30)->nullable();

            // Isi pesan
            // subject: 'informasi_umum' | 'ppdb' | 'akademik' | 'keuangan' | 'lainnya'
            $table->string('subject', 100)->nullable()->default('informasi_umum');
            $table->text('message');

            // Status baca — untuk badge notifikasi di admin panel
            $table->tinyInteger('is_read')->default(0);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index('is_read', 'idx_message_is_read');
            $table->index('created_at', 'idx_message_created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
