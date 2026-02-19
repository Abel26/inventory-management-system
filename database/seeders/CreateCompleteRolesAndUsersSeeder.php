<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateCompleteRolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get all permissions
        $allPermissions = Permission::all();

        // Create or get Super Admin role
        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);
        $superAdmin->syncPermissions($allPermissions);

        // Create Admin role
        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);
        
        // Admin permissions: All except role management
        $adminPermissions = Permission::whereNotIn('name', [
            'create roles',
            'edit roles', 
            'delete roles',
        ])->get();
        $admin->syncPermissions($adminPermissions);

        // Create User role
        $user = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'web',
        ]);
        
        // User permissions: View only for dashboard, assets, and reports
        $userPermissions = Permission::whereIn('name', [
            'view dashboard',
            'view assets',
            'view materials',
            'view tools',
            'view models',
        ])->get();
        $user->syncPermissions($userPermissions);

        // Create Super Admin user if not exists
        $superAdminUser = User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@ebara.com',
                'password' => Hash::make('SuperAdmin123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $superAdminUser->assignRole($superAdmin);

        // Create Admin user
        $adminUser = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin User',
                'email' => 'admin@ebara.com',
                'password' => Hash::make('Admin123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $adminUser->assignRole($admin);

        // Create regular User
        $regularUser = User::firstOrCreate(
            ['username' => 'user'],
            [
                'name' => 'Regular User',
                'email' => 'user@ebara.com',
                'password' => Hash::make('User123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $regularUser->assignRole($user);

        $this->command->info('✅ Roles and Users created successfully:');
        $this->command->info('  - Super Admin (superadmin@ebara.com / SuperAdmin123): All permissions');
        $this->command->info('  - Admin (admin@ebara.com / Admin123): All permissions except role management');
        $this->command->info('  - User (user@ebara.com / User123): View only permissions for dashboard and assets');
    }
}