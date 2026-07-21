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
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('is_free')->default(false)->after('category_id');
        });

        Schema::table('lectures', function (Blueprint $table) {
            $table->boolean('is_free')->default(false)->after('pdf_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('is_free');
        });

        Schema::table('lectures', function (Blueprint $table) {
            $table->dropColumn('is_free');
        });
    }
};
