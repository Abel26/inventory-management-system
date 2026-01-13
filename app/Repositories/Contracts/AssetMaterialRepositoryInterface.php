<?php

namespace App\Repositories\Contracts;

use App\Models\AssetMaterial;

interface AssetMaterialRepositoryInterface
{
    /**
     * Get all asset materials.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): \Illuminate\Database\Eloquent\Collection;

    /**
     * Find asset material by ID.
     *
     * @param int $id
     * @return AssetMaterial|null
     */
    public function find(int $id): ?AssetMaterial;

    /**
     * Find asset material by code.
     *
     * @param string $code
     * @return AssetMaterial|null
     */
    public function findByCode(string $code): ?AssetMaterial;

    /**
     * Create new asset material.
     *
     * @param array $data
     * @return AssetMaterial
     */
    public function create(array $data): AssetMaterial;

    /**
     * Update asset material.
     *
     * @param AssetMaterial $material
     * @param array $data
     * @return bool
     */
    public function update(AssetMaterial $material, array $data): bool;

    /**
     * Delete asset material.
     *
     * @param AssetMaterial $material
     * @return bool
     */
    public function delete(AssetMaterial $material): bool;

    /**
     * Search asset materials.
     *
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $query): \Illuminate\Database\Eloquent\Collection;
}
