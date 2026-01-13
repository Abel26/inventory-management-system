<?php

namespace App\Repositories\Contracts;

interface AssetRepositoryInterface
{
    /**
     * Get all assets (materials, tools, models).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find asset by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Create new asset.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update existing asset.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data);

    /**
     * Delete asset.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);

    /**
     * Search assets.
     *
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $query);
}
