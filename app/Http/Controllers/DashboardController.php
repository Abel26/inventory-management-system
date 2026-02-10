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
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index(): View
    {
        try {
            $stats = $this->getStats();
            $chartData = [
                'trend' => $this->getTrendData(),
                'composition' => $this->getCompositionData(),
            ];
            $activity = $this->getRecentActivity();
            $critical = $this->getCriticalItems();
            $greeting = $this->getGreeting();

            return view('dashboard', compact('stats', 'chartData', 'activity', 'critical', 'greeting'));
        } catch (\Exception $e) {
            // Log error and provide fallback data
            Log::error('Dashboard error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback data to prevent errors
            $stats = [
                'totalAssetValue' => 0,
                'valueBreakdown' => ['material' => 0, 'tool' => 0, 'model' => 0],
                'totalCriticalIssues' => 0,
                'lowStockAlerts' => 0,
                'activeUsers' => 0,
                'totalMaterials' => 0,
                'totalTools' => 0,
                'totalModels' => 0,
                'totalReports' => 0,
            ];
            
            $chartData = [
                'trend' => [
                    'dates' => [],
                    'categories' => [],
                    'series' => [
                        ['name' => 'Total Laporan', 'data' => []],
                        ['name' => 'Laporan Selesai', 'data' => []]
                    ]
                ],
                'composition' => [
                    'labels' => ['Materials', 'Tools', 'Models'],
                    'counts' => [0, 0, 0],
                    'percentages' => [0, 0, 0],
                    'series' => [0, 0, 0]
                ]
            ];
            
            $activity = [];
            $critical = [];
            $greeting = $this->getGreeting();

            return view('dashboard', compact('stats', 'chartData', 'activity', 'critical', 'greeting'));
        }
    }

    /**
     * Get dynamic greeting based on time
     */
    private function getGreeting(): string
    {
        // Gunakan timezone Asia/Jakarta secara eksplisit untuk greeting
        $hour = now()->timezone('Asia/Jakarta')->format('H');
        
        if ($hour >= 5 && $hour < 11) {
            return 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            return 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 19) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    }

    /**
     * Get dashboard statistics
     */
    private function getStats(): array
    {
        try {
            // Cache statistics for 5 minutes to improve performance
            return Cache::remember('dashboard.stats', now()->timezone('Asia/Jakarta')->addMinutes(5), function () {
                // Calculate total asset value from materials, tools, and models
                $materialValue = AssetMaterial::selectRaw('SUM(unit_price * quantity) as total')->first()->total ?? 0;
                $toolValue = AssetTool::selectRaw('SUM(purchase_price * quantity) as total')->first()->total ?? 0;
                // AssetModels don't have price field, so we'll count them as quantity units
                $modelValue = AssetModel::count() * 1000000; // Estimated value per model
                $totalAssetValue = $materialValue + $toolValue + $modelValue;
                
                return [
                    'totalAssetValue' => $totalAssetValue,
                    'valueBreakdown' => [
                        'material' => $materialValue,
                        'tool' => $toolValue,
                        'model' => $modelValue
                    ],
                    'totalCriticalIssues' => Report::where('priority', 'Critical')->where('status', '!=', 'Resolved')->count(),
                    'lowStockAlerts' => AssetMaterial::whereColumn('quantity', '<=', 'min_threshold')->where('quantity', '>', 0)->count(),
                    'activeUsers' => User::whereNotNull('email_verified_at')->where('created_at', '>=', now()->timezone('Asia/Jakarta')->subDays(30))->count(),
                    'totalMaterials' => AssetMaterial::count(),
                    'totalTools' => AssetTool::count(),
                    'totalModels' => AssetModel::count(),
                    'totalReports' => Report::count(),
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error getting dashboard stats: ' . $e->getMessage());
            // Return fallback data
            return [
                'totalAssetValue' => 0,
                'valueBreakdown' => ['material' => 0, 'tool' => 0, 'model' => 0],
                'totalCriticalIssues' => 0,
                'lowStockAlerts' => 0,
                'activeUsers' => 0,
                'totalMaterials' => 0,
                'totalTools' => 0,
                'totalModels' => 0,
                'totalReports' => 0,
            ];
        }
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity(int $limit = 5): array
    {
        try {
            // Cache recent activity for 3 minutes
            return Cache::remember("dashboard.activity.{$limit}", now()->timezone('Asia/Jakarta')->addMinutes(3), function () use ($limit) {
                $reports = Report::with(['user', 'reportable'])
                    ->latest()
                    ->take($limit)
                    ->get();

                return $reports->map(function ($report) {
                    return [
                        'message' => $this->formatReportActivity($report),
                        'time' => $report->created_at->diffForHumans(),
                        'icon' => $this->getActivityIcon($report->issue_type->value ?? 'general', 'report'),
                        'color' => $this->getActivityColor($report->priority->value ?? 'normal', 'priority'),
                        'user' => $report->user->name ?? 'System',
                    ];
                })->toArray();
            });
        } catch (\Exception $e) {
            Log::error('Error getting recent activity: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get critical items
     */
    private function getCriticalItems(int $limit = 10): array
    {
        try {
            // Cache critical items for 5 minutes
            return Cache::remember("dashboard.critical.{$limit}", now()->timezone('Asia/Jakarta')->addMinutes(5), function () use ($limit) {
                // Fetch critical/unresolved reports
                $criticalReports = Report::with(['reportable'])
                    ->where('priority', 'Critical')
                    ->where('status', '!=', 'Resolved')
                    ->take($limit)
                    ->get();
                    
                // Map to a unified structure
                $items = $criticalReports->map(function ($report) {
                    $assetName = $report->reportable->name ?? 'Unknown Asset';
                    $assetCode = $report->reportable->material_code ?? $report->reportable->tool_code ?? $report->reportable->model_code ?? '-';
                    
                    // Determine detailed URL based on asset type (basic mapping)
                    // Ideally should use polymorphic route helper or check instance type
                    $actionUrl = '#'; // Default
                     if ($report->reportable_type === \App\Models\AssetMaterial::class) {
                        $actionUrl = route('assets.materials.show', $report->reportable_id);
                    } elseif ($report->reportable_type === \App\Models\AssetTool::class) {
                        $actionUrl = route('assets.tools.show', $report->reportable_id);
                    } elseif ($report->reportable_type === \App\Models\AssetModel::class) {
                        $actionUrl = route('assets.models.show', $report->reportable_id);
                    }

                    return [
                        'name' => $assetName,
                        'code' => $assetCode,
                        'status' => 'critical',
                        'status_display' => 'Kritis',
                        'action_url' => $actionUrl,
                        'priority' => 'Critical'
                    ];
                });

                // Add low stock items if space permits
                if ($items->count() < $limit) {
                    $lowStock = AssetMaterial::where('quantity', '<=', 5)
                        ->take($limit - $items->count())
                        ->get()
                        ->map(function ($item) {
                            return [
                                'name' => $item->name,
                                'code' => $item->material_code,
                                'status' => 'low_stock',
                                'status_display' => 'Stok Menipis',
                                'action_url' => route('assets.materials.show', $item->id),
                                'priority' => 'High'
                            ];
                        });
                    
                    $items = $items->merge($lowStock);
                }

                return $items->toArray();
            });
        } catch (\Exception $e) {
            Log::error('Error getting critical items: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get trend data for the last 7 days
     */
    private function getTrendData(): array
    {
        try {
            // Cache trend data for 10 minutes
            return Cache::remember('dashboard.trend', now()->timezone('Asia/Jakarta')->addMinutes(10), function () {
                $dates = [];
                $totalReports = [];
                $resolvedReports = [];
                
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->timezone('Asia/Jakarta')->subDays($i)->format('Y-m-d');
                    $dates[] = $date;
                    
                    // Total reports for the day
                    $totalReports[] = Report::whereDate('created_at', $date)->count();
                    
                    // Resolved reports for the day
                    $resolvedReports[] = Report::whereDate('resolved_at', $date)->count();
                }

                return [
                    'dates' => $dates,
                    'categories' => collect($dates)->map(function($date) {
                        return \Carbon\Carbon::parse($date)->format('D'); // Mon, Tue, etc.
                    })->toArray(),
                    'series' => [
                        [
                            'name' => 'Total Laporan',
                            'data' => $totalReports
                        ],
                        [
                            'name' => 'Laporan Selesai',
                            'data' => $resolvedReports
                        ]
                    ]
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error getting trend data: ' . $e->getMessage());
            // Return fallback data
            return [
                'dates' => [],
                'categories' => [],
                'series' => [
                    ['name' => 'Total Laporan', 'data' => []],
                    ['name' => 'Laporan Selesai', 'data' => []]
                ]
            ];
        }
    }

    /**
     * Get composition data for assets
     */
    private function getCompositionData(): array
    {
        try {
            // Cache composition data for 15 minutes
            return Cache::remember('dashboard.composition', now()->timezone('Asia/Jakarta')->addMinutes(15), function () {
                $materialCount = AssetMaterial::count();
                $toolCount = AssetTool::count();
                $modelCount = AssetModel::count();
                
                $total = $materialCount + $toolCount + $modelCount;
                
                $percentages = [];
                if ($total > 0) {
                    $percentages[] = round(($materialCount / $total) * 100, 1);
                    $percentages[] = round(($toolCount / $total) * 100, 1);
                    $percentages[] = round(($modelCount / $total) * 100, 1);
                } else {
                    $percentages = [0, 0, 0];
                }

                return [
                    'labels' => ['Materials', 'Tools', 'Models'],
                    'counts' => [$materialCount, $toolCount, $modelCount],
                    'percentages' => $percentages,
                    'series' => [$materialCount, $toolCount, $modelCount]
                ];
            });
        } catch (\Exception $e) {
            Log::error('Error getting composition data: ' . $e->getMessage());
            // Return fallback data
            return [
                'labels' => ['Materials', 'Tools', 'Models'],
                'counts' => [0, 0, 0],
                'percentages' => [0, 0, 0],
                'series' => [0, 0, 0]
            ];
        }
    }

    /**
     * Format report activity message
     */
    private function formatReportActivity(Report $report): string
    {
        $user = $report->user->name ?? 'Someone';
        $type = $report->issue_type->value ?? 'Issue';
        $asset = $report->reportable->name ?? 'Unknown Asset';
        
        if ($report->status->value === 'Resolved') {
            return "$user menyelesaikan masalah pada $asset";
        }
        
        return "$user melaporkan $type pada $asset";
    }

    /**
     * Get activity icon based on type
     */
    private function getActivityIcon(string $type, string $context): string
    {
        if ($context === 'report') {
            return match($type) {
                'Lost' => 'ph-warning-circle',
                'Damaged' => 'ph-wrench',
                'Maintenance' => 'ph-gear',
                default => 'ph-info'
            };
        }
        return 'ph-circle';
    }

    /**
     * Get activity color based on type
     */
    private function getActivityColor(string $type, string $context): string
    {
        if ($context === 'priority') {
            return match($type) {
                'Critical' => 'danger',
                'High' => 'warning',
                'Medium' => 'info',
                default => 'secondary'
            };
        }
        return 'primary';
    }

    /**
     * API endpoint to get filtered reports for chart interactions
     */
    public function getReportsByDate(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->input('date');
        
        $reports = Report::with(['user', 'reportable'])
            ->whereDate('created_at', $date)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reports,
            'total' => $reports->count(),
        ]);
    }

    /**
     * API endpoint to get assets by type for chart interactions
     */
    public function getAssetsByType(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:material,tool,model',
        ]);

        $type = $request->input('type');
        $assets = [];

        switch ($type) {
            case 'material':
                $assets = AssetMaterial::withCount(['reports'])
                    ->latest()
                    ->get();
                break;
            case 'tool':
                $assets = AssetTool::withCount(['reports'])
                    ->latest()
                    ->get();
                break;
            case 'model':
                $assets = AssetModel::withCount(['reports'])
                    ->latest()
                    ->get();
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $assets,
            'total' => $assets->count(),
        ]);
    }
}
