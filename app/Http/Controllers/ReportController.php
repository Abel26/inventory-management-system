<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\AssetLookupService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected AssetLookupService $assetLookupService,
        protected ReportService $reportService
    ) {
        $this->assetLookupService = $assetLookupService;
        $this->reportService = $reportService;
    }

    /**
     * Display reports dashboard.
     */
    public function index(): View
    {
        $reports = $this->reportService->getAll();

        // Get statistics
        $stats = [
            'total' => $reports->count(),
            'pending' => $reports->where('status', 'Pending')->count(),
            'in_progress' => $reports->where('status', 'In Progress')->count(),
            'resolved' => $reports->where('status', 'Resolved')->count(),
            'rejected' => $reports->where('status', 'Rejected')->count(),
        ];

        return view('reports.index', compact('reports', 'stats'));
    }

    /**
     * Scan QR code and redirect to create report.
     */
    public function scan(Request $request)
    {
        $code = $request->input('code');

        Log::info('📷 Scan Initiated', [
            'code' => $code,
            'code_length' => strlen($code ?? ''),
            'all_params' => $request->all(),
        ]);

        // 1. GUARD CLAUSE: If no code is provided, just show scanner page.
        // DO NOT call the service here.
        if (!$code) {
            Log::info('📷 No code provided, showing scanner page');
            return view('reports.scan');
        }

        // Trim whitespace from code
        $code = trim($code);

        Log::info('🔍 Searching for asset', [
            'code' => $code,
            'trimmed_code' => $code,
        ]);

        // 2. SEARCH LOGIC: Only executes if $code exists.
        $assetInfo = $this->assetLookupService->findByCode($code);

        Log::info('📋 Asset lookup result', [
            'code' => $code,
            'found' => $assetInfo['found'] ?? false,
            'type' => $assetInfo['type'] ?? null,
            'asset_id' => $assetInfo['asset']->id ?? null,
        ]);

        // 3. RESULT HANDLING
        if (!$assetInfo || !$assetInfo['found']) {
            // If searching failed, return to scanner with error message
            Log::warning('⚠️ Asset not found', [
                'code' => $code,
            ]);

            return redirect()->route('reports.scan')
                ->with('error', 'Aset tidak ditemukan. Pastikan kode benar.');
        }

        // 4. SUCCESS: Redirect to create form
        Log::info('✅ Asset found, redirecting to create form', [
            'code' => $code,
            'asset_id' => $assetInfo['asset']->id,
            'type' => $assetInfo['type'],
        ]);

        return redirect()->route('reports.create', ['code' => $code]);
    }

    /**
     * Display create report form.
     */
    public function create(Request $request): View
    {
        $code = $request->query('code');
        $assetInfo = null;

        Log::info('📝 Create report form accessed', [
            'code' => $code,
        ]);

        if ($code) {
            $result = $this->assetLookupService->findByCode($code);
            if ($result['found']) {
                $assetInfo = [
                    'id' => $result['asset']->id,
                    'name' => $result['display_name'],
                    'code' => $result['code'],
                    'type' => ucfirst($result['type']),
                    'condition' => $result['condition'],
                    'location' => $result['location'],
                    'model_class' => $result['model'],
                ];

                Log::info('✅ Asset info loaded for create form', [
                    'asset_id' => $assetInfo['id'],
                    'code' => $assetInfo['code'],
                ]);
            } else {
                Log::warning('⚠️ Asset not found for create form', [
                    'code' => $code,
                ]);
            }
        }

        return view('reports.create', compact('assetInfo'));
    }

    /**
     * Store new report.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reportable_id' => 'required|integer',
            'reportable_type' => 'required|string|in:App\Models\AssetMaterial,App\Models\AssetTool,App\Models\AssetModel',
            'issue_type' => 'required|string|in:Damage,Maintenance,Lost,Stock Discrepancy',
            'priority' => 'required|string|in:Low,Medium,High,Critical',
            'description' => 'required|string|max:1000',
            'photo' => 'nullable|image|max:5120',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        Log::info('💾 Creating new report', [
            'user_id' => $data['user_id'],
            'reportable_id' => $data['reportable_id'],
            'reportable_type' => $data['reportable_type'],
            'issue_type' => $data['issue_type'],
            'priority' => $data['priority'],
        ]);

        $this->reportService->create($data);

        Log::info('✅ Report created successfully');

        return redirect()
            ->route('reports.index')
            ->with('success', 'Laporan berhasil dibuat');
    }

    /**
     * Show report details.
     */
    public function show(int $id): View
    {
        $report = $this->reportService->find($id);

        if (!$report) {
            Log::warning('⚠️ Report not found', ['id' => $id]);
            abort(404, 'Laporan tidak ditemukan');
        }

        Log::info('📄 Viewing report details', ['id' => $id]);

        return view('reports.show', compact('report'));
    }

    /**
     * Update report status.
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,In Progress,Resolved,Rejected',
        ]);

        Log::info('🔄 Updating report status', [
            'id' => $id,
            'old_status' => $this->reportService->find($id)?->status,
            'new_status' => $request->input('status'),
            'updated_by' => auth()->id(),
        ]);

        $this->reportService->updateStatus(
            $id,
            $request->input('status'),
            auth()->id()
        );

        Log::info('✅ Report status updated successfully', [
            'id' => $id,
            'new_status' => $request->input('status'),
        ]);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Status laporan berhasil diperbarui');
    }

    /**
     * Get reports by status (AJAX).
     */
    public function getByStatus(string $status)
    {
        Log::info('📊 Fetching reports by status', ['status' => $status]);

        $reports = $this->reportService->getByStatus($status);

        Log::info('✅ Reports fetched successfully', [
            'status' => $status,
            'count' => $reports->count(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $reports,
        ]);
    }
}
