<?php

namespace App\Repositories;

use App\Models\AssetMaterial;
use App\Repositories\Contracts\AssetMaterialRepositoryInterface;
use Illuminate\Support\Collection;

class AssetRepository implements AssetMaterialRepositoryInterface
{
    /**
     * Get all asset materials.
     */
    public function all(): Collection
    {
        return AssetMaterial::all();
    }

    /**
     * Find asset material by ID.
     */
    public function find(int $id): ?AssetMaterial
    {
        return AssetMaterial::find($id);
    }

    /**
     * Create new asset material.
     */
    public function create(array $data): AssetMaterial
    {
        return AssetMaterial::create($data);
    }

    /**
     * Update existing asset material.
     */
    public function update(int $id, array $data): bool
    {
        $material = $this->find($id);
        
        if (!$material) {
            return false;
        }

        $material->update($data);

        return true;
    }

    /**
     * Delete asset material.
     */
    public function delete(int $id): bool
    {
        $material = $this->find($id);
        
        if (!$material) {
            return false;
        }

        $material->delete();

        return true;
    }

    /**
     * Search asset materials.
     */
    public function search(string $query): Collection
    {
        return AssetMaterial::where('name', 'like', "%{$query}%")
            ->orderBy('name', 'asc')
            ->get();
    }
}
