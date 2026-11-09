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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->string('category')->nullable();
            $table->json('tech_stack')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('duration', 100)->nullable();
            $table->string('role', 150)->nullable();
            $table->string('client', 150)->nullable();
            $table->json('features')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->text('project_url')->nullable();
            $table->text('repo_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['portfolio_profile_id', 'slug']);
            $table->index(['portfolio_profile_id', 'is_published', 'order']);
            $table->index(['portfolio_profile_id', 'category', 'is_published']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
