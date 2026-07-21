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
        // First create categories table if not exists
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->timestamps();
            });
        }

        // Then add the category_id column to courses if it doesn't exist
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'category_id')) {
                $table->foreignId('category_id')->nullable();
            }

            // Add foreign key separately to avoid issues
            if (!Schema::hasColumn('courses', 'category_id_foreign')) {
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        });
    }
};
