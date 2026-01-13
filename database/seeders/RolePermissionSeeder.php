<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions grouped by module with exact naming convention
        $permissions = [
            // Dashboard Module
            'Dashboard' => [
                'view dashboard',
                'create dashboard',
                'edit dashboard',
                'delete dashboard',
            ],
            
            // Products Module
            'Products' => [
                'view products',
                'create products',
                'edit products',
                'delete products',
            ],
            
            // Assets Module
            'Assets' => [
                'view assets',
                'create assets',
                'edit assets',
                'delete assets',
                'view materials',
                'create materials',
                'edit materials',
                'delete materials',
                'view tools',
                'create tools',
                'edit tools',
                'delete tools',
                'view models',
                'create models',
                'edit models',
                'delete models',
            ],
            
            // Roles Module
            'Roles' => [
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
            ],
        ];

        // Create permissions with exact naming convention
        foreach ($permissions as $module => $modulePermissions) {
            foreach ($modulePermissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }
        }

        // Create Super Admin role with all permissions
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        // Assign all permissions to Super Admin
        $allPermissions = Permission::all();
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        // Assign all permissions to Super Admin
        $superAdminRole->syncPermissions($allPermissions);

        $this->command->info('Role & Permission Management seeded successfully.');
    }
}
