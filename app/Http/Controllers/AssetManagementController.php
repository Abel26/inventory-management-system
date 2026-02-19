<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetMaterialRequest;
use App\Http\Requests\UpdateAssetMaterialRequest;
use App\Models\AssetMaterial;
use App\Services\AssetMaterialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetMaterialsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        if ($request->ajax()) {
            // Handle AJAX request for DataTables
            return $this->getData($request);
        }
        
        $materials = $this->assetMaterialService->getAll();
        $gedungs = \App\Models\Gedung::orderBy('nama')->get();
        $satuans = \App\Models\Satuan::orderBy('nama')->get();
        
        return view('asset_materials.index', compact('materials', 'gedungs', 'satuans'));
    }

    /**
     * Show the form for creating a new asset material.
     */
    public function create(): View
    {
        $gedungs = \App\Models\Gedung::orderBy('nama')->get();
        return view('asset_materials.create', compact('gedungs'));
    }

    /**
     * Show the form for editing the specified asset material.
     */
    public function edit(int $id): View
    {
        $material = $this->assetMaterialService->find($id);
        $gedungs = \App\Models\Gedung::orderBy('nama')->get();
        
        if (!$material) {
            abort(404, 'Material tidak ditemukan');
        }
        
        return view('asset_materials.edit', compact('material', 'gedungs'));
    }

    /**
     * Store new asset material.
     */
    public function store(StoreAssetMaterialRequest $request)
    {
        Log::info('MATERIAL STORE: Request received', [
            'data' => $request->all(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        try {
            Log::info('MATERIAL STORE: Validating request');
            $validatedData = $request->validated();
            Log::info('MATERIAL STORE: Validation passed', ['validated_data' => $validatedData]);
            
            $material = $this->assetMaterialService->create($validatedData);
            Log::info('MATERIAL STORE: Material created successfully', ['material_id' => $material->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Material berhasil ditambahkan',
                'material_id' => $material->id
            ]);
        } catch (\Exception $e) {
            Log::error('MATERIAL STORE: Error occurred', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show single asset material.
     */
    public function show($id)
    {
        try {
            // Convert to integer if it's numeric, otherwise try to find by material_code
            $materialId = is_numeric($id) ? (int) $id : null;
            
            if ($materialId) {
                $material = $this->assetMaterialService->find($materialId);
            } else {
                // Try to find by material_code if ID is not numeric
                $material = $this->assetMaterialService->getByMaterialCode($id);
            }
            
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
                    'unit_id' => $material->unit_id,
                    'unit' => $material->satuan ? $material->satuan->nama : $material->unit, // Untuk backward compatibility
                    'min_threshold' => $material->min_threshold,
                    'unit_price' => $material->unit_price,
                    'supplier' => $material->supplier,
                    'entry_date' => $material->entry_date ? $material->entry_date->format('Y-m-d') : '',
                    'expiry_date' => $material->expiry_date ? $material->expiry_date->format('Y-m-d') : '',
                    'location' => $material->location,
                    'gedung_id' => $material->gedung_id,
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
            // Ensure we have a valid material ID
            if (!$id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID Material tidak valid'
                ], 400);
            }
            
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
     * Export materials to PDF.
     */
    public function exportPdf()
    {
        $materials = $this->assetMaterialService->getAll();
        $date = Carbon::now()->locale('id')->isoFormat('D MMMM Y');
        $title = 'Laporan Data Material';
        
        $pdf = PDF::loadView('asset_materials.pdf', compact('materials', 'date', 'title'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        
        return $pdf->download("Laporan Material {$date}.pdf");
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

        // Get all materials with fresh data to avoid cache issues
        $query = AssetMaterial::with(['satuan', 'gedung'])->latest();

        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('material_code', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Get total records
        $totalRecords = $query->count();

        // Apply pagination with fresh data
        $materials = $query->offset($start)
            ->limit($length)
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
                'unit_id' => $material->unit_id,
                'unit' => $material->satuan ? $material->satuan->nama : $material->unit, // Untuk backward compatibility
                'satuan' => $material->satuan, // Include satuan relation for DataTable
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
