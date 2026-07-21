<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First check if the user role exists, if not create it
        $userRole = Role::where('name', 'User')->first();
        if (!$userRole) {
            $userRole = Role::create(['name' => 'User']);
        }

        // Create some regular users
        $users = [
            [
                'name' => 'Test User 1',
                'email' => 'user1@example.com', // fR8!zN#41wQ@kU7$Lp2Vx%
                'password' => 'User123!',
                'phone' => '1234567891',
            ],
            [
                'name' => 'Test User 2',
                'email' => 'user2@example.com',
                'password' => 'User123!',
                'phone' => '1234567892',
            ],
            [
                'name' => 'Test User 3',
                'email' => 'user3@example.com',
                'password' => 'User123!',
                'phone' => '1234567893',
            ]
        ];

        foreach ($users as $userData) {
            $user = User::where('email', $userData['email'])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'phone' => $userData['phone'],
                    'about' => 'Regular user created via seeder',
                ]);
            }

            // Attach the user role to the user
            if (!$user->roles->contains($userRole->id)) {
                $user->roles()->attach($userRole->id);
            }
        }

        $this->command->info('Regular users created successfully!');
    }
}
