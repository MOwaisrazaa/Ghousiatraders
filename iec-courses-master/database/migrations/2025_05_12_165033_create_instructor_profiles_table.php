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
        Schema::create('instructor_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('bio')->nullable();
            $table->string('title')->nullable();
            $table->text('expertise')->nullable()->comment('Comma-separated list of expertise areas');
            $table->text('skills')->nullable()->comment('Comma-separated list of skills');
            $table->string('image_path')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_website')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_profiles');
    }
};
