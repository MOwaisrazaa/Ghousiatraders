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
        Schema::table('lectures', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['course_id']);
            
            // Make course_id nullable
            $table->unsignedBigInteger('course_id')->nullable()->change();
            
            // Re-add the foreign key constraint with nullable support
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['course_id']);
            
            // Make course_id required again (but first delete any standalone lectures)
            DB::table('lectures')->whereNull('course_id')->delete();
            $table->unsignedBigInteger('course_id')->nullable(false)->change();
            
            // Re-add the original foreign key constraint
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }
};