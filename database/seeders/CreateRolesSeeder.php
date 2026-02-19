<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin role if not exists
        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        // Create Admin role
        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        // Create User role
        $user = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'web',
        ]);

        // Get all permissions
        $allPermissions = Permission::all();

        // Assign all permissions to Super Admin
        $superAdmin->syncPermissions($allPermissions);

        // Assign specific permissions to Admin
        // Admin can do everything except manage roles and users
        $adminPermissions = Permission::whereNotIn('name', [
            'create roles',
            'edit roles',
            'delete roles',
        ])->get();
        $admin->syncPermissions($adminPermissions);

        // Assign limited permissions to User
        // User can only view dashboard, assets, and reports
        $userPermissions = Permission::whereIn('name', [
            'view dashboard',
            'view assets',
            'view materials',
            'view tools',
            'view models',
        ])->get();
        $user->syncPermissions($userPermissions);

        $this->command->info('✅ Roles created successfully:');
        $this->command->info('  - Super Admin: All permissions');
        $this->command->info('  - Admin: All permissions except role management');
        $this->command->info('  - User: View only permissions for dashboard and assets');
    }
}