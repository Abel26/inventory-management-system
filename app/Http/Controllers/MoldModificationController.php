<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMoldModificationRequest;
use App\Http\Requests\UpdateMoldModificationRequest;
use App\Models\MoldModification;
use App\Models\AssetModel;
use App\Services\MoldModificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MoldModificationController extends Controller
{
    /**
     * MoldModification Service instance.
     */
    public function __construct(
        protected MoldModificationService $moldModificationService
    ) {
        $this->moldModificationService = $moldModificationService;
    }
    /**
     * Display a listing of mold modifications.
     */
    public function index(): JsonResponse
    {
        $modifications = $this->moldModificationService->getAll()
            ->map(function ($modification) {
                return [
                    'id' => $modification->id,
                    'asset_model_id' => $modification->asset_model_id,
                    'model_name' => $modification->model_name,
                    'spec_before' => $modification->spec_before,
                    'spec_after' => $modification->spec_after,
                    'production_date' => $modification->production_date->format('Y-m-d'),
                    'production_date_formatted' => $modification->production_date->format('d M Y'),
                    'status' => $modification->status,
                    'status_label' => $modification->status_label,
                    'status_color' => $modification->status_color,
                    'days_remaining' => $modification->days_remaining,
                    'is_critical' => $modification->is_critical,
                    'row_class' => $modification->row_class,
                    'text_class' => $modification->text_class,
                    'description' => $modification->description,
                    'asset_model' => $modification->assetModel ? [
                        'id' => $modification->assetModel->id,
                        'name' => $modification->assetModel->name,
                        'model_code' => $modification->assetModel->model_code
                    ] : null,
                    'created_at' => $modification->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $modification->updated_at->format('Y-m-d H:i:s')
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $modifications
        ]);
    }

    /**
     * Store a newly created mold modification.
     */
    public function store(StoreMoldModificationRequest $request): JsonResponse
    {
        try {
            $modification = $this->moldModificationService->create($request->validated());
            
            // Load relationships for response
            $modification->load('assetModel');
            
            return response()->json([
                'success' => true,
                'message' => 'Jadwal modifikasi berhasil ditambahkan',
                'data' => $modification
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan jadwal modifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the status of a mold modification.
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,done'
        ]);

        try {
            $modification = $this->moldModificationService->updateStatus($id, $request->status);
            
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'data' => $modification
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified mold modification.
     */
    public function update(UpdateMoldModificationRequest $request, $id): JsonResponse
    {
        try {
            $modification = $this->moldModificationService->update($id, $request->validated());
            
            // Load relationships for response
            $modification->load('assetModel');
            
            return response()->json([
                'success' => true,
                'message' => 'Jadwal modifikasi berhasil diperbarui',
                'data' => $modification
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jadwal modifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified mold modification.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $result = $this->moldModificationService->delete($id);
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal modifikasi berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus jadwal modifikasi'
                ], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal modifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get critical mold modifications (H-7 warning).
     */
    public function getCritical(): JsonResponse
    {
        $critical = $this->moldModificationService->getCritical();

        return response()->json([
            'success' => true,
            'data' => $critical,
            'count' => $critical->count()
        ]);
    }

    /**
     * Get asset models for dropdown selection.
     */
    public function getAssetModels(): JsonResponse
    {
        $assetModels = $this->moldModificationService->getAssetModels();

        return response()->json([
            'success' => true,
            'data' => $assetModels
        ]);
    }

    /**
     * Get statistics for dashboard.
     */
    public function getStats(): JsonResponse
    {
        $stats = $this->moldModificationService->getStats();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}