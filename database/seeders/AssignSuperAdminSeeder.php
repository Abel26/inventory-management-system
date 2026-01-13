<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AssignSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the Super Admin role
        $superAdminRole = Role::where('name', 'Super Admin')->first();

        if (!$superAdminRole) {
            $this->command->error('Role "Super Admin" not found. Please run RolePermissionSeeder first.');
            return;
        }

        // Find the first user (admin) by ID or email
        // Priority: Try to find by email first, then by ID
        $adminEmail = config('app.admin_email', 'admin@ebara.com');
        $user = User::where('email', $adminEmail)->first();

        if (!$user) {
            // If not found by email, try to find the first user
            $user = User::first();
        }

        if (!$user) {
            $this->command->error('No user found in the database. Please create a user first.');
            return;
        }

        // Assign Super Admin role to the user
        $user->assignRole($superAdminRole);

        $this->command->info("Successfully assigned 'Super Admin' role to user: {$user->name} ({$user->email})");
    }
}
