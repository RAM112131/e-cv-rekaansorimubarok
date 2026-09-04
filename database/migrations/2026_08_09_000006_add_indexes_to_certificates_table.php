<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->index('issuer');
            $table->index('issue_date');
            $table->index('expiration_date');
            $table->index(['user_id', 'issuer']);
            $table->index(['user_id', 'issue_date']);
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropIndex(['issuer']);
            $table->dropIndex(['issue_date']);
            $table->dropIndex(['expiration_date']);
            $table->dropIndex(['user_id', 'issuer']);
            $table->dropIndex(['user_id', 'issue_date']);
        });
    }
};