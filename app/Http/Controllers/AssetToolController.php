<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetToolRequest;
use App\Http\Requests\UpdateAssetToolRequest;
use App\Models\AssetTool;
use App\Services\AssetToolService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetToolsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AssetToolController extends Controller
{
    /**
     * AssetTool Service instance.
     */
    public function __construct(
        protected AssetToolService $assetToolService
    ) {
        $this->assetToolService = $assetToolService;
    }

    /**
     * Display asset tools page.
     */
    public function index(): View
    {
        return view('asset_tools.index');
    }

    /**
     * Store new asset tool.
     */
    public function store(StoreAssetToolRequest $request)
    {
        try {
            $this->assetToolService->create($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Alat berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan alat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show single asset tool.
     */
    public function show(int $id)
    {
        try {
            $tool = $this->assetToolService->find($id);
            
            if (!$tool) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $tool->id,
                    'tool_code' => $tool->tool_code,
                    'name' => $tool->name,
                    'category' => $tool->category,
                    'brand' => $tool->brand,
                    'type' => $tool->type,
                    'purchase_year' => $tool->purchase_year,
                    'quantity' => $tool->quantity,
                    'location' => $tool->location,
                    'condition' => $tool->condition,
                    'description' => $tool->description,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data alat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update asset tool.
     */
    public function update(UpdateAssetToolRequest $request, int $id)
    {
        try {
            $updated = $this->assetToolService->update($id, $request->validated());
            
            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Alat berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui alat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete asset tool.
     */
    public function destroy(int $id)
    {
        try {
            $deleted = $this->assetToolService->delete($id);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alat tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Alat berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus alat: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get QR Code URL for tool.
     */
    public function qrCode(int $id)
    {
        $tool = $this->assetToolService->find($id);
        
        if (!$tool) {
            return response()->json([
                'success' => false,
                'message' => 'Alat tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'qr_code_url' => $this->assetToolService->getQrCodeUrl($tool),
            'qrCode' => $this->assetToolService->getQrCodeHtml($tool),
            'toolCode' => $tool->tool_code
        ]);
    }

    /**
     * Export tools to Excel.
     */
    public function export()
    {
        return Excel::download(new AssetToolsExport(), 'tools-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export tools to PDF.
     */
    public function exportPdf()
    {
        $tools = $this->assetToolService->getAll();
        $date = Carbon::now()->locale('id')->isoFormat('D MMMM Y');
        $title = 'Laporan Data Alat';
        
        $pdf = PDF::loadView('asset_tools.pdf', compact('tools', 'date', 'title'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        
        return $pdf->download("Laporan Alat {$date}.pdf");
    }

    /**
     * Get tool data for DataTables.
     */
    public function getData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        // Get all tools
        $query = AssetTool::query();

        // Apply search
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('tool_code', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%");
        }

        // Get total records
        $totalRecords = $query->count();

        // Apply pagination
        $tools = $query->offset($start)
            ->limit($length)
            ->orderBy('created_at', 'desc')
            ->get();

        // Format data for DataTables
        $data = $tools->map(function ($tool) {
            $qrCodeUrl = $this->assetToolService->getQrCodeUrl($tool);
            
            return [
                'id' => $tool->id,
                'tool_code' => $tool->tool_code,
                'name' => $tool->name,
                'category' => $tool->category,
                'brand' => $tool->brand ?? '-',
                'type' => $tool->type ?? '-',
                'purchase_year' => $tool->purchase_year ?? '-',
                'quantity' => $tool->quantity,
                'condition' => $tool->condition,
                'condition_label' => $tool->condition_label,
                'condition_color' => $tool->condition_color,
                'location' => $tool->location ?? '-',
                'qr_code_url' => $qrCodeUrl,
                'created_at' => $tool->created_at->format('d/m/Y H:i'),
            ];
        });

        // Calculate stats
        $stats = [
            'total' => AssetTool::count(),
            'good' => AssetTool::where('condition', 'Good')->count(),
            'repair' => AssetTool::where('condition', 'Repair')->count(),
            'damaged' => AssetTool::where('condition', 'Damaged')->count(),
        ];

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
            'stats' => $stats,
        ]);
    }
}
