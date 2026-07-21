<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First check if the admin role exists, if not create it
        $adminRole = Role::where('name', 'Admin')->first();
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'Admin']);
        }

        // Create the admin user
        $user = User::where('email', 'talibebaqi@gmail.com')->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Bilal',
                'email' => 'talibebaqi@gmail.com',
                'email_verified_at' => now(), // Auto-verify email for admin
                'phone_verified_at' => now(), // Auto-verify phone for admin
                'phone' => '+923152771063',
                'password' => Hash::make('92Bil@l26'),
                'about' => 'Admin user created via seeder',
            ]);
        }

        // Attach the admin role to the user
        if (!$user->roles->contains($adminRole->id)) {
            $user->roles()->attach($adminRole->id);
        }

        // Create a default admin user for testing
        $defaultAdmin = User::where('email', 'admin@example.com')->first();

        if (!$defaultAdmin) {
            $defaultAdmin = User::create([
                'name' => 'Default Admin',
                'email' => 'admin@example.com',
                'email_verified_at' => now(), // Auto-verify email for admin
                'phone_verified_at' => now(), // Auto-verify phone for admin
                'password' => Hash::make('Admin123!'),
                'phone' => '1234567890',
                'about' => 'Default admin user created via seeder',
            ]);
        }

        // Attach the admin role to the default admin user
        if (!$defaultAdmin->roles->contains($adminRole->id)) {
            $defaultAdmin->roles()->attach($adminRole->id);
        }

        $this->command->info('Admin users created successfully!');
    }
}
