<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use App\Models\UserDevice;
use Illuminate\Console\Command;

class FixUserDeviceIssues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:fix-device-issues {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix device and admin issues for a specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }
        
        $this->info("Fixing issues for user: {$user->name} ({$user->email})");
        
        // 1. Fix Admin Role
        $this->info("1. Checking admin role...");
        $adminRole = Role::where('name', 'Admin')->first();
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'Admin']);
            $this->info("Created Admin role");
        }
        
        if (!$user->roles->contains($adminRole->id)) {
            $user->roles()->attach($adminRole->id);
            $this->info("✓ Attached Admin role to user");
        } else {
            $this->info("✓ User already has Admin role");
        }
        
        // 2. Clean up device records
        $this->info("2. Cleaning up device records...");
        $devices = $user->devices;
        $this->info("Found {$devices->count()} device records");
        
        if ($devices->count() > 3) {
            // Keep only the 3 most recent devices
            $devicesToKeep = $devices->sortByDesc('last_login_at')->take(3);
            $devicesToDelete = $devices->whereNotIn('id', $devicesToKeep->pluck('id'));
            
            foreach ($devicesToDelete as $device) {
                $this->info("Deleting old device: {$device->device_name} ({$device->ip_address})");
                $device->delete();
            }
        }
        
        // 3. Ensure there's a primary device
        $this->info("3. Checking primary device...");
        $primaryDevice = $user->devices()->where('is_primary', true)->first();
        
        if (!$primaryDevice) {
            $latestDevice = $user->devices()->latest('last_login_at')->first();
            if ($latestDevice) {
                $latestDevice->update(['is_primary' => true]);
                $this->info("✓ Set latest device as primary: {$latestDevice->device_name}");
            }
        } else {
            $this->info("✓ Primary device exists: {$primaryDevice->device_name}");
        }
        
        // 4. Update device types to be consistent
        $this->info("4. Standardizing device types...");
        foreach ($user->devices as $device) {
            if (empty($device->device_type)) {
                // Set a default device type based on device name
                $deviceType = 'Desktop'; // Default to Desktop
                if (stripos($device->device_name, 'mobile') !== false || 
                    stripos($device->device_name, 'android') !== false || 
                    stripos($device->device_name, 'iphone') !== false) {
                    $deviceType = 'Mobile';
                }
                
                $device->update(['device_type' => $deviceType]);
                $this->info("Updated device type for {$device->device_name} to {$deviceType}");
            }
        }
        
        // 5. Show final status
        $this->info("5. Final status:");
        $this->info("✓ Admin status: " . ($user->isAdmin() ? 'Yes' : 'No'));
        $this->info("✓ Device count: " . $user->devices->count());
        $this->info("✓ Unique IPs: " . $user->devices->pluck('ip_address')->unique()->count());
        
        $this->info("All issues fixed! User should now be able to login without device restrictions.");
        
        return 0;
    }
}
