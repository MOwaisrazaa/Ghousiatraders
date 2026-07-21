<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration safely handles the questions status column conflict
     * that occurs when the same column is added twice in different migrations.
     */
    public function up(): void
    {
        // Check if the questions table exists
        if (!Schema::hasTable('questions')) {
            return;
        }

        // Check if status column exists
        if (!Schema::hasColumn('questions', 'status')) {
            // If status column doesn't exist, add it with the correct enum values
            Schema::table('questions', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('content');
            });
        } else {
            // If status column exists, update it to have the correct enum values
            try {
                // First, update any 'answered' status to 'approved' to match new enum
                DB::statement("UPDATE questions SET status = 'approved' WHERE status = 'answered'");

                // Then modify the column to have the correct enum values
                DB::statement("ALTER TABLE questions MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");

                echo "Successfully updated questions status column enum values.\n";
            } catch (\Exception $e) {
                // If the column already has the correct enum values, this will fail silently
                echo "Status column already has correct enum values or update failed: " . $e->getMessage() . "\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is designed to be safe and shouldn't be rolled back
        // as it fixes data consistency issues. If rollback is absolutely necessary,
        // manual intervention would be required.
        echo "This migration fixes data consistency and should not be rolled back.\n";
    }
};
