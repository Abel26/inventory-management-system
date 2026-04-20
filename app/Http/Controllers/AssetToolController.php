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
    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        if ($request->ajax()) {
            // Handle AJAX request for DataTables
            return $this->getData($request);
        }
        
        $gedungs = \App\Models\Gedung::orderBy('nama')->get();
        return view('asset_tools.index', compact('gedungs'));
    }

    /**
     * Show the form for creating a new asset tool.
     */
    public function create(): View
    {
        $gedungs = \App\Models\Gedung::orderBy('nama')->get();
        return view('asset_tools.create', compact('gedungs'));
    }

    /**
     * Show the form for editing the specified asset tool.
     */
    public function edit(int $id): View
    {
        $tool = $this->assetToolService->find($id);
        $gedungs = \App\Models\Gedung::orderBy('nama')->get();
        
        if (!$tool) {
            abort(404, 'Alat tidak ditemukan');
        }
        
        return view('asset_tools.edit', compact('tool', 'gedungs'));
    }

    /**
     * Store new asset tool.
     */
    public function store(StoreAssetToolRequest $request)
    {
        try {
            $tool = $this->assetToolService->create($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Data peralatan berhasil disimpan',
                'data' => $tool
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data peralatan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show single asset tool.
     */
    public function show($id)
    {
        try {
            // Convert to integer if it's numeric, otherwise try to find by tool_code
            $toolId = is_numeric($id) ? (int) $id : null;
            
            if ($toolId) {
                $tool = $this->assetToolService->find($toolId);
            } else {
                // Try to find by tool_code if ID is not numeric
                $tool = $this->assetToolService->getByToolCode($id);
            }
            
            if (!$tool) {
                if (request()->ajax() || request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Alat tidak ditemukan'
                    ], 404);
                }
                abort(404, 'Alat tidak ditemukan');
            }
            
            // If request is AJAX or expects JSON, return JSON response
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $tool->id,
                        'tool_code' => $tool->tool_code,
                        'name' => $tool->name,
                        'category' => $tool->category,
                        'brand' => $tool->brand,
                        'type' => $tool->type,
                        'purchase_date' => $tool->purchase_date ? $tool->purchase_date->format('Y-m-d') : '',
                        'purchase_price' => $tool->purchase_price,
                        'purchase_year' => $tool->purchase_date ? $tool->purchase_date->format('Y') : ($tool->purchase_year ?? ''),
                        'quantity' => $tool->quantity,
                        'location' => $tool->location,
                        'gedung_id' => $tool->gedung_id,
                        'condition' => $tool->condition,
                        'description' => $tool->description,
                    ]
                ]);
            }

            // Otherwise return the view
            return view('asset_tools.show', compact('tool'));
        } catch (\Exception $e) {
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data alat: ' . $e->getMessage()
                ], 500);
            }
            abort(500, 'Gagal mengambil data alat');
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
                    'message' => 'Data peralatan tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Data peralatan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data peralatan: ' . $e->getMessage()
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
                // For AJAX requests, return JSON response
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data peralatan tidak ditemukan'
                    ], 404);
                }
                
                return redirect()->route('assets.tools.index')->with('error', 'Data peralatan tidak ditemukan');
            }
            
            // For AJAX requests, return JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data peralatan berhasil dihapus'
                ]);
            }
            
            return redirect()->route('assets.tools.index')->with('success', 'Data peralatan berhasil dihapus');
        } catch (\Exception $e) {
            // For AJAX requests, return JSON response
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data peralatan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('assets.tools.index')->with('error', 'Gagal menghapus data peralatan: ' . $e->getMessage());
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

        // Get all tools (excluding soft-deleted)
        $query = AssetTool::withoutTrashed();

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
                'purchase_year' => $tool->purchase_date ? $tool->purchase_date->format('Y') : ($tool->purchase_year ?? '-'),
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
