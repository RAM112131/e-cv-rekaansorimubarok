<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->index('type');
            $table->index('is_current');
            $table->index('company');
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'is_current']);
        });
    }

    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['is_current']);
            $table->dropIndex(['company']);
            $table->dropIndex(['user_id', 'type']);
            $table->dropIndex(['user_id', 'is_current']);
        });
    }
};