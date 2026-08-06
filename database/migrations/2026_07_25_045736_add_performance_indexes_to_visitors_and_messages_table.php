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
        Schema::table('visitors', function (Blueprint $table) {
            $table->index('visited_at');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('is_read');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropIndex(['visited_at']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['is_read']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['order']);
        });
    }
};
