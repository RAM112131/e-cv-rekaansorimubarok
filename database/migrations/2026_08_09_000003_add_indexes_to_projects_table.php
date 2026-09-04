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
        Schema::table('projects', function (Blueprint $table) {
            $table->index('is_published');
            $table->index('is_featured');
            $table->index('category');
            $table->index(['user_id', 'is_published']);
            $table->index(['user_id', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['is_published']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['category']);
            $table->dropIndex(['user_id', 'is_published']);
            $table->dropIndex(['user_id', 'is_featured']);
        });
    }
};