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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('certificate_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('image')->nullable();
            $table->string('issuer');
            $table->string('credential_id')->nullable();
            $table->date('issue_date');
            $table->date('expiration_date')->nullable();
            $table->text('credential_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('show_on_cv')->default(true);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();

            $table->index(['portfolio_profile_id', 'is_published', 'issue_date']);
            $table->index(['portfolio_profile_id', 'show_on_cv', 'order']);
            $table->index('certificate_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
