<?php

namespace App\Services;

use App\Models\AssetMaterial;
use App\Models\AssetTool;
use App\Models\AssetModel;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Cache duration in minutes
     */
    private const CACHE_DURATION = 5;

    /**
     * Translation mappings for localization
     */
    private const STATUS_TRANSLATION = [
        'Pending' => 'Menunggu',
        'In Progress' => 'Diproses',
        'Resolved' => 'Selesai',
        'Rejected' => 'Ditolak',
    ];

    private const PRIORITY_TRANSLATION = [
        'Critical' => 'Kritis',
        'High' => 'Tinggi',
        'Medium' => 'Sedang',
        'Low' => 'Rendah',
    ];

    private const CONDITION_TRANSLATION = [
        'Good' => 'Baik',
        'Maintenance' => 'Perbaikan',
        'Broken' => 'Rusak',
        'Lost' => 'Hilang',
    ];

    private const ASSET_TYPE_TRANSLATION = [
        'Material' => 'Material',
        'Tools' => 'Peralatan',
        'Models' => 'Cetakan',
    ];

    /**
     * Translate status to Indonesian
     */
    private function translateStatus(string $status): string
    {
        return self::STATUS_TRANSLATION[$status] ?? $status;
    }

    /**
     * Translate priority to Indonesian
     */
    private function translatePriority(string $priority): string
    {
        return self::PRIORITY_TRANSLATION[$priority] ?? $priority;
    }

    /**
     * Translate condition to Indonesian
     */
    private function translateCondition(string $condition): string
    {
        return self::CONDITION_TRANSLATION[$condition] ?? $condition;
    }

    /**
     * Translate asset type to Indonesian
     */
    private function translateAssetType(string $type): string
    {
        return self::ASSET_TYPE_TRANSLATION[$type] ?? $type;
    }

    /**
     * Get all dashboard data with caching
     */
    public function getDashboardData(): array
    {
        return [
            'stats' => $this->getStats(),
            'charts' => $this->getChartsData(),
            'activity' => $this->getRecentActivity(),
            'critical' => $this->getCriticalItems(),
            'resolvedTrend' => $this->getResolvedTrend(),
        ];
    }

    /**
     * Get dashboard statistics (Pulse Cards)
     */
    public function getStats(): array
    {
        return Cache::remember('dashboard.stats', now()->addMinutes(self::CACHE_DURATION), function () {
            return [
                'totalAssetValue' => $this->getTotalAssetValue(),
                'totalCriticalIssues' => $this->getTotalCriticalIssues(),
                'lowStockAlerts' => $this->getLowStockAlerts(),
                'activeUsers' => $this->getActiveUsers(),
                'totalMaterials' => AssetMaterial::count(),
                'totalTools' => AssetTool::count(),
                'totalModels' => AssetModel::count(),
                'totalReports' => Report::count(),
            ];
        });
    }

    /**
     * Get charts data for visualization
     */
    public function getChartsData(): array
    {
        return Cache::remember('dashboard.charts', now()->addMinutes(self::CACHE_DURATION), function () {
            return [
                'monthlyTrend' => $this->getMonthlyTrend(),
                'assetComposition' => $this->getAssetComposition(),
                'assetsByLocation' => $this->getAssetsByLocation(),
                'assetStatusBreakdown' => $this->getAssetStatusBreakdown(),
                'reportStatusBreakdown' => $this->getReportStatusBreakdown(),
                'radarData' => $this->getRadarData(),
                'treemapData' => $this->getTreemapData(),
                'healthGauge' => $this->getHealthGauge(),
                'stackedColumnData' => $this->getStackedColumnData(),
            ];
        });
    }

    /**
     * Get recent activity feed
     */
    public function getRecentActivity(int $limit = 5): array
    {
        return Cache::remember('dashboard.activity', now()->addMinutes(2), function () use ($limit) {
            $activities = [];

            // Get recent reports
            $recentReports = Report::with(['user', 'reportable'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            foreach ($recentReports as $report) {
                $activities[] = [
                    'type' => 'report',
                    'message' => $this->formatReportActivity($report),
                    'time' => $report->created_at->diffForHumans(),
                    'icon' => $this->getActivityIcon('report', $report->issue_type->value),
                    'color' => $this->getActivityColor('report', $report->priority->value),
                ];
            }

            return $activities;
        });
    }

    /**
     * Get critical items that need attention
     */
    public function getCriticalItems(int $limit = 10): array
    {
        return Cache::remember('dashboard.critical', now()->addMinutes(self::CACHE_DURATION), function () use ($limit) {
            $criticalItems = [];

            // Out of stock materials
            $outOfStock = AssetMaterial::where('quantity', '<=', 0)
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'code' => $item->material_code,
                        'type' => $this->translateAssetType('Material'),
                        'stock' => $item->quantity,
                        'threshold' => $item->min_threshold,
                        'status' => 'out_of_stock',
                        'status_display' => 'Stok Habis',
                        'action_url' => route('assets.materials.show', $item->id),
                    ];
                });

            // Low stock materials
            $lowStock = AssetMaterial::whereColumn('quantity', '<=', 'min_threshold')
                ->where('quantity', '>', 0)
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'code' => $item->material_code,
                        'type' => $this->translateAssetType('Material'),
                        'stock' => $item->quantity,
                        'threshold' => $item->min_threshold,
                        'status' => 'low_stock',
                        'status_display' => 'Stok Menipis',
                        'action_url' => route('assets.materials.show', $item->id),
                    ];
                });

            // Critical priority reports
            $criticalReports = Report::where('priority', 'Critical')
                ->where('status', '!=', 'Resolved')
                ->with(['reportable'])
                ->limit($limit)
                ->get()
                ->map(function ($report) {
                    return [
                        'id' => $report->id,
                        'name' => $report->reportable ? $report->reportable->name ?? 'Unknown' : 'Unknown',
                        'code' => $report->report_code,
                        'type' => 'Laporan',
                        'stock' => '-',
                        'threshold' => '-',
                        'status' => 'critical',
                        'status_display' => $this->translatePriority('Critical'),
                        'action_url' => route('reports.show', $report->id),
                    ];
                });

            $criticalItems = array_merge($outOfStock->toArray(), $lowStock->toArray(), $criticalReports->toArray());

            // Sort by status priority
            usort($criticalItems, function ($a, $b) {
                $priority = ['critical' => 0, 'out_of_stock' => 1, 'low_stock' => 2];
                return $priority[$a['status']] - $priority[$b['status']];
            });

            return array_slice($criticalItems, 0, $limit);
        });
    }

    /**
     * Search across all asset models
     */
    public function searchAssets(string $query): array
    {
        $results = [];

        // Search materials
        $materials = AssetMaterial::where('name', 'like', "%{$query}%")
            ->orWhere('material_code', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->material_code,
                    'type' => $this->translateAssetType('Material'),
                    'url' => route('assets.materials.show', $item->id),
                    'icon' => 'ph-package',
                ];
            });

        // Search tools
        $tools = AssetTool::where('name', 'like', "%{$query}%")
            ->orWhere('tool_code', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->tool_code,
                    'type' => $this->translateAssetType('Tools'),
                    'url' => route('assets.tools.show', $item->id),
                    'icon' => 'ph-wrench',
                ];
            });

        // Search models
        $models = AssetModel::where('name', 'like', "%{$query}%")
            ->orWhere('model_code', 'like', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->model_code,
                    'type' => $this->translateAssetType('Models'),
                    'url' => route('assets.models.show', $item->id),
                    'icon' => 'ph-cube',
                ];
            });

        $results = array_merge($materials->toArray(), $tools->toArray(), $models->toArray());

        return [
            'success' => true,
            'data' => $results,
            'total' => count($results),
        ];
    }

    /**
     * Get chart data by timeframe
     */
    public function getChartDataByTimeframe(string $timeframe): array
    {
        Cache::forget('dashboard.charts');
        return $this->getChartsData();
    }

    /**
     * Get total asset value
     */
    private function getTotalAssetValue(): float
    {
        return (float) AssetMaterial::selectRaw('SUM(quantity * unit_price) as total')
            ->value('total') ?? 0;
    }

    /**
     * Get total critical issues
     */
    private function getTotalCriticalIssues(): int
    {
        return Report::where('priority', 'Critical')
            ->where('status', '!=', 'Resolved')
            ->count();
    }

    /**
     * Get low stock alerts count
     */
    private function getLowStockAlerts(): int
    {
        return AssetMaterial::whereColumn('quantity', '<=', 'min_threshold')
            ->where('quantity', '>', 0)
            ->count();
    }

    /**
     * Get active users count (last 30 days)
     */
    private function getActiveUsers(): int
    {
        return User::whereNotNull('email_verified_at')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
    }

    /**
     * Get monthly trend data (last 12 months)
     */
    private function getMonthlyTrend(): array
    {
        // Generate all 12 months from current date backwards
        $categories = [];
        $data = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $categories[] = $date->format('M Y');
            
            // Get count for this month
            $count = Report::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $data[] = $count;
        }

        return [
            'categories' => $categories,
            'series' => [
                [
                    'name' => 'Laporan',
                    'data' => $data,
                ]
            ]
        ];
    }

    /**
     * Get resolved trend data (last 7 days for dashboard)
     */
    public function getResolvedTrend(): array
    {
        $categories = [];
        $totalReports = [];
        $resolvedReports = [];
        
        // Generate last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $categories[] = $date->locale('id')->format('D');
            
            $total = Report::whereDate('created_at', $date->format('Y-m-d'))->count();
            $resolved = Report::whereDate('resolved_at', $date->format('Y-m-d'))->count();
            
            $totalReports[] = $total;
            $resolvedReports[] = $resolved;
        }

        return [
            'categories' => $categories,
            'total' => $totalReports,
            'resolved' => $resolvedReports,
        ];
    }

    /**
     * Get asset composition
     */
    private function getAssetComposition(): array
    {
        $materialsCount = AssetMaterial::count();
        $toolsCount = AssetTool::count();
        $modelsCount = AssetModel::count();

        return [
            'labels' => [
                $this->translateAssetType('Material'),
                $this->translateAssetType('Tools'),
                $this->translateAssetType('Models'),
            ],
            'series' => [$materialsCount, $toolsCount, $modelsCount],
        ];
    }

    /**
     * Get assets by location
     */
    private function getAssetsByLocation(): array
    {
        $locations = [];

        // Materials by location
        $materialLocations = AssetMaterial::whereNotNull('location')
            ->select('location', DB::raw('COUNT(*) as count'))
            ->groupBy('location')
            ->get();

        foreach ($materialLocations as $loc) {
            $locations[$loc->location] = ($locations[$loc->location] ?? 0) + $loc->count;
        }

        // Tools by location
        $toolLocations = AssetTool::whereNotNull('location')
            ->select('location', DB::raw('COUNT(*) as count'))
            ->groupBy('location')
            ->get();

        foreach ($toolLocations as $loc) {
            $locations[$loc->location] = ($locations[$loc->location] ?? 0) + $loc->count;
        }

        // Models by location
        $modelLocations = AssetModel::whereNotNull('location')
            ->select('location', DB::raw('COUNT(*) as count'))
            ->groupBy('location')
            ->get();

        foreach ($modelLocations as $loc) {
            $locations[$loc->location] = ($locations[$loc->location] ?? 0) + $loc->count;
        }

        ksort($locations);

        return [
            'categories' => array_keys($locations),
            'series' => [
                [
                    'name' => 'Aset',
                    'data' => array_values($locations),
                ]
            ]
        ];
    }

    /**
     * Get asset status breakdown
     */
    private function getAssetStatusBreakdown(): array
    {
        // Materials
        $materialStatus = [
            'good' => AssetMaterial::whereColumn('quantity', '>', 'min_threshold')->count(),
            'low' => AssetMaterial::whereColumn('quantity', '<=', 'min_threshold')
                ->where('quantity', '>', 0)
                ->count(),
            'out' => AssetMaterial::where('quantity', '<=', 0)->count(),
        ];

        // Tools
        $toolStatus = AssetTool::groupBy('condition')
            ->select('condition', DB::raw('COUNT(*) as count'))
            ->pluck('count', 'condition')
            ->toArray();

        // Models
        $modelStatus = AssetModel::groupBy('condition')
            ->select('condition', DB::raw('COUNT(*) as count'))
            ->pluck('count', 'condition')
            ->toArray();

        return [
            'materials' => $materialStatus,
            'tools' => $toolStatus,
            'models' => $modelStatus,
        ];
    }

    /**
     * Get report status breakdown
     */
    private function getReportStatusBreakdown(): array
    {
        $reportStatus = Report::groupBy('status')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->pluck('count', 'status')
            ->toArray();

        // Translate status labels to Indonesian
        $translatedLabels = [];
        foreach (array_keys($reportStatus) as $status) {
            $translatedLabels[] = $this->translateStatus($status);
        }

        return [
            'labels' => $translatedLabels,
            'series' => array_values($reportStatus),
        ];
    }

    /**
     * Get radar chart data for asset category performance
     * Normalizes values to 0-100 scale
     */
    private function getRadarData(): array
    {
        // Get max values for normalization
        $maxQuantity = max(
            AssetMaterial::max('quantity') ?? 1,
            AssetTool::max('quantity') ?? 1,
            AssetModel::count() ?? 1
        );

        $maxValue = max($maxQuantity, 1);

        // Get materials data
        $materials = AssetMaterial::select([
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(quantity * unit_price) as total_value'),
        ])->first();

        // Get tools data
        $tools = AssetTool::select([
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(quantity) as total_quantity'),
        ])->first();

        // Get models data
        $models = AssetModel::select([
            DB::raw('COUNT(*) as count'),
        ])->first();

        // Normalize to 0-100 scale
        $materialCount = $materials->count ?? 0;
        $toolCount = $tools->count ?? 0;
        $modelCount = $models->count ?? 0;

        $maxCount = max($materialCount, $toolCount, $modelCount, 1);

        return [
            'series' => [
                [
                    'name' => $this->translateAssetType('Material'),
                    'data' => [
                        round(($materialCount / $maxCount) * 100),
                        round((($materials->total_quantity ?? 0) / $maxValue) * 100),
                        round((($materials->total_value ?? 0) / ($maxValue * 100)) * 100),
                    ],
                ],
                [
                    'name' => $this->translateAssetType('Tools'),
                    'data' => [
                        round(($toolCount / $maxCount) * 100),
                        round((($tools->total_quantity ?? 0) / $maxValue) * 100),
                        round((($tools->total_quantity ?? 0) / $maxValue) * 100),
                    ],
                ],
                [
                    'name' => $this->translateAssetType('Models'),
                    'data' => [
                        round(($modelCount / $maxCount) * 100),
                        round(($modelCount / $maxCount) * 100),
                        round(($modelCount / $maxCount) * 100),
                    ],
                ],
            ],
            'categories' => ['Jumlah', 'Total Kuantitas', 'Total Nilai'],
        ];
    }

    /**
     * Get treemap data for inventory distribution
     */
    private function getTreemapData(): array
    {
        // Group assets by category/type
        $treemapData = [];

        // Materials by type
        $materialTypes = AssetMaterial::select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();

        foreach ($materialTypes as $type) {
            $treemapData[] = [
                'x' => 0,
                'y' => 0,
                'value' => $type->count,
                'name' => $type->type ?? 'Umum',
                'category' => $this->translateAssetType('Material'),
            ];
        }

        // Tools by category
        $toolCategories = AssetTool::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->get();

        foreach ($toolCategories as $cat) {
            $treemapData[] = [
                'x' => 0,
                'y' => 0,
                'value' => $cat->count,
                'name' => $cat->category ?? 'Umum',
                'category' => $this->translateAssetType('Tools'),
            ];
        }

        // Models by type
        $modelTypes = AssetModel::select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();

        foreach ($modelTypes as $type) {
            $treemapData[] = [
                'x' => 0,
                'y' => 0,
                'value' => $type->count,
                'name' => $type->type ?? 'Umum',
                'category' => $this->translateAssetType('Models'),
            ];
        }

        // Sort by value (largest first)
        usort($treemapData, function ($a, $b) {
            return $b['value'] - $a['value'];
        });

        return [
            'series' => [
                [
                    'data' => array_slice($treemapData, 0, 15), // Top 15 items
                ],
            ],
        ];
    }

    /**
     * Get health gauge data (system health score)
     */
    private function getHealthGauge(): array
    {
        // Calculate total assets
        $totalMaterials = AssetMaterial::count();
        $totalTools = AssetTool::count();
        $totalModels = AssetModel::count();
        $totalAssets = $totalMaterials + $totalTools + $totalModels;

        // Calculate healthy assets
        $healthyMaterials = AssetMaterial::whereColumn('quantity', '>', 'min_threshold')->count();
        $healthyTools = AssetTool::where('condition', 'Good')->count();
        $healthyModels = AssetModel::where('condition', 'Good')->count();
        $totalHealthy = $healthyMaterials + $healthyTools + $healthyModels;

        // Calculate health percentage
        $healthPercentage = $totalAssets > 0 ? round(($totalHealthy / $totalAssets) * 100, 1) : 0;

        // Determine health status
        if ($healthPercentage >= 80) {
            $status = 'Sangat Baik';
            $color = '#10B981'; // green
        } elseif ($healthPercentage >= 60) {
            $status = 'Baik';
            $color = '#009B77'; // ebara
        } elseif ($healthPercentage >= 40) {
            $status = 'Cukup';
            $color = '#F59E0B'; // warning
        } else {
            $status = 'Buruk';
            $color = '#EF4444'; // danger
        }

        return [
            'series' => [$healthPercentage],
            'status' => $status,
            'color' => $color,
            'totalAssets' => $totalAssets,
            'healthyAssets' => $totalHealthy,
        ];
    }

    /**
     * Get stacked column data for issues by status per month
     */
    private function getStackedColumnData(): array
    {
        $stackedData = Report::select([
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            'status',
            DB::raw('COUNT(*) as count'),
        ])
        ->where('created_at', '>=', now()->subMonths(6)) // Last 6 months
        ->groupBy(['month', 'status'])
        ->orderBy('month')
        ->get()
        ->groupBy('month')
        ->map(function ($monthData) {
            $pending = $monthData->where('status', 'Pending')->sum('count') ?? 0;
            $inProgress = $monthData->where('status', 'In Progress')->sum('count') ?? 0;
            $resolved = $monthData->where('status', 'Resolved')->sum('count') ?? 0;
            $rejected = $monthData->where('status', 'Rejected')->sum('count') ?? 0;

            return [
                'x' => $monthData->first()->month,
                'y' => $pending + $inProgress + $resolved + $rejected,
                'stacks' => [
                    $pending,
                    $inProgress,
                    $resolved,
                    $rejected,
                ],
            ];
        })
        ->values();

        $stackedArray = $stackedData->toArray();

        $categories = array_column($stackedArray, 'x');
        $seriesData = [
            [
                'name' => $this->translateStatus('Pending'),
                'data' => array_column($stackedArray, 'stacks')[0] ?? [],
            ],
            [
                'name' => $this->translateStatus('In Progress'),
                'data' => array_column($stackedArray, 'stacks')[1] ?? [],
            ],
            [
                'name' => $this->translateStatus('Resolved'),
                'data' => array_column($stackedArray, 'stacks')[2] ?? [],
            ],
            [
                'name' => $this->translateStatus('Rejected'),
                'data' => array_column($stackedArray, 'stacks')[3] ?? [],
            ],
        ];

        // Extract stacks data properly
        $pendingData = [];
        $inProgressData = [];
        $resolvedData = [];
        $rejectedData = [];

        foreach ($stackedArray as $item) {
            $pendingData[] = $item['stacks'][0] ?? 0;
            $inProgressData[] = $item['stacks'][1] ?? 0;
            $resolvedData[] = $item['stacks'][2] ?? 0;
            $rejectedData[] = $item['stacks'][3] ?? 0;
        }

        $seriesData = [
            [
                'name' => $this->translateStatus('Pending'),
                'data' => $pendingData,
            ],
            [
                'name' => $this->translateStatus('In Progress'),
                'data' => $inProgressData,
            ],
            [
                'name' => $this->translateStatus('Resolved'),
                'data' => $resolvedData,
            ],
            [
                'name' => $this->translateStatus('Rejected'),
                'data' => $rejectedData,
            ],
        ];

        return [
            'categories' => array_map(function ($month) {
                return date('M Y', strtotime($month . '-01'));
            }, $categories),
            'series' => $seriesData,
        ];
    }

    /**
     * Format report activity message
     */
    private function formatReportActivity(Report $report): string
    {
        $userName = $report->user ? $report->user->name : 'Unknown User';
        $assetName = $report->reportable ? $report->reportable->name : 'Unknown Asset';
        $issueType = $report->issue_type->value;

        return "{$userName} melaporkan {$issueType} pada {$assetName}";
    }

    /**
     * Get activity icon based on type
     */
    private function getActivityIcon(string $type, string $subtype = ''): string
    {
        $icons = [
            'report' => [
                'Damage' => 'ph-warning-octagon',
                'Maintenance' => 'ph-wrench',
                'Lost' => 'ph-warning',
                'Stock Discrepancy' => 'ph-chart-bar',
            ],
        ];

        return $icons[$type][$subtype] ?? 'ph-activity';
    }

    /**
     * Get activity color based on type
     */
    private function getActivityColor(string $type, string $subtype = ''): string
    {
        $colors = [
            'report' => [
                'Damage' => 'danger',
                'Maintenance' => 'warning',
                'Lost' => 'danger',
                'Stock Discrepancy' => 'info',
            ],
        ];

        return $colors[$type][$subtype] ?? 'default';
    }

    /**
     * Clear dashboard cache
     */
    public function clearCache(): void
    {
        Cache::forget('dashboard.stats');
        Cache::forget('dashboard.charts');
        Cache::forget('dashboard.activity');
        Cache::forget('dashboard.critical');
    }
}
