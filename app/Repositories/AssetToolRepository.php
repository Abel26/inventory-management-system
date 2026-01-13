<?php

namespace App\Repositories;

use App\Models\AssetTool;
use App\Repositories\Contracts\AssetToolRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AssetToolRepository implements AssetToolRepositoryInterface
{
    /**
     * Get all asset tools.
     */
    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return AssetTool::all();
    }

    /**
     * Find asset tool by ID.
     */
    public function find(int $id): ?AssetTool
    {
        return AssetTool::find($id);
    }

    /**
     * Create new asset tool.
     */
    public function create(array $data): AssetTool
    {
        return AssetTool::create($data);
    }

    /**
     * Update existing asset tool.
     */
    public function update(int $id, array $data): bool
    {
        $tool = $this->find($id);
        
        if (!$tool) {
            return false;
        }

        $tool->update($data);

        return true;
    }

    /**
     * Delete asset tool.
     */
    public function delete(int $id): bool
    {
        $tool = $this->find($id);
        
        if (!$tool) {
            return false;
        }

        $tool->delete();

        return true;
    }

    /**
     * Search asset tools.
     */
    public function search(string $query): \Illuminate\Database\Eloquent\Collection
    {
        return AssetTool::where('name', 'like', "%{$query}%")
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Find asset tool by code.
     */
    public function findByCode(string $code): ?AssetTool
    {
        return AssetTool::where('tool_code', $code)->first();
    }
}
