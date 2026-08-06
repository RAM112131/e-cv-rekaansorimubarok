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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->string('headline')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('headline');
            $table->string('role')->default('admin')->after('bio');

            // Multi-Factor autentication bawaan FilamentV4
            $table->text('app_authentication_secret')->nullable()->after('role');
            $table->text('app_authentication_recovery_codes')->nullable()->after('app_authentication_secret');
            $table->boolean('has_email_authentication')->default(false)->after('app_authentication_recovery_codes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'headline',
                'bio',
                'role',
                'app_authentication_secret',
                'app_authentication_recovery_codes',
                'has_email_authentication',
            ]);
        });
    }
};
