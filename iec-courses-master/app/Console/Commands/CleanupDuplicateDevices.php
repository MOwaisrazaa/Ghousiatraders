<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserDevice;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devices:cleanup-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate device records that violate unique constraints';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of duplicate device records...');

        // Find duplicate device_ids and keep only the most recent one
        $duplicates = DB::table('user_devices')
            ->select('device_id', DB::raw('COUNT(*) as count'))
            ->groupBy('device_id')
            ->having(DB::raw('COUNT(*)'), '>', 1)
            ->get();

        $deletedCount = 0;

        foreach ($duplicates as $duplicate) {
            // Keep the most recent device record, delete others
            $devices = UserDevice::where('device_id', $duplicate->device_id)
                ->orderBy('last_login_at', 'desc')
                ->get();

            // Keep the first (most recent), delete the rest
            for ($i = 1; $i < count($devices); $i++) {
                $devices[$i]->delete();
                $deletedCount++;
            }

            $this->line("Cleaned up device_id: {$duplicate->device_id} (kept 1, deleted " . ($duplicate->count - 1) . ")");
        }

        $this->info("Cleanup complete! Deleted {$deletedCount} duplicate records.");
    }
}
