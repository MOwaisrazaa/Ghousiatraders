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
        // Drop the table if it exists to recreate it correctly
        if (Schema::hasTable('role_user')) {
            // Backup existing data
            $data = DB::table('role_user')->get();
            Schema::drop('role_user');

            // Log info about what we're doing
            \Log::info('Dropping and recreating role_user table. Found ' . count($data) . ' existing records.');
        }

        // Create the table with the correct structure
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');

            // Set primary key
            $table->primary(['user_id', 'role_id']);

            // Set foreign keys
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onDelete('cascade');
        });

        // Log completion
        \Log::info('Successfully created role_user table');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
