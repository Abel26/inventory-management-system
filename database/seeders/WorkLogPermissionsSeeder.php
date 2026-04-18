<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class WorkLogPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'View all work logs',
            'View own work logs',
            'Create work logs',
            'Update own work logs',
            'Update any work logs',
            'Delete own work logs',
            'Delete any work logs',
            'Export work logs',
            'View work logs statistics',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
            ]);
        }

        // Assign permissions to roles
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $admin = Role::where('name', 'Admin')->first();
        $user = Role::where('name', 'User')->first();

        if ($superAdmin) {
            // Super Admin: All permissions
            $superAdmin->syncPermissions($permissions);
        }

        if ($admin) {
            // Admin: All permissions except delete_any
            $adminPermissions = array_filter($permissions, function ($name) {
                return $name !== 'Delete any work logs';
            });
            $admin->syncPermissions($adminPermissions);
        }

        if ($user) {
            // Regular User: view_own, create, update_own, delete_own
            $userPermissions = [
                'View own work logs',
                'Create work logs',
                'Update own work logs',
                'Delete own work logs',
            ];
            $user->syncPermissions($userPermissions);
        }

        $this->command->info('Work log permissions seeded successfully.');
    }
}
