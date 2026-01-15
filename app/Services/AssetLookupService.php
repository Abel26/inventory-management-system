<?php

namespace App\Services;

use App\Models\AssetMaterial;
use App\Models\AssetTool;
use App\Models\AssetModel;
use Illuminate\Support\Facades\Log;

class AssetLookupService
{
    /**
     * Find asset by code.
     *
     * @param string $code The QR code or asset code to search
     * @return array Returns asset data with type or null
     */
    public function findByCode(string $code): array
    {
        // Trim whitespace from the code
        $code = trim($code);

        Log::info('🔍 AssetLookupService: Searching for asset', [
            'code' => $code,
            'code_length' => strlen($code),
        ]);

        // Try to find in tools first
        $tool = AssetTool::withTrashed()
            ->where('tool_code', $code)
            ->orWhere('qr_code_path', $code)
            ->first();

        if ($tool) {
            Log::info('✅ AssetLookupService: Tool found', [
                'code' => $code,
                'tool_id' => $tool->id,
                'tool_name' => $tool->name,
            ]);

            return [
                'found' => true,
                'type' => 'tool',
                'model' => AssetTool::class,
                'asset' => $tool,
                'display_name' => $tool->name ?? 'Tool',
                'code' => $tool->tool_code,
                'condition' => $tool->condition,
                'location' => $tool->location ?? 'Gudang',
            ];
        }

        // Try to find in models
        $model = AssetModel::withTrashed()
            ->where('model_code', $code)
            ->orWhere('qr_code_path', $code)
            ->first();

        if ($model) {
            Log::info('✅ AssetLookupService: Model found', [
                'code' => $code,
                'model_id' => $model->id,
                'model_name' => $model->name,
            ]);

            return [
                'found' => true,
                'type' => 'model',
                'model' => AssetModel::class,
                'asset' => $model,
                'display_name' => $model->name ?? 'Model',
                'code' => $model->model_code,
                'condition' => $model->condition,
                'location' => $model->location ?? 'Gudang',
            ];
        }

        // Try to find in materials
        $material = AssetMaterial::withTrashed()
            ->where('material_code', $code)
            ->orWhere('qr_code_path', $code)
            ->first();

        if ($material) {
            Log::info('✅ AssetLookupService: Material found', [
                'code' => $code,
                'material_id' => $material->id,
                'material_name' => $material->name,
            ]);

            return [
                'found' => true,
                'type' => 'material',
                'model' => AssetMaterial::class,
                'asset' => $material,
                'display_name' => $material->name ?? 'Material',
                'code' => $material->material_code,
                'condition' => $material->condition ?? 'Baik',
                'location' => $material->location ?? 'Gudang',
            ];
        }

        // Asset not found
        Log::warning('⚠️ AssetLookupService: Asset not found', [
            'code' => $code,
        ]);

        return [
            'found' => false,
            'message' => 'Aset tidak ditemukan',
        ];
    }

    /**
     * Get asset display information for UI.
     *
     * @param array|null $assetData The asset data from findByCode
     * @return array Formatted asset info
     */
    public function getAssetDisplayInfo(?array $assetData): array
    {
        if (!$assetData) {
            Log::warning('⚠️ AssetLookupService: No asset data provided');

            return [
                'found' => false,
                'message' => 'Aset tidak ditemukan',
            ];
        }

        Log::info('✅ AssetLookupService: Formatting asset display info', [
            'type' => $assetData['type'] ?? null,
            'code' => $assetData['code'] ?? null,
        ]);

        return [
            'found' => true,
            'asset' => [
                'name' => $assetData['display_name'],
                'code' => $assetData['code'],
                'type' => ucfirst($assetData['type']),
                'condition' => $assetData['condition'],
                'location' => $assetData['location'],
                'id' => $assetData['asset']->id,
                'model_class' => $assetData['model'],
            ],
        ];
    }
}
