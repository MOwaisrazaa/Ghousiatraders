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
        if (Schema::hasTable('homepage_sections')) {
            Schema::table('homepage_sections', function (Blueprint $table) {
                $table->string('bg_theme')->default('default')->after('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('homepage_sections')) {
            Schema::table('homepage_sections', function (Blueprint $table) {
                $table->dropColumn('bg_theme');
            });
        }
    }
};
