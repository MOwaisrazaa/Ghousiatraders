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
        // First, delete duplicate device_ids keeping only the most recent one per user
        DB::statement('
            DELETE FROM user_devices 
            WHERE id NOT IN (
                SELECT MAX(id) 
                FROM (
                    SELECT MAX(id) as id 
                    FROM user_devices 
                    GROUP BY user_id, device_id
                ) as temp
            )
        ');

        // Drop the old unique constraint on device_id
        Schema::table('user_devices', function (Blueprint $table) {
            $table->dropUnique(['device_id']);
        });

        // Add a composite unique constraint on user_id and device_id
        Schema::table('user_devices', function (Blueprint $table) {
            $table->unique(['user_id', 'device_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_devices', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'device_id']);
        });

        Schema::table('user_devices', function (Blueprint $table) {
            $table->unique(['device_id']);
        });
    }
};
