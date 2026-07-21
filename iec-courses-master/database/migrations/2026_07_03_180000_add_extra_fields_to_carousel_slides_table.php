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
        Schema::table('carousel_slides', function (Blueprint $table) {
            if (!Schema::hasColumn('carousel_slides', 'eyebrow')) {
                $table->string('eyebrow', 100)->nullable()->after('page_key');
            }
            if (!Schema::hasColumn('carousel_slides', 'secondary_cta_text')) {
                $table->string('secondary_cta_text', 50)->nullable()->after('cta_url');
            }
            if (!Schema::hasColumn('carousel_slides', 'secondary_cta_url')) {
                $table->string('secondary_cta_url', 255)->nullable()->after('secondary_cta_text');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carousel_slides', function (Blueprint $table) {
            if (Schema::hasColumn('carousel_slides', 'eyebrow')) {
                $table->dropColumn('eyebrow');
            }
            if (Schema::hasColumn('carousel_slides', 'secondary_cta_text')) {
                $table->dropColumn('secondary_cta_text');
            }
            if (Schema::hasColumn('carousel_slides', 'secondary_cta_url')) {
                $table->dropColumn('secondary_cta_url');
            }
        });
    }
};
