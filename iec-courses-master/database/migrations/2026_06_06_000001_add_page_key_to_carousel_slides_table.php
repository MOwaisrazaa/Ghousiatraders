<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carousel_slides', function (Blueprint $table) {
            if (!Schema::hasColumn('carousel_slides', 'page_key')) {
                $table->string('page_key', 50)->default('home')->after('subtitle');
            }
        });
    }

    public function down(): void
    {
        Schema::table('carousel_slides', function (Blueprint $table) {
            if (Schema::hasColumn('carousel_slides', 'page_key')) {
                $table->dropColumn('page_key');
            }
        });
    }
};
