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
            if (!Schema::hasColumn('ratings', 'status')) {
                $table->string('status', 20)->default('pending')->after('show_publicly'); // approved, pending, rejected
            }
            if (!Schema::hasColumn('ratings', 'moderation_note')) {
                $table->text('moderation_note')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'moderation_note',
            ]);
        });
    }
};
