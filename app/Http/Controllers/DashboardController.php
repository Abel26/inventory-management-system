<?php

namespace App\Http\Controllers;

use App\Models\AssetMaterial;
use App\Models\AssetTool;
use App\Models\AssetModel;
use App\Models\Report;
use App\Models\User;
use App\Models\MoldModification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DashboardExport;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index(): View
    {
        try {
            // Ensure user is authenticated
            if (!\Illuminate\Support\Facades\Auth::check()) {
                Log::error('Dashboard accessed without authentication');
                abort(401, 'Unauthorized access');
            }
            
            Log::info('Dashboard accessed by user: ' . \Illuminate\Support\Facades\Auth::user()->id);
            
            $stats = $this->getStats();
            $chartData = [
                'trend' => $this->getTrendData(),
                'composition' => $this->getCompositionData(),
            ];
            $activity = $this->getRecentActivity();
            $critical = $this->getCriticalItems();
            $greeting = $this->getGreeting();
            $moldModifications = $this->getMoldModifications();
            $assetModels = AssetModel::select('id', 'name')->orderBy('name')->get() ?? collect();

            return view('dashboard', compact('stats', 'chartData', 'activity', 'critical', 'greeting', 'moldModifications', 'assetModels'));
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
                        ['name' => __('dashboard.charts.total_reports'), 'data' => []],
                        ['name' => __('dashboard.charts.resolved_reports'), 'data' => []]
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
            $moldModifications = [];
            $assetModels = collect();

            return view('dashboard', compact('stats', 'chartData', 'activity', 'critical', 'greeting', 'moldModifications', 'assetModels'));
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
            return __('dashboard.greeting_morning');
        } elseif ($hour >= 11 && $hour < 15) {
            return __('dashboard.greeting_afternoon');
        } elseif ($hour >= 15 && $hour < 19) {
            return __('dashboard.greeting_evening');
        } else {
            return __('dashboard.greeting_night');
        }
    }

    /**
     * Get dashboard statistics
     */
    private function getStats(): array
    {
        try {
            // Clear cache to ensure fresh data
            Cache::forget('dashboard.stats');
            
            // Cache statistics for 5 minutes to improve performance
            return Cache::remember('dashboard.stats', now()->timezone('Asia/Jakarta')->addMinutes(5), function () {
                // Calculate total asset value from materials, tools, and models
                $materialValue = AssetMaterial::selectRaw('SUM(unit_price * quantity) as total')->first()->total ?? 0;
                $toolValue = AssetTool::selectRaw('SUM(purchase_price * quantity) as total')->first()->total ?? 0;
                // AssetModels don't have price field, so we'll count them as quantity units
                $modelValue = AssetModel::count() * 1000000; // Estimated value per model
                $totalAssetValue = $materialValue + $toolValue + $modelValue;
                
                $stats = [
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
                
                return $stats;
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
                        'status_display' => __('dashboard.status.critical'),
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
                                'status_display' => __('dashboard.status.low_stock'),
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
                            'name' => __('dashboard.charts.total_reports'),
                            'data' => $totalReports
                        ],
                        [
                            'name' => __('dashboard.charts.resolved_reports'),
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
                    ['name' => __('dashboard.charts.total_reports'), 'data' => []],
                    ['name' => __('dashboard.charts.resolved_reports'), 'data' => []]
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
            return "$user " . __('dashboard.activity_resolved') . " $asset";
        }
        
        return "$user " . __('dashboard.activity_reported') . " $type " . __('dashboard.activity_on') . " $asset";
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

    /**
     * Get mold modifications for dashboard
     */
    private function getMoldModifications(): array
    {
        try {
            return Cache::remember('dashboard.mold_modifications', now()->addMinutes(5), function () {
                return MoldModification::with('assetModel')
                    ->orderBy('production_date', 'asc')
                    ->take(10) // Limit to 10 for safety
                    ->get()
                    ->map(function ($modification) {
                        return [
                            'id' => $modification->id,
                            'asset_model_id' => $modification->asset_model_id,
                            'model_name' => $modification->model_name,
                            'spec_before' => $modification->spec_before,
                            'spec_after' => $modification->spec_after,
                            'production_date' => $modification->production_date->format('Y-m-d'),
                            'production_date_formatted' => $modification->production_date->format('d M Y'),
                            'status' => $modification->status,
                            'status_label' => $modification->status_label,
                            'days_remaining' => $modification->days_remaining,
                            'is_critical' => $modification->is_critical,
                            'row_class' => $modification->row_class,
                            'text_class' => $modification->text_class,
                            'description' => $modification->description,
                            'asset_model' => $modification->assetModel ? [
                                'id' => $modification->assetModel->id,
                                'name' => $modification->assetModel->name,
                                'model_code' => $modification->assetModel->model_code
                            ] : null
                        ];
                    })->toArray();
            });
        } catch (\Exception $e) {
            Log::error('Error getting mold modifications: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Export dashboard data to Excel
     */
    public function exportExcel()
    {
        try {
            $stats = $this->getStats();
            $materials = AssetMaterial::with('gedung')->get();
            $tools = AssetTool::all();
            $models = AssetModel::all();
            $reports = Report::with(['user', 'reportable'])->get();

            $data = [
                'stats' => $stats,
                'materials' => $materials,
                'tools' => $tools,
                'models' => $models,
                'reports' => $reports,
                'export_date' => now()->format('Y-m-d H:i:s')
            ];

            // For now, return a simple CSV export since DashboardExport might not exist
            $filename = 'dashboard-export-' . date('Y-m-d') . '.xlsx';
            
            // Create a simple collection for export
            $exportData = collect([
                ['Dashboard Export - ' . now()->format('Y-m-d H:i:s')],
                [''],
                ['Statistics'],
                ['Total Asset Value', $stats['totalAssetValue'] ?? 0],
                ['Materials Count', $stats['materialsCount'] ?? 0],
                ['Tools Count', $stats['toolsCount'] ?? 0],
                ['Models Count', $stats['modelsCount'] ?? 0],
                ['Reports Count', $stats['reportsCount'] ?? 0],
                [''],
                ['Materials'],
                ['ID', 'Code', 'Name', 'Type', 'Quantity', 'Unit', 'Location'],
            ]);

            // Add materials data
            foreach ($materials as $material) {
                $exportData->push([
                    $material->id,
                    $material->material_code,
                    $material->name,
                    $material->type,
                    $material->quantity,
                    $material->unit,
                    $material->location ?? '-'
                ]);
            }

            return Excel::download(new class($exportData) implements \Maatwebsite\Excel\Concerns\FromCollection {
                protected $data;
                
                public function __construct($data)
                {
                    $this->data = $data;
                }
                
                public function collection()
                {
                    return $this->data;
                }
            }, $filename);

        } catch (\Exception $e) {
            Log::error('Error exporting dashboard: ' . $e->getMessage());
            return back()->with('error', __('dashboard.export_failed'));
        }
    }
}
