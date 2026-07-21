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
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();

            // Brand Section
            $table->string('brand_name')->default('IEC Courses');
            $table->string('brand_tagline')->default('Islamic Economics & Finance');
            $table->text('brand_description')->default('The premier platform for Sharia-compliant education. Empowering professionals and students with ethical financial knowledge worldwide.');

            // Social Media Links
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();

            // Contact Information
            $table->string('address')->default('Dawat-e-Islami, Global Headquarters, Karachi, Pakistan');
            $table->string('email')->default('support@iecdawateislami.com');
            $table->string('phone')->default('+92 (21) 111-25-26-27');

            // Copyright & Footer Text
            $table->string('copyright_name')->default('IEC Dawat-e-Islami');
            $table->string('copyright_url')->default('https://www.iecdawateislami.com');
            $table->text('footer_text')->default('Made with passion for Sharia education.');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};
