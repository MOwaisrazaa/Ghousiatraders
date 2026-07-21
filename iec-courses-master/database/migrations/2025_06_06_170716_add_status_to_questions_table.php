<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Check if the status column already exists
            if (!Schema::hasColumn('questions', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('content');
            } else {
                // If column exists, modify it to update the enum values
                DB::statement("ALTER TABLE questions MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");

                // Update existing 'answered' status to 'approved'
                DB::statement("UPDATE questions SET status = 'approved' WHERE status = 'answered'");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
