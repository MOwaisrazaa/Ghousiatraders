<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        $modules = [
            'orders',
            'products',
            'courses',
            'categories',
            'store_settings',
            'settings',
            'users',
            'roles',
            'support',
            'reports',
            'reviews',
            'carousel',
            'blogs',
            'pages',
            'faqs',
        ];

        $actions = ['view', 'create', 'edit', 'delete', 'manage', 'export'];

        foreach ($modules as $module) {
            // Module level permission
            RolePermission::updateOrCreate(
                [
                    'role_id' => $adminRole->id,
                    'permission' => $module,
                ],
                [
                    'is_allowed' => true,
                ]
            );

            // Action level permissions
            foreach ($actions as $action) {
                RolePermission::updateOrCreate(
                    [
                        'role_id' => $adminRole->id,
                        'permission' => "{$module}.{$action}",
                    ],
                    [
                        'is_allowed' => true,
                    ]
                );
            }
        }
    }
}
