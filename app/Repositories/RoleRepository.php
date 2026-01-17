<?php

namespace App\Repositories;

use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Get all roles.
     */
    public function all()
    {
        return Role::with('permissions')->get();
    }

    /**
     * Find role by ID.
     */
    public function find(int $id)
    {
        return Role::with('permissions')->find($id);
    }

    /**
     * Create new role.
     */
    public function create(array $data)
    {
        $role = Role::create($data);
        
        if (isset($data['permissions'])) {
            // Convert permission IDs to Permission objects before syncing
            $permissionIds = array_map('intval', $data['permissions']);
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissions);
        }
        
        return $role;
    }

    /**
     * Update existing role.
     */
    public function update(int $id, array $data)
    {
        $role = $this->find($id);
        
        if (!$role) {
            return null;
        }
        
        $role->update($data);
        
        if (isset($data['permissions'])) {
            // Convert permission IDs to Permission objects before syncing
            $permissionIds = array_map('intval', $data['permissions']);
            $permissions = Permission::whereIn('id', $permissionIds)->get();
            $role->syncPermissions($permissions);
        }
        
        return $role;
    }

    /**
     * Delete role.
     */
    public function delete(int $id)
    {
        $role = $this->find($id);
        
        if (!$role) {
            return false;
        }

        $role->delete();
        
        return true;
    }

    /**
     * Find role by name.
     */
    public function findByName(string $name)
    {
        return Role::where('name', $name)->first();
    }

    /**
     * Get role with permissions.
     */
    public function findWithPermissions(int $id)
    {
        return Role::with('permissions')->find($id);
    }
    
    /**
     * Get permissions grouped by module.
     */
    public function getPermissionsByModule(): Collection
    {
        $permissions = Permission::all();
        
        // Group permissions by module based on naming convention
        return $permissions->groupBy(function($permission) {
            $name = $permission->name;
            
            // Extract module from permission name
            if (str_contains($name, 'dashboard')) {
                return 'Dashboard';
            } elseif (str_contains($name, 'products')) {
                return 'Products';
            } elseif (str_contains($name, 'assets')) {
                return 'Assets';
            } elseif (str_contains($name, 'materials')) {
                return 'Materials';
            } elseif (str_contains($name, 'tools')) {
                return 'Tools';
            } elseif (str_contains($name, 'models')) {
                return 'Models';
            } elseif (str_contains($name, 'roles')) {
                return 'Roles';
            }
            
            return 'Other';
        });
    }
}
