<?php

namespace App\Repositories;

use App\Models\AssetModel;
use App\Repositories\Contracts\AssetModelRepositoryInterface;
use Illuminate\Support\Collection;

class AssetModelRepository implements AssetModelRepositoryInterface
{
    /**
     * Get all asset models.
     */
    public function all(): Collection
    {
        return AssetModel::with('material')->get();
    }

    /**
     * Find asset model by ID.
     */
    public function find(int $id): ?AssetModel
    {
        return AssetModel::with('material')->find($id);
    }

    /**
     * Create new asset model.
     */
    public function create(array $data): AssetModel
    {
        return AssetModel::create($data);
    }

    /**
     * Update existing asset model.
     */
    public function update(int $id, array $data): bool
    {
        $model = $this->find($id);
        
        if (!$model) {
            return false;
        }

        $model->update($data);

        return true;
    }

    /**
     * Delete asset model.
     */
    public function delete(int $id): bool
    {
        $model = $this->find($id);
        
        if (!$model) {
            return false;
        }

        $model->delete();

        return true;
    }

    /**
     * Search asset models.
     */
    public function search(string $query): Collection
    {
        return AssetModel::where('name', 'like', "%{$query}%")
            ->orWhere('model_code', 'like', "%{$query}%")
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Find asset model by code.
     */
    public function findByCode(string $code): ?AssetModel
    {
        return AssetModel::where('model_code', $code)->first();
    }
}
