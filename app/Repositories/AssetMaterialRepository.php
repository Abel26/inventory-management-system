<?php

namespace App\Repositories;

use App\Models\AssetMaterial;
use App\Repositories\Contracts\AssetMaterialRepositoryInterface;

class AssetMaterialRepository implements AssetMaterialRepositoryInterface
{
    /**
     * Get all asset materials.
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
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
     * Find asset material by code.
     */
    public function findByCode(string $code): ?AssetMaterial
    {
        return AssetMaterial::where('material_code', $code)->first();
    }

    /**
     * Create new asset material.
     */
    public function create(array $data): AssetMaterial
    {
        return AssetMaterial::create($data);
    }

    /**
     * Update asset material.
     */
    public function update(AssetMaterial $material, array $data): bool
    {
        return $material->update($data);
    }

    /**
     * Delete asset material.
     */
    public function delete(AssetMaterial $material): bool
    {
        return $material->delete();
    }

    /**
     * Search asset materials.
     */
    public function search(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return AssetMaterial::where('name', 'like', "%{$query}%")
            ->orWhere('material_code', 'like', "%{$query}%")
            ->orWhere('type', 'like', "%{$query}%")
            ->get();
    }
}
