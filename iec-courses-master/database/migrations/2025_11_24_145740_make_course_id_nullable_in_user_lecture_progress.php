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
        Schema::table('user_lecture_progress', function (Blueprint $table) {
            // Make course_id nullable to support standalone lectures
            $table->unsignedBigInteger('course_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_lecture_progress', function (Blueprint $table) {
            // Revert course_id to not nullable
            $table->unsignedBigInteger('course_id')->nullable(false)->change();
        });
    }
};
