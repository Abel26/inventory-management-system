<?php

namespace App\Repositories\Contracts;

interface AssetModelRepositoryInterface
{
    /**
     * Get all asset models.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find asset model by ID.
     *
     * @param int $id
     * @return \App\Models\AssetModel|null
     */
    public function find(int $id);

    /**
     * Create new asset model.
     *
     * @param array $data
     * @return \App\Models\AssetModel
     */
    public function create(array $data);

    /**
     * Update existing asset model.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data);

    /**
     * Delete asset model.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);

    /**
     * Search asset models.
     *
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $query);

    /**
     * Find asset model by code.
     *
     * @param string $code
     * @return \App\Models\AssetModel|null
     */
    public function findByCode(string $code);
}
