<?php

namespace App\Services;

use App\Models\MoldModification;
use App\Models\AssetModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MoldModificationService
{
    /**
     * Get all mold modifications with relationships.
     */
    public function getAll()
    {
        return MoldModification::with('assetModel')
            ->orderByProductionDate()
            ->get();
    }

    /**
     * Get mold modification by ID.
     */
    public function findById(int $id): ?MoldModification
    {
        return MoldModification::with('assetModel')->find($id);
    }

    /**
     * Create new mold modification.
     */
    public function create(array $data): MoldModification
    {
        Log::info('Creating mold modification', ['data' => $data]);

        // Get model name from asset model if not provided
        if (!isset($data['model_name']) || empty($data['model_name'])) {
            $assetModel = AssetModel::find($data['asset_model_id']);
            $data['model_name'] = $assetModel ? $assetModel->name : 'Unknown Model';
        }

        $modification = MoldModification::create($data);

        // Clear cache
        $this->clearCache();

        Log::info('Mold modification created successfully', ['id' => $modification->id]);

        return $modification;
    }

    /**
     * Update mold modification status.
     */
    public function updateStatus(int $id, string $status): MoldModification
    {
        $modification = MoldModification::findOrFail($id);
        
        Log::info('Updating mold modification status', [
            'id' => $id,
            'old_status' => $modification->status,
            'new_status' => $status
        ]);

        $modification->update(['status' => $status]);

        // Clear cache
        $this->clearCache();

        Log::info('Mold modification status updated successfully', ['id' => $id]);

        return $modification;
    }

    /**
     * Update mold modification.
     */
    public function update(int $id, array $data): MoldModification
    {
        $modification = MoldModification::findOrFail($id);
        
        Log::info('Updating mold modification', [
            'id' => $id,
            'data' => $data
        ]);

        // Get model name from asset model if not provided
        if (!isset($data['model_name']) || empty($data['model_name'])) {
            $assetModel = AssetModel::find($data['asset_model_id']);
            $data['model_name'] = $assetModel ? $assetModel->name : 'Unknown Model';
        }

        $modification->update($data);

        // Clear cache
        $this->clearCache();

        Log::info('Mold modification updated successfully', ['id' => $id]);

        return $modification;
    }

    /**
     * Get critical mold modifications (H-7 warning).
     */
    public function getCritical()
    {
        return MoldModification::critical()
            ->with('assetModel')
            ->orderByProductionDate()
            ->get();
    }

    /**
     * Get asset models for dropdown selection.
     */
    public function getAssetModels()
    {
        return AssetModel::select('id', 'name', 'model_code')
            ->orderBy('name')
            ->get()
            ->map(function ($model) {
                return [
                    'id' => $model->id,
                    'name' => $model->name,
                    'display_name' => $model->name . ' (' . $model->model_code . ')'
                ];
            });
    }

    /**
     * Get statistics for dashboard.
     */
    public function getStats(): array
    {
        return Cache::remember('mold_modifications.stats', now()->addMinutes(5), function () {
            $total = MoldModification::count();
            $pending = MoldModification::pending()->count();
            $done = MoldModification::completed()->count();
            $critical = MoldModification::critical()->count();

            return [
                'total' => $total,
                'pending' => $pending,
                'done' => $done,
                'critical' => $critical,
                'completion_rate' => $total > 0 ? round(($done / $total) * 100, 1) : 0
            ];
        });
    }

    /**
     * Delete mold modification.
     */
    public function delete(int $id): bool
    {
        $modification = MoldModification::findOrFail($id);
        
        Log::info('Deleting mold modification', ['id' => $id]);

        $result = $modification->delete();

        // Clear cache
        $this->clearCache();

        Log::info('Mold modification deleted successfully', ['id' => $id]);

        return $result;
    }

    /**
     * Clear related cache.
     */
    private function clearCache(): void
    {
        Cache::forget('dashboard.mold_modifications');
        Cache::forget('mold_modifications.stats');
    }

    /**
     * Get mold modifications with filters.
     */
    public function getFiltered(array $filters = [])
    {
        $query = MoldModification::with('assetModel');

        // Apply status filter
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }

        // Apply date range filter
        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('production_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('production_date', '<=', $filters['date_to']);
        }

        // Apply asset model filter
        if (isset($filters['asset_model_id']) && $filters['asset_model_id']) {
            $query->where('asset_model_id', $filters['asset_model_id']);
        }

        return $query->orderByProductionDate()->get();
    }

    /**
     * Get upcoming modifications (next 7 days).
     */
    public function getUpcoming(int $days = 7)
    {
        return MoldModification::where('production_date', '>', now())
            ->where('production_date', '<=', now()->addDays($days))
            ->where('status', 'pending')
            ->with('assetModel')
            ->orderByProductionDate()
            ->get();
    }

    /**
     * Get overdue modifications.
     */
    public function getOverdue()
    {
        return MoldModification::where('production_date', '<', now())
            ->where('status', 'pending')
            ->with('assetModel')
            ->orderBy('production_date', 'asc')
            ->get();
    }
}