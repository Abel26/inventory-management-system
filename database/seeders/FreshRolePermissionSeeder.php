<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FreshRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Step 1: Clear Permission Cache
        $this->command->info('🔄 Clearing permission cache...');
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->command->info('✅ Permission cache cleared.');

        // Step 2: Truncate all role/permission tables (Fresh Start)
        $this->command->info('🗑️  Truncating role/permission tables...');
        Schema::disableForeignKeyConstraints();

        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();

        Schema::enableForeignKeyConstraints();
        $this->command->info('✅ Tables truncated successfully.');

        // Step 3: Create Permissions (Grouped by Module)
        $this->command->info('📋 Creating permissions...');
        $permissions = [
            // Dashboard Module
            'Dashboard' => [
                'view dashboard',
                'create dashboard',
                'edit dashboard',
                'delete dashboard',
            ],

            // Assets Module
            'Assets' => [
                'view assets',
                'create assets',
                'edit assets',
                'delete assets',
            ],
            'Materials' => [
                'view materials',
                'create materials',
                'edit materials',
                'delete materials',
                'export materials',
            ],
            'Tools' => [
                'view tools',
                'create tools',
                'edit tools',
                'delete tools',
                'export tools',
            ],
            'Models' => [
                'view models',
                'create models',
                'edit models',
                'delete models',
                'export models',
            ],

            // Roles Module
            'Roles' => [
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
            ],
        ];

        $allPermissions = [];
        foreach ($permissions as $module => $modulePermissions) {
            foreach ($modulePermissions as $permissionName) {
                $allPermissions[] = $permissionName;
                Permission::create(['name' => $permissionName]);
            }
        }
        $this->command->info('✅ Created ' . count($allPermissions) . ' permissions.');

        // Step 4: Create Super Admin Role
        $this->command->info('👑 Creating Super Admin role...');
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo($allPermissions);
        $this->command->info('✅ Super Admin role created with all permissions.');

        // Step 5: Assign Super Admin to User
        $this->command->info('👤 Assigning Super Admin role to user...');
        
        // Try to find user by email (priority list)
        $adminEmails = ['abel-natanael@abel', 'admin@ebara.com', 'admin@example.com'];
        $user = null;
        
        foreach ($adminEmails as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $this->command->info("📧 Found user by email: {$email}");
                break;
            }
        }

        // If not found by email, try to find by ID
        if (!$user) {
            $user = User::find(1);
            if ($user) {
                $this->command->info('🔢 Found user by ID: 1');
            }
        }

        // If still not found, get the first user
        if (!$user) {
            $user = User::first();
            if ($user) {
                $this->command->info('🔍 Found first user in database');
            }
        }

        if ($user) {
            $user->assignRole($superAdmin);
            $this->command->info("✅ User '{$user->name}' ({$user->email}) has been assigned as Super Admin.");
        } else {
            $this->command->error('❌ No user found in database. Please create a user first.');
        }

        $this->command->info('🎉 Role & Permission seeding completed!');
    }
}
