<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@polani.com'],
            [
                'name' => 'Polani Admin',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'password' => Hash::make('PolaniAdmin@123'),
                'phone' => '1234567890',
                'location' => 'System',
                'about' => 'Single admin account for product management',
            ]
        );
    
        // Ensure Super Admin role exists
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
    
        // Attach role if not already attached
        if (!$user->roles->contains($superAdminRole->id)) {
            $user->roles()->attach($superAdminRole->id);
        }
    
        $this->command->info('Super Admin user created/updated and role assigned.');
    }
}
