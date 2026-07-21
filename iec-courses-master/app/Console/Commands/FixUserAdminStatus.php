<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class FixUserAdminStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:fix-admin-status {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix admin status for a specific user';

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
        
        $this->info("User found: {$user->name} ({$user->email})");
        
        // Check current roles
        $currentRoles = $user->roles->pluck('name')->toArray();
        $this->info("Current roles: " . implode(', ', $currentRoles));
        
        // Check admin status
        $this->info("Is Admin: " . ($user->isAdmin() ? 'Yes' : 'No'));
        $this->info("Is Super Admin: " . ($user->isSuperAdmin() ? 'Yes' : 'No'));
        
        // Get or create Admin role
        $adminRole = Role::where('name', 'Admin')->first();
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'Admin']);
            $this->info("Created Admin role");
        }
        
        // Attach admin role if not already attached
        if (!$user->roles->contains($adminRole->id)) {
            $user->roles()->attach($adminRole->id);
            $this->info("Attached Admin role to user");
        } else {
            $this->info("User already has Admin role");
        }
        
        // Check devices
        $devices = $user->devices;
        $this->info("User has {$devices->count()} devices:");
        
        foreach ($devices as $device) {
            $this->info("- Device: {$device->device_name} | Type: {$device->device_type} | IP: {$device->ip_address} | Primary: " . ($device->is_primary ? 'Yes' : 'No'));
        }
        
        // Clear any device restrictions for admin
        if ($user->isAdmin()) {
            $this->info("User is admin - device restrictions should be bypassed");
        }
        
        $this->info("Admin status fix completed!");
        
        return 0;
    }
}
