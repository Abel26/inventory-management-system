<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Exports\UserExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * User Service instance.
     */
    public function __construct(
        protected UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * Display users listing page.
     */
    public function index(): View
    {
        $roles = Role::all();
        $statistics = $this->userService->getStatistics();
        
        return view('users.index', [
            'roles' => $roles,
            'statistics' => $statistics,
        ]);
    }

    /**
     * Get users data for DataTables.
     */
    public function getData(Request $request): JsonResponse
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search')['value'] ?? '';
            
            $users = $search ? $this->userService->search($search) : $this->userService->getAll();
            
            $totalRecords = $users->count();
            $filteredRecords = $totalRecords;
            
            // Apply pagination
            if ($length != -1) {
                $users = $users->skip($start)->take($length);
            }
            
            $data = $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'phone_number' => $user->phone_number ?? '-',
                    'roles' => $user->roles->toArray(),
                    'is_active' => $user->is_active,
                    'created_at' => $user->created_at->toISOString(),
                    'actions' => $this->generateActionButtons($user),
                ];
            });
            
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting users data: ' . $e->getMessage());
            
            return response()->json([
                'draw' => $request->get('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Failed to load data',
            ], 500);
        }
    }

    /**
     * Store new user.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            // Handle single role for frontend
            if (isset($validated['role'])) {
                $roleName = $validated['role'];
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $validated['roles'] = [$role->id];
                }
            }
            
            $user = $this->userService->create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show user details.
     */
    public function show($id): JsonResponse
    {
        try {
            $user = $this->userService->find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan',
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'phone_number' => $user->phone_number,
                    'is_active' => $user->is_active,
                    'roles' => $user->roles->toArray(),
                    'created_at' => $user->created_at->format('d M Y H:i'),
                    'updated_at' => $user->updated_at->format('d M Y H:i'),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting user details: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update existing user.
     */
    public function update(UpdateUserRequest $request, $id): JsonResponse
    {
        try {
            $validated = $request->validated();
            
            // Handle password - only update if provided
            if (empty($validated['password'])) {
                unset($validated['password']);
                unset($validated['password_confirmation']);
            }
            
            // Handle single role for frontend
            if (isset($validated['role'])) {
                $roleName = $validated['role'];
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $validated['roles'] = [$role->id];
                }
            }
            
            $user = $this->userService->update($id, $validated);
            
            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete user.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->userService->delete($id);
            
            return response()->json([
                'success' => true,
                'message' => 'User berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus($id): JsonResponse
    {
        try {
            $this->userService->toggleStatus($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Status user berhasil diubah',
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling user status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export users to Excel.
     */
    public function export()
    {
        try {
            return Excel::download(new UserExport, 'users-' . date('Y-m-d'));
        } catch (\Exception $e) {
            Log::error('Error exporting users: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data user');
        }
    }

    /**
     * Export users to PDF.
     */
    public function exportPdf()
    {
        try {
            $users = $this->userService->getAll();
            
            $pdf = Pdf::loadView('users.pdf', ['users' => $users])
                ->setPaper('a4')
                ->setOption('margin-bottom', 20)
                ->setOption('margin-left', 20)
                ->setOption('margin-right', 20)
                ->setOption('margin-top', 20)
                ->download('users-' . date('Y-m-d') . '.pdf');
            
            return $pdf->stream();
        } catch (\Exception $e) {
            Log::error('Error exporting users to PDF: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data user ke PDF');
        }
    }

    /**
     * Generate action buttons for DataTables.
     */
    private function generateActionButtons($user): string
    {
        return '
            <div class="flex items-center justify-center gap-2">
                <button onclick="editUser(' . $user->id . ')" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="Edit">
                    <i class="ph ph-pencil-simple text-xl"></i>
                </button>
                <button onclick="deleteUser(' . $user->id . ')" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
                    <i class="ph ph-trash text-xl"></i>
                </button>
            </div>
        ';
    }
}