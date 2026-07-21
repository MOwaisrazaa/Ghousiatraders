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
        Schema::table('footer_settings', function (Blueprint $table) {
            $table->string('instagram_url')->nullable()->after('brand_description');
            $table->string('tiktok_url')->nullable()->after('instagram_url');
            $table->dropColumn(['twitter_url', 'linkedin_url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('footer_settings', function (Blueprint $table) {
            $table->dropColumn(['instagram_url', 'tiktok_url']);
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
        });
    }
};
