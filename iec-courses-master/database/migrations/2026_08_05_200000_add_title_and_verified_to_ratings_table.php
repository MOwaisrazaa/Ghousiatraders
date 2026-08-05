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
        Schema::table('ratings', function (Blueprint $table) {
            if (!Schema::hasColumn('ratings', 'title')) {
                $table->string('title')->nullable()->after('rating');
            }
            if (!Schema::hasColumn('ratings', 'is_verified_purchase')) {
                $table->boolean('is_verified_purchase')->default(false)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            if (Schema::hasColumn('ratings', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('ratings', 'is_verified_purchase')) {
                $table->dropColumn('is_verified_purchase');
            }
        });
    }
};
