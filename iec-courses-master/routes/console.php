<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\UserDevice;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('devices:ensure-primary', function () {
    $this->info('Checking users without primary devices...');
    
    $users = User::all();
    $updatedCount = 0;
    
    foreach ($users as $user) {
        $primaryDevice = $user->primaryDevice();
        
        if (!$primaryDevice) {
            // Get the most recent device or create a default one
            $device = $user->devices()->latest('last_login_at')->first();
            
            if ($device) {
                // Update this device to be primary
                $device->update(['is_primary' => true]);
                $this->line("Set primary device for user ID {$user->id} ({$user->email})");
            } else {
                // Create a placeholder device - this will be properly set on next login
                UserDevice::create([
                    'user_id' => $user->id,
                    'device_name' => 'Unknown Device',
                    'browser' => 'Unknown',
                    'platform' => 'Unknown',
                    'device_id' => 'placeholder-' . md5($user->email . '-' . $user->id),
                    'ip_address' => '127.0.0.1',
                    'last_login_at' => now(),
                    'is_primary' => true
                ]);
                $this->line("Created placeholder device for user ID {$user->id} ({$user->email})");
            }
            
            $updatedCount++;
        }
    }
    
    $this->info("Completed! Updated {$updatedCount} users.");
})->purpose('Ensure every user has a primary device');
