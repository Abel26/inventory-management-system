<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Super Admin role
        $superAdminRole = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);

        // Create Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@ebara.co.id'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email' => 'admin@ebara.com',
                'password' => Hash::make('Sup3r4dm1n'),
                'email_verified_at' => now(),
            ]
        );

        // Assign Super Admin role to the user
        $superAdmin->assignRole($superAdminRole);

        $this->command->info('✓ Super Admin role created');
        $this->command->info('✓ Super Admin user created (username: superadmin, password: Sup3r4dm1n)');
    }
}
