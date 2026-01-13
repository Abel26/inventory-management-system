<?php

namespace App\Http\Controllers;

use App\Exports\RolesExport;
use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Role Service instance.
     */
    public function __construct(
        protected RoleService $roleService
    ) {
        $this->roleService = $roleService;
    }

    /**
     * Display roles listing page.
     */
    public function index(): View
    {
        $roles = $this->roleService->getAll();
        $permissions = Permission::all();
        
        // Add permissions_count to each role
        $rolesWithCount = $roles->map(function($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'permissions_count' => $role->permissions->count(),
            ];
        });
        
        return view('roles.index', [
            'roles' => $rolesWithCount,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store new role.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:spatie_permission,name',
            ]);
            
            $this->roleService->create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Role berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show role details.
     */
    public function show(int $id)
    {
        try {
            $role = $this->roleService->findWithPermissions($id);
            
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'guard_name' => $role->guard_name,
                    'permissions' => $role->permissions->pluck('name')->toArray(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update existing role.
     */
    public function update(Request $request, int $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $id,
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:spatie_permission,name',
            ]);
            
            $this->roleService->update($id, $data);
            
            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete role.
     */
    public function destroy(int $id)
    {
        try {
            $role = $this->roleService->find($id);
            
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan'
                ], 404);
            }
            
            // Prevent deleting Super Admin role
            if ($role->name === 'Super Admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Role Super Admin tidak dapat dihapus'
                ], 403);
            }
            
            $this->roleService->delete($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all permissions grouped by module.
     */
    public function getPermissionsByModule()
    {
        $permissions = $this->roleService->getPermissionsByModule();
        
        return response()->json([
            'success' => true,
            'data' => $permissions,
        ]);
    }
    
    /**
     * Export roles to Excel.
     */
    public function export()
    {
        return Excel::download(new RolesExport, 'roles_export_' . date('Y-m-d_H-i') . '.xlsx');
    }
}
