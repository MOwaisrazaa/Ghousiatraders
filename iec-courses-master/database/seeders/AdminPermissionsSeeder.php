<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\AdminPermission;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all admin users (not super admins)
        $adminRoleId = Role::where('name', 'Admin')->first()->id;
        $adminUsers = User::whereHas('roles', function($query) use ($adminRoleId) {
            $query->where('role_id', $adminRoleId);
        })->get();

        // Define available pages
        $availablePages = [
            'courses',
            'lectures',
            'users',
            'coupons',
            'orders',
            'questions',
            'roles',
            'payment_methods',
        ];

        // For each admin user, create permissions (all disabled by default)
        foreach ($adminUsers as $adminUser) {
            foreach ($availablePages as $page) {
                AdminPermission::updateOrCreate(
                    [
                        'admin_user_id' => $adminUser->id,
                        'page' => $page
                    ],
                    [
                        'is_allowed' => false
                    ]
                );
            }
        }

        $this->command->info('Admin permissions have been seeded.');
    }
}
