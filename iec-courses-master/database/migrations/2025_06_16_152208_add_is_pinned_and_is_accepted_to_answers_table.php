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
        Schema::table('answers', function (Blueprint $table) {
            // Add is_pinned column (for featuring answers at the top)
            $table->boolean('is_pinned')->default(false)->after('content');

            // Add is_accepted column (for marking answers as accepted/approved)
            $table->boolean('is_accepted')->default(false)->after('is_pinned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn(['is_pinned', 'is_accepted']);
        });
    }
};
