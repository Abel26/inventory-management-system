<?php

namespace App\Http\Controllers;

use App\Exports\RolesExport;
use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                'permissions.*' => 'exists:permissions,id',
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
                    'permissions' => $role->permissions->pluck('id')->toArray(),
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
                'permissions.*' => 'exists:permissions,id',
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
    
    /**
     * Export roles to PDF.
     */
    public function exportPdf()
    {
        $roles = Role::withCount('permissions')->orderBy('created_at', 'desc')->get();
        
        $pdf = Pdf::loadView('exports.roles-pdf', compact('roles'))
            ->setPaper('a4', 'portrait')
            ->setOption(['defaultFont' => 'Arial']);
        
        return $pdf->download('roles_export_' . date('Y-m-d_H-i') . '.pdf');
    }

    /**
     * Get data for DataTables.
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $draw = (int) $request->get('draw', 1);
            $start = (int) $request->get('start', 0);
            $length = (int) $request->get('length', 10);
            $search = $request->get('search');
            $searchValue = $search['value'] ?? '';

            // Base query
            $baseQuery = Role::withCount('permissions');
            
            // Get total records WITHOUT filters for pagination
            $totalRecords = Role::count();

            // Build filtered query
            $query = $baseQuery->clone();
            
            // Apply search filter if provided
            if (!empty($searchValue)) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%")
                      ->orWhere('guard_name', 'like', "%{$searchValue}%");
                });
            }

            // Get filtered records count
            $filteredRecords = $query->count();

            // Apply sorting
            $orderBy = 'created_at';
            $orderDir = 'DESC';
            
            if ($request->has('order') && is_array($request->get('order'))) {
                $orderArray = $request->get('order')[0] ?? null;
                if ($orderArray) {
                    $columnIndex = $orderArray['column'] ?? 0;
                    $dir = strtoupper($orderArray['dir'] ?? 'ASC');
                    
                    // Map column index to field name
                    $columns = ['name', 'guard_name', 'permissions_count', 'id'];
                    if (isset($columns[$columnIndex])) {
                        $orderBy = $columns[$columnIndex];
                        $orderDir = in_array($dir, ['ASC', 'DESC']) ? $dir : 'ASC';
                    }
                }
            }

            // Apply pagination and ordering
            $roles = $query->orderBy($orderBy, $orderDir)
                ->offset($start)
                ->limit($length)
                ->get();

            // Transform data for DataTables
            $data = $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => htmlspecialchars($role->name, ENT_QUOTES, 'UTF-8'),
                    'guard_name' => htmlspecialchars($role->guard_name, ENT_QUOTES, 'UTF-8'),
                    'permissions_count' => (int) $role->permissions_count,
                ];
            })->toArray();

            // Return proper DataTables response
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data,
            ], 200, [], JSON_UNESCAPED_UNICODE);
            
        } catch (\Exception $e) {
            Log::error('RoleController@getData Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'draw' => $request->get('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => env('APP_DEBUG') ? $e->getMessage() : 'Gagal mengambil data',
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
