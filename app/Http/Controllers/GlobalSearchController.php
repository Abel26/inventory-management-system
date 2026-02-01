<?php

namespace App\Http\Controllers;

use App\Models\AssetMaterial;
use App\Models\AssetTool;
use App\Models\AssetModel;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class GlobalSearchController extends Controller
{
    /**
     * Search across all asset types
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100',
        ]);

        $query = $request->input('query');

        // Cache search results for 5 minutes
        $results = Cache::remember("search.{$query}", now()->addMinutes(5), function () use ($query) {
            return $this->performPolymorphicSearch($query);
        });






































        return response()->json([
            'success' => true,
            'data' => $results,
            'total' => count($results),
        ]);
    }

    /**
     * Get detailed asset information with relations
     */
    public function getDetail(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:material,tool,model',
            'id' => 'required|integer',
        ]);

        $type = $request->input('type');
        $id = $request->input('id');

        // Cache asset details for 10 minutes
        $asset = Cache::remember("asset.{$type}.{$id}", now()->addMinutes(10), function () use ($type, $id) {
            return $this->getAssetWithRelations($type, $id);
        });

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Asset tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $asset,
        ]);
    }

    /**
     * Perform polymorphic search across all asset types
     */
    private function performPolymorphicSearch(string $query): array
    {
        $results = [];
        // Search Materials with report count
        $materials = AssetMaterial::withCount(['reports'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('material_code', 'like', "%{$query}%")
                  ->orWhere('type', 'like', "%{$query}%")
                  ->orWhere('location', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();

        foreach ($materials as $item) {
            $results[] = [
                'id' => $item->id,
                'type' => 'material',
                'name' => $item->name,
                'code' => $item->material_code,
                'icon' => 'ph-package',
                'recent_issues' => $item->reports_count,
                'status' => $item->stock_status,
                'status_label' => $item->stock_status_label,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'location' => $item->location,
                'search_rank' => $this->calculateSearchRank($item, $query),
            ];
        }

        // Search Tools with report count
        $tools = AssetTool::withCount(['reports'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('tool_code', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%")
                  ->orWhere('brand', 'like', "%{$query}%")
                  ->orWhere('location', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();

        foreach ($tools as $item) {
            $results[] = [
                'id' => $item->id,
                'type' => 'tool',
                'name' => $item->name,
                'code' => $item->tool_code,
                'icon' => 'ph-wrench',
                'recent_issues' => $item->reports_count,
                'status' => $item->condition_status ?? 'unknown',
                'status_label' => $this->translateCondition($item->condition ?? 'Unknown'),
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'location' => $item->location,
                'search_rank' => $this->calculateSearchRank($item, $query),
            ];
        }

        // Search Models with report count
        $models = AssetModel::withCount(['reports'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('model_code', 'like', "%{$query}%")
                  ->orWhere('type', 'like', "%{$query}%")
                  ->orWhere('location', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();

        foreach ($models as $item) {
            $results[] = [
                'id' => $item->id,
                'type' => 'model',
                'name' => $item->name,
                'code' => $item->model_code,
                'icon' => 'ph-cube',
                'recent_issues' => $item->reports_count,
                'status' => $item->condition_status ?? 'unknown',
                'status_label' => $this->translateCondition($item->condition ?? 'Unknown'),
                'quantity' => $item->quantity,
                'location' => $item->location,
                'search_rank' => $this->calculateSearchRank($item, $query),
            ];
        }

        // Sort by search rank (exact matches first, then partial matches)
        usort($results, function ($a, $b) {
            return $b['search_rank'] - $a['search_rank'];
        });

        return array_slice($results, 0, 10); // Limit to 10 results total
    }

    /**
     * Calculate search ranking for better results
     */
    private function calculateSearchRank($asset, string $query): int
    {
        $rank = 0;
        $queryLower = strtolower($query);
        
        // Exact match on code gets highest rank
        if (strtolower($asset->material_code ?? $asset->tool_code ?? $asset->model_code ?? '') === $queryLower) {
            $rank += 100;
        }
        
        // Exact match on name gets high rank
        if (strtolower($asset->name) === $queryLower) {
            $rank += 80;
        }
        
        // Partial match on name/code
        if (stripos($asset->name, $query) !== false) {
            $rank += 50;
        }
        
        if (stripos($asset->material_code ?? $asset->tool_code ?? $asset->model_code ?? '', $query) !== false) {
            $rank += 40;
        }
        
        // Match in other fields
        if (isset($asset->type) && stripos($asset->type, $query) !== false) {
            $rank += 20;
        }
        
        if (isset($asset->location) && stripos($asset->location, $query) !== false) {
            $rank += 15;
        }
        
        // Recent issues (higher priority for items with issues)
        if (isset($asset->reports_count) && $asset->reports_count > 0) {
            $rank += min($asset->reports_count * 5, 25);
        }
        
        return $rank;
    }

    /**
     * Get asset with all necessary relations for modal
     */
    private function getAssetWithRelations(string $type, int $id): ?array
    {
        switch ($type) {
            case 'material':
                $asset = AssetMaterial::with([
                    'reports' => function ($query) {
                        $query->with(['user', 'resolver'])
                              ->latest()
                              ->limit(5);
                    }
                ])->find($id);

                if (!$asset) return null;

                return [
                    'id' => $asset->id,
                    'type' => 'material',
                    'name' => $asset->name,
                    'code' => $asset->material_code,
                    'icon' => 'ph-package',
                    'quantity' => $asset->quantity,
                    'unit' => $asset->unit,
                    'min_threshold' => $asset->min_threshold,
                    'location' => $asset->location,
                    'supplier' => $asset->supplier,
                    'unit_price' => $asset->unit_price,
                    'entry_date' => $asset->entry_date?->format('d M Y'),
                    'expiry_date' => $asset->expiry_date?->format('d M Y'),
                    'type_name' => $asset->type,
                    'description' => $asset->description,
                    'qr_code_path' => $asset->qr_code_path,
                    'status' => $asset->stock_status,
                    'status_label' => $asset->stock_status_label,
                    'total_value' => $asset->quantity * $asset->unit_price,
                    'is_low_stock' => $asset->isLowStock(),
                    'is_out_of_stock' => $asset->isOutOfStock(),
                    'reports' => $asset->reports->map(function ($report) {
                        return [
                            'id' => $report->id,
                            'report_code' => $report->report_code,
                            'issue_type' => $report->issue_type->value,
                            'priority' => $report->priority->value,
                            'status' => $report->status->value,
                            'description' => $report->description,
                            'created_at' => $report->created_at->format('d M Y H:i'),
                            'resolved_at' => $report->resolved_at?->format('d M Y H:i'),
                            'user' => [
                                'name' => $report->user?->name,
                                'email' => $report->user?->email,
                            ],
                            'resolver' => [
                                'name' => $report->resolver?->name,
                                'email' => $report->resolver?->email,
                            ],
                        ];
                    })->toArray(),
                    'detail_url' => route('assets.materials.show', $asset->id),
                    'created_at' => $asset->created_at->format('d M Y H:i'),
                    'updated_at' => $asset->updated_at->format('d M Y H:i'),
                ];

            case 'tool':
                $asset = AssetTool::with([
                    'reports' => function ($query) {
                        $query->with(['user', 'resolver'])
                              ->latest()
                              ->limit(5);
                    }
                ])->find($id);

                if (!$asset) return null;

                return [
                    'id' => $asset->id,
                    'type' => 'tool',
                    'name' => $asset->name,
                    'code' => $asset->tool_code,
                    'icon' => 'ph-wrench',
                    'quantity' => $asset->quantity,
                    'unit' => $asset->unit,
                    'location' => $asset->location,
                    'supplier' => $asset->supplier,
                    'purchase_price' => $asset->purchase_price,
                    'purchase_date' => $asset->purchase_date?->format('d M Y'),
                    'warranty_expiry' => $asset->warranty_expiry?->format('d M Y'),
                    'category' => $asset->category,
                    'brand' => $asset->brand,
                    'description' => $asset->description,
                    'qr_code_path' => $asset->qr_code_path,
                    'status' => $asset->condition ?? 'unknown',
                    'status_label' => $this->translateCondition($asset->condition ?? 'Unknown'),
                    'total_value' => $asset->quantity * $asset->purchase_price,
                    'is_under_warranty' => $asset->warranty_expiry && $asset->warranty_expiry->isFuture(),
                    'reports' => $asset->reports->map(function ($report) {
                        return [
                            'id' => $report->id,
                            'report_code' => $report->report_code,
                            'issue_type' => $report->issue_type->value,
                            'priority' => $report->priority->value,
                            'status' => $report->status->value,
                            'description' => $report->description,
                            'created_at' => $report->created_at->format('d M Y H:i'),
                            'resolved_at' => $report->resolved_at?->format('d M Y H:i'),
                            'user' => [
                                'name' => $report->user?->name,
                                'email' => $report->user?->email,
                            ],
                            'resolver' => [
                                'name' => $report->resolver?->name,
                                'email' => $report->resolver?->email,
                            ],
                        ];
                    })->toArray(),
                    'detail_url' => route('assets.tools.show', $asset->id),
                    'created_at' => $asset->created_at->format('d M Y H:i'),
                    'updated_at' => $asset->updated_at->format('d M Y H:i'),
                ];

            case 'model':
                $asset = AssetModel::with([
                    'reports' => function ($query) {
                        $query->with(['user', 'resolver'])
                              ->latest()
                              ->limit(5);
                    }
                ])->find($id);

                if (!$asset) return null;

                return [
                    'id' => $asset->id,
                    'type' => 'model',
                    'name' => $asset->name,
                    'code' => $asset->model_code,
                    'icon' => 'ph-cube',
                    'quantity' => $asset->quantity,
                    'location' => $asset->location,
                    'supplier' => $asset->supplier,
                    'manufacture_date' => $asset->manufacture_date?->format('d M Y'),
                    'type_name' => $asset->type,
                    'description' => $asset->description,
                    'qr_code_path' => $asset->qr_code_path,
                    'status' => $asset->condition ?? 'unknown',
                    'status_label' => $this->translateCondition($asset->condition ?? 'Unknown'),
                    'unit_price' => $asset->unit_price,
                    'total_value' => $asset->quantity * ($asset->unit_price ?? 0),
                    'reports' => $asset->reports->map(function ($report) {
                        return [
                            'id' => $report->id,
                            'report_code' => $report->report_code,
                            'issue_type' => $report->issue_type->value,
                            'priority' => $report->priority->value,
                            'status' => $report->status->value,
                            'description' => $report->description,
                            'created_at' => $report->created_at->format('d M Y H:i'),
                            'resolved_at' => $report->resolved_at?->format('d M Y H:i'),
                            'user' => [
                                'name' => $report->user?->name,
                                'email' => $report->user?->email,
                            ],
                            'resolver' => [
                                'name' => $report->resolver?->name,
                                'email' => $report->resolver?->email,
                            ],
                        ];
                    })->toArray(),
                    'detail_url' => route('assets.models.show', $asset->id),
                    'created_at' => $asset->created_at->format('d M Y H:i'),
                    'updated_at' => $asset->updated_at->format('d M Y H:i'),
                ];

            default:
                return null;
        }
    }

    /**
     * Translate condition to Indonesian
     */
    private function translateCondition(string $condition): string
    {
        return match($condition) {
            'Good' => 'Baik',
            'Maintenance' => 'Perbaikan',
            'Repair' => 'Perbaikan',
            'Broken' => 'Rusak',
            'Damaged' => 'Rusak',
            'Lost' => 'Hilang',
            'Disposed' => 'Dibuang',
            'Unknown' => 'Tidak Diketahui',
            default => $condition,
        };
    }

    /**
     * Get quick search suggestions for autocomplete
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:1|max:50',
        ]);

        $query = $request->input('query');
        $suggestions = [];

        // Get unique material codes, names, and types
        $materialSuggestions = AssetMaterial::where('name', 'like', "%{$query}%")
            ->orWhere('material_code', 'like', "%{$query}%")
            ->orWhere('type', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('name', 'material_code')
            ->toArray();

        // Get unique tool codes, names, and categories
        $toolSuggestions = AssetTool::where('name', 'like', "%{$query}%")
            ->orWhere('tool_code', 'like', "%{$query}%")
            ->orWhere('category', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('name', 'tool_code')
            ->toArray();

        // Get unique model codes and names
        $modelSuggestions = AssetModel::where('name', 'like', "%{$query}%")
            ->orWhere('model_code', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('name', 'model_code')
            ->toArray();

        // Combine and limit suggestions
        $allSuggestions = array_merge(
            array_keys($materialSuggestions),
            array_keys($toolSuggestions),
            array_keys($modelSuggestions),
            array_values($materialSuggestions),
            array_values($toolSuggestions),
            array_values($modelSuggestions)
        );

        $suggestions = array_unique($allSuggestions);
        $suggestions = array_slice($suggestions, 0, 10);

        return response()->json([
            'success' => true,
            'data' => array_values($suggestions),
        ]);
    }
}