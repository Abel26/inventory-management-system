<?php

namespace App\Repositories\Contracts;

interface AssetToolRepositoryInterface
{
    /**
     * Get all asset tools.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Find asset tool by ID.
     *
     * @param int $id
     * @return \App\Models\AssetTool|null
     */
    public function find(int $id);

    /**
     * Create new asset tool.
     *
     * @param array $data
     * @return \App\Models\AssetTool
     */
    public function create(array $data);

    /**
     * Update existing asset tool.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data);

    /**
     * Delete asset tool.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id);

    /**
     * Search asset tools.
     *
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $query);
}
