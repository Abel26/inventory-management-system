<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DashboardExport;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the executive dashboard
     */
    public function index(): View
    {
        $data = $this->dashboardService->getDashboardData();

        return view('dashboard', $data);
    }

    /**
     * Get dashboard data (AJAX)
     */
    public function getData(): JsonResponse
    {
        $data = $this->dashboardService->getDashboardData();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Search assets across all models (AJAX)
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query terlalu pendek',
                'data' => [],
            ]);
        }

        $results = $this->dashboardService->searchAssets($query);

        return response()->json($results);
    }

    /**
     * Filter dashboard data by timeframe (AJAX)
     */
    public function filter(Request $request): JsonResponse
    {
        $timeframe = $request->input('timeframe', 'month');

        $chartsData = $this->dashboardService->getChartDataByTimeframe($timeframe);

        return response()->json([
            'success' => true,
            'data' => $chartsData,
        ]);
    }

    /**
     * Export dashboard to PDF
     */
    public function exportPdf()
    {
        $data = $this->dashboardService->getDashboardData();
        $date = now()->locale('id')->isoFormat('D MMMM Y');

        $pdf = PDF::loadView('dashboard.pdf', compact('data', 'date'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download("Executive-Report-{$date}.pdf");
    }

    /**
     * Export dashboard to Excel
     */
    public function exportExcel()
    {
        $data = $this->dashboardService->getDashboardData();

        return Excel::download(new DashboardExport($data), 'Executive-Report-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Clear dashboard cache (for testing/admin)
     */
    public function clearCache(): JsonResponse
    {
        $this->dashboardService->clearCache();

        return response()->json([
            'success' => true,
            'message' => 'Cache berhasil dibersihkan',
        ]);
    }
}
