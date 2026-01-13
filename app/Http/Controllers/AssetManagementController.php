<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetMaterialRequest;
use App\Http\Requests\UpdateAssetMaterialRequest;
use App\Models\AssetMaterial;
use App\Services\AssetMaterialService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetMaterialsExport;

class AssetManagementController extends Controller
{
    /**
     * AssetMaterial Service instance.
     */
    public function __construct(
        protected AssetMaterialService $assetMaterialService
    ) {
        $this->assetMaterialService = $assetMaterialService;
    }

    /**
     * Display asset materials page.
     */
    public function index(): View
    {
        $materials = $this->assetMaterialService->getAll();
        
        return view('asset_materials.index', compact('materials'));
    }

    /**
     * Store new asset material.
     */
    public function store(StoreAssetMaterialRequest $request)
    {
        try {
            $this->assetMaterialService->create($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Material berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show single asset material.
     */
    public function show(int $id)
    {
        try {
            $material = $this->assetMaterialService->find($id);
            
            if (!$material) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $material->id,
                    'material_code' => $material->material_code,
                    'name' => $material->name,
                    'type' => $material->type,
                    'quantity' => $material->quantity,
                    'unit' => $material->unit,
                    'min_threshold' => $material->min_threshold,
                    'unit_price' => $material->unit_price,
                    'supplier' => $material->supplier,
                    'entry_date' => $material->entry_date ? $material->entry_date->format('Y-m-d') : null,
                    'expiry_date' => $material->expiry_date ? $material->expiry_date->format('Y-m-d') : null,
                    'location' => $material->location,
                    'description' => $material->description,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update asset material.
     */
    public function update(UpdateAssetMaterialRequest $request, int $id)
    {
        try {
            $updated = $this->assetMaterialService->update($id, $request->validated());
            
            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Material berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete asset material.
     */
    public function destroy(int $id)
    {
        try {
            $deleted = $this->assetMaterialService->delete($id);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Material berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get QR Code URL for material.
     */
    public function qrCode(int $id)
    {
        $material = $this->assetMaterialService->find($id);
        
        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'Material tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'qr_code_url' => $this->assetMaterialService->getQrCodeUrl($material),
            'qrCode' => $this->assetMaterialService->getQrCodeHtml($material),
            'materialCode' => $material->material_code
        ]);
    }

    /**
     * Export materials to Excel.
     */
    public function export()
    {
        return Excel::download(new AssetMaterialsExport(), 'materials-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Get material data for DataTables.
     */
    public function getData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        // Get all materials
        $query = AssetMaterial::query();

        // Apply search
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('material_code', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%");
        }

        // Get total records
        $totalRecords = $query->count();

        // Apply pagination
        $materials = $query->offset($start)
            ->limit($length)
            ->orderBy('created_at', 'desc')
            ->get();

        // Format data for DataTables
        $data = $materials->map(function ($material) {
            $qrCodeUrl = $this->assetMaterialService->getQrCodeUrl($material);
            
            return [
                'id' => $material->id,
                'material_code' => $material->material_code,
                'name' => $material->name,
                'type' => $material->type,
                'quantity' => $material->quantity,
                'unit' => $material->unit,
                'min_threshold' => $material->min_threshold,
                'supplier' => $material->supplier ?? '-',
                'entry_date' => $material->entry_date ? $material->entry_date->format('d/m/Y') : '-',
                'expiry_date' => $material->expiry_date ? $material->expiry_date->format('d/m/Y') : '-',
                'location' => $material->location ?? '-',
                'stock_status' => $material->stock_status,
                'stock_status_label' => $material->stock_status_label,
                'stock_status_color' => $material->stock_status_color,
                'qr_code_url' => $qrCodeUrl,
                'created_at' => $material->created_at->format('d/m/Y H:i'),
            ];
        });

        // Calculate stats
        $stats = [
            'total' => AssetMaterial::count(),
            'lowStock' => $this->assetMaterialService->getLowStockMaterials()->count(),
            'outOfStock' => $this->assetMaterialService->getOutOfStockMaterials()->count(),
            'totalValue' => $this->assetMaterialService->getTotalValue(),
        ];

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
            'stats' => $stats,
        ]);
    }

    /**
     * Display asset tools page.
     */
    public function tools(): View
    {
        return view('asset_tools.index');
    }

    /**
     * Display asset models page.
     */
    public function models(): View
    {
        return view('asset_models.index');
    }
}
