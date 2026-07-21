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
        // Add YouTube URL to lecture ID 1 for testing
        DB::table('lectures')
            ->where('id', 1)
            ->update([
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'updated_at' => now()
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove YouTube URL from lecture ID 1
        DB::table('lectures')
            ->where('id', 1)
            ->update([
                'youtube_url' => null,
                'updated_at' => now()
            ]);
    }
};
