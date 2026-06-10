<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'type')) {
                $table->enum('type', ['news', 'article', 'event'])->default('news');
            }

            if (! Schema::hasColumn('posts', 'summary')) {
                $table->text('summary')->nullable();
            }

            if (! Schema::hasColumn('posts', 'category')) {
                $table->string('category', 100)->nullable();
            }

            if (! Schema::hasColumn('posts', 'post_status')) {
                $table->string('post_status', 50)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('idx_post_category');
            $table->dropIndex('idx_post_status');
            $table->dropColumn(['summary', 'category', 'post_status']);
        });
    }
};
