<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetModelRequest;
use App\Http\Requests\UpdateAssetModelRequest;
use App\Models\AssetModel;
use App\Services\AssetModelService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetModelsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AssetModelController extends Controller
{
    /**
     * AssetModel Service instance.
     */
    public function __construct(
        protected AssetModelService $assetModelService
    ) {
        $this->assetModelService = $assetModelService;
    }

    /**
     * Display asset models page.
     */
    public function index(): View
    {
        $materials = \App\Models\AssetMaterial::all();
        return view('asset_models.index', compact('materials'));
    }

    /**
     * Store new asset model.
     */
    public function store(StoreAssetModelRequest $request)
    {
        try {
            $this->assetModelService->create($request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Model berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan model: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show single asset model.
     */
    public function show(int $id)
    {
        try {
            $model = $this->assetModelService->find($id);
            
            if (!$model) {
                return response()->json([
                    'success' => false,
                    'message' => 'Model tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $model->id,
                    'model_code' => $model->model_code,
                    'name' => $model->name,
                    'type' => $model->type,
                    'material_id' => $model->material_id,
                    'manufactured_date' => $model->manufactured_date ? $model->manufactured_date->format('Y-m-d') : null,
                    'condition' => $model->condition,
                    'location' => $model->location,
                    'description' => $model->description,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data model: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update asset model.
     */
    public function update(UpdateAssetModelRequest $request, int $id)
    {
        try {
            $updated = $this->assetModelService->update($id, $request->validated());
            
            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Model tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Model berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui model: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete asset model.
     */
    public function destroy(int $id)
    {
        try {
            $deleted = $this->assetModelService->delete($id);
            
            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Model tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Model berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus model: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get QR Code URL for model.
     */
    public function qrCode(int $id)
    {
        $model = $this->assetModelService->find($id);
        
        if (!$model) {
            return response()->json([
                'success' => false,
                'message' => 'Model tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'qr_code_url' => $this->assetModelService->getQrCodeUrl($model),
            'qrCode' => $this->assetModelService->getQrCodeHtml($model),
            'modelCode' => $model->model_code
        ]);
    }

    /**
     * Export models to Excel.
     */
    public function export()
    {
        return Excel::download(new AssetModelsExport(), 'models-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export models to PDF.
     */
    public function exportPdf()
    {
        $models = \App\Models\AssetModel::with('material')->get();
        $date = Carbon::now()->locale('id')->isoFormat('D MMMM Y');
        $title = 'Laporan Data Model';
        
        $pdf = PDF::loadView('asset_models.pdf', compact('models', 'date', 'title'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
        
        return $pdf->download("Laporan Model {$date}.pdf");
    }

    /**
     * Get model data for DataTables.
     */
    public function getData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');
        $search = $request->get('search')['value'] ?? '';

        // Get all models
        $query = AssetModel::with('material:id,name');

        // Apply search
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('model_code', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%");
        }

        // Get total records
        $totalRecords = $query->count();

        // Apply pagination
        $models = $query->offset($start)
            ->limit($length)
            ->orderBy('created_at', 'desc')
            ->get();

        // Format data for DataTables
        $data = $models->map(function ($model) {
            $qrCodeUrl = $this->assetModelService->getQrCodeUrl($model);
            
            return [
                'id' => $model->id,
                'model_code' => $model->model_code,
                'name' => $model->name,
                'type' => $model->type,
                'material_name' => $model->material?->name ?? '-',
                'manufactured_date' => $model->manufactured_date ? $model->manufactured_date->format('d/m/Y') : '-',
                'condition' => $model->condition,
                'condition_label' => $model->condition_label,
                'condition_color' => $model->condition_color,
                'location' => $model->location ?? '-',
                'qr_code_url' => $qrCodeUrl,
                'created_at' => $model->created_at->format('d/m/Y H:i'),
            ];
        });

        // Calculate stats
        $stats = [
            'total' => AssetModel::count(),
            'good' => AssetModel::where('condition', 'Good')->count(),
            'repair' => AssetModel::where('condition', 'Repair')->count(),
            'damaged' => AssetModel::where('condition', 'Damaged')->count(),
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
