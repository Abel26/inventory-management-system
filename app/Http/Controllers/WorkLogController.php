<?php

namespace App\Http\Controllers;

use App\Services\WorkLogService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkLogRequest;
use App\Http\Requests\UpdateWorkLogRequest;
use App\Exports\WorkLogExport;
use App\Exports\WorkLogPdfExport;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class WorkLogController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected WorkLogService $workLogService
    ) {}

    /**
     * Display a listing of all work logs (admin view).
     */
    public function index(Request $request): View
    {
        $this->authorize('View all work logs');

        $filters = [
            'status' => $request->input('status'),
            'work_type' => $request->input('work_type'),
            'priority' => $request->input('priority'),
            'start_date' => $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null,
            'end_date' => $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null,
            'per_page' => $request->input('per_page', 15),
        ];

        $workLogs = $this->workLogService->getAllWorkLogs($filters);
        
        // Get statistics data
        $stats = $this->workLogService->getStatistics($filters);
        
        // Get users for filter dropdown
        $users = \App\Models\User::active()->orderBy('name')->get();
        
        // Get employee comparison data
        $employees = $this->workLogService->getEmployeeComparison($filters);
        
        // Get monthly summary data
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $userId = $request->input('user_id');
        $monthlySummary = $userId
            ? $this->workLogService->getMonthlySummary($userId, $year, $month)
            : [];

        return view('work-logs.index', compact(
            'workLogs',
            'filters',
            'stats',
            'users',
            'employees',
            'monthlySummary',
            'month',
            'year'
        ));
    }

    /**
     * Display my work logs (employee view).
     */
    public function myWork(Request $request): View
    {
        $this->authorize('View own work logs');

        $userId = Auth::id();
        $filters = [
            'status' => $request->input('status'),
            'work_type' => $request->input('work_type'),
            'priority' => $request->input('priority'),
            'start_date' => $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null,
            'end_date' => $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null,
            'per_page' => $request->input('per_page', 15),
        ];

        $workLogs = $this->workLogService->getWorkLogsByUser($userId, $filters);
        $todayWorkLogs = $this->workLogService->getTodayWorkLogs($userId);
        $activeWorkLogs = $this->workLogService->getActiveWorkLogs($userId);
        
        // Fetch stats for the current user
        $statsFilters = array_merge($filters, ['user_id' => $userId]);
        $stats = $this->workLogService->getStatistics($statsFilters);

        // Fetch monthly summary
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $monthlySummary = $this->workLogService->getMonthlySummary($userId, $year, $month);

        // Fetch employee dashboard data
        $weeklyBreakdown = $this->workLogService->getWeeklyBreakdown($userId);
        $todayStats = $this->workLogService->getTodayStats($userId);

        // Greeting based on time of day
        $hour = now()->timezone('Asia/Jakarta')->format('H');
        if ($hour >= 5 && $hour < 11) {
            $greeting = __('dashboard.greeting_morning');
        } elseif ($hour >= 11 && $hour < 15) {
            $greeting = __('dashboard.greeting_afternoon');
        } elseif ($hour >= 15 && $hour < 19) {
            $greeting = __('dashboard.greeting_evening');
        } else {
            $greeting = __('dashboard.greeting_night');
        }

        return view('work-logs.my-work', compact(
            'workLogs',
            'todayWorkLogs',
            'activeWorkLogs',
            'filters',
            'stats',
            'monthlySummary',
            'month',
            'year',
            'weeklyBreakdown',
            'todayStats',
            'greeting'
        ));
    }

    /**
     * Show form for creating a new work log.
     */
    public function create(): View
    {
        $this->authorize('Create work logs');

        $users = [];
        if (Auth::user()->canViewUserManagement()) {
            $users = User::active()->orderBy('name')->get();
        }

        return view('work-logs.create', compact('users'));
    }

    /**
     * Store a newly created work log in storage.
     */
    public function store(StoreWorkLogRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        // If user is admin and provided a user_id, use it. Otherwise use Auth::id()
        if (Auth::user()->canViewUserManagement() && $request->filled('user_id')) {
            $data['user_id'] = $request->user_id;
        } else {
            $data['user_id'] = Auth::id();
        }

        $data['work_code'] = \App\Models\WorkLog::generateWorkCode();

        try {
            $workLog = $this->workLogService->createWorkLog($data);

            // Handle attachment if present
            if ($request->hasFile('attachment')) {
                $this->workLogService->uploadAttachment($workLog->id, $request->file('attachment'));
            }

            return response()->json([
                'success' => true,
                'message' => __('work_logs.messages.created'),
                'data' => $workLog->fresh(),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('work_logs.messages.validation_failed'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('work_logs.messages.create_failed', ['error' => $e->getMessage()]),
            ], 422);
        }
    }

    /**
     * Display the specified work log.
     */
    public function show(\App\Models\WorkLog $workLog): View
    {
        // Check if user can view this work log
        $user = Auth::user();
        if (Auth::id() !== $workLog->user_id && !($user && $user->hasPermissionTo('View all work logs'))) {
            abort(403, __('work_logs.messages.view_forbidden'));
        }

        return view('work-logs.show', compact('workLog'));
    }

    /**
     * Show form for editing the specified work log.
     */
    public function edit(\App\Models\WorkLog $workLog): View
    {
        // Check if user can edit this work log
        $user = Auth::user();
        if (Auth::id() !== $workLog->user_id && !($user && $user->hasPermissionTo('Update any work logs'))) {
            abort(403, __('work_logs.messages.edit_forbidden'));
        }

        $users = [];
        if ($user->canViewUserManagement()) {
            $users = User::active()->orderBy('name')->get();
        }

        return view('work-logs.edit', compact('workLog', 'users'));
    }

    /**
     * Update the specified work log in storage.
     */
    public function update(UpdateWorkLogRequest $request, \App\Models\WorkLog $workLog): JsonResponse
    {
        // Check if user can edit this work log
        if (!$workLog->canEdit()) {
            return response()->json([
                'success' => false,
                'message' => __('work_logs.messages.edit_forbidden'),
            ], 403);
        }

        $data = $request->validated();

        try {
            $updatedWorkLog = $this->workLogService->updateWorkLog($workLog->id, $data);

            // Handle attachment if present
            if ($request->hasFile('attachment')) {
                $this->workLogService->uploadAttachment($workLog->id, $request->file('attachment'));
            }

            return response()->json([
                'success' => true,
                'message' => __('work_logs.messages.updated'),
                'data' => $updatedWorkLog->fresh(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('work_logs.messages.validation_failed'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('work_logs.messages.update_failed', ['error' => $e->getMessage()]),
            ], 422);
        }
    }

    /**
     * Remove the specified work log from storage.
     */
    public function destroy(\App\Models\WorkLog $workLog): JsonResponse
    {
        // Check if user can delete this work log
        if (!$workLog->canEdit()) {
            return response()->json([
                'success' => false,
                'message' => __('work_logs.messages.delete_forbidden'),
            ], 403);
        }

        try {
            $this->workLogService->deleteWorkLog($workLog->id);

            return response()->json([
                'success' => true,
                'message' => __('work_logs.messages.deleted'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('work_logs.messages.delete_failed', ['error' => $e->getMessage()]),
            ], 422);
        }
    }

    /**
     * Get work logs by date range (AJAX).
     */
    public function getByDateRange(Request $request): JsonResponse
    {
        $this->authorize('View own work logs');

        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfDay();

        $filters = [
            'status' => $request->input('status'),
            'work_type' => $request->input('work_type'),
            'priority' => $request->input('priority'),
            'per_page' => $request->input('per_page', 15),
        ];

        // If user doesn't have permission to view all, force filter to their own ID
        if (!Auth::user()->hasPermissionTo('View all work logs')) {
            $filters['user_id'] = Auth::id();
        } else if ($request->filled('user_id')) {
            $filters['user_id'] = $request->input('user_id');
        }

        $workLogs = $this->workLogService->getWorkLogsByDateRange($startDate, $endDate, $filters);
        
        // Fetch statistics for the same period and filters
        $stats = $this->workLogService->getStatistics(array_merge($filters, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]));

        return response()->json([
            'success' => true,
            'data' => $workLogs->items(),
            'stats' => $stats,
            'meta' => [
                'total' => $workLogs->total(),
                'current_page' => $workLogs->currentPage(),
                'last_page' => $workLogs->lastPage(),
            ]
        ]);
    }

    /**
     * Get work log statistics (AJAX).
     */
    public function getStatistics(Request $request): JsonResponse
    {
        $this->authorize('View own work logs');

        $filters = [
            'status' => $request->input('status'),
            'work_type' => $request->input('work_type'),
            'priority' => $request->input('priority'),
            'start_date' => $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null,
            'end_date' => $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null,
        ];

        // If user doesn't have permission to view all, force filter to their own ID
        if (!Auth::user()->hasPermissionTo('View all work logs')) {
            $filters['user_id'] = Auth::id();
        } else if ($request->filled('user_id')) {
            $filters['user_id'] = $request->input('user_id');
        }

        $statistics = $this->workLogService->getStatistics($filters);

        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }

    /**
     * Get monthly summary (AJAX).
     */
    public function getMonthlySummary(Request $request): JsonResponse
    {
        $this->authorize('View own work logs');

        $userId = $request->input('user_id', Auth::id());
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $summary = $this->workLogService->getMonthlySummary($userId, $year, $month);

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }

    /**
     * Get employee comparison data (AJAX).
     */
    public function getEmployeeComparison(Request $request): JsonResponse
    {
        $this->authorize('View all work logs');

        $filters = [
            'status' => $request->input('status'),
            'work_type' => $request->input('work_type'),
            'priority' => $request->input('priority'),
            'start_date' => $request->input('start_date') ? Carbon::parse($request->input('start_date')) : null,
            'end_date' => $request->input('end_date') ? Carbon::parse($request->input('end_date')) : null,
        ];

        $employees = $this->workLogService->getEmployeeComparison($filters);

        return response()->json([
            'success' => true,
            'data' => $employees,
        ]);
    }

    /**
     * Export work logs to Excel.
     */
    public function export(Request $request)
    {
        $this->authorize('Export work logs');

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $userId = $request->input('user_id');

        $fileName = 'laporan-kerja-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new WorkLogExport($startDate, $endDate, $userId), $fileName);
    }

    /**
     * Export work logs to PDF.
     */
    public function exportPdf(Request $request)
    {
        $this->authorize('Export work logs');

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $userId = $request->input('user_id');

        $pdfExport = new WorkLogPdfExport($startDate, $endDate, $userId);

        return $pdfExport->generatePdf();
    }


    /**
     * Upload attachment to work log.
     */
    public function uploadAttachment(Request $request, \App\Models\WorkLog $workLog): JsonResponse
    {
        $this->authorize('View own work logs');

        $validated = $request->validate([
            'attachment' => 'required|file|max:2048|mimes:jpeg,jpg,png,pdf,doc,docx',
        ]);

        $workLog = $this->workLogService->uploadAttachment(
            $workLog->id,
            $validated['attachment']
        );

        return response()->json([
            'message' => __('work_logs.messages.attachment_uploaded'),
            'work_log' => $workLog,
        ]);
    }

    /**
     * Delete attachment from work log.
     */
    public function deleteAttachment(\App\Models\WorkLog $workLog): JsonResponse
    {
        $this->authorize('View own work logs');

        $workLog = $this->workLogService->deleteAttachment($workLog->id);

        return response()->json([
            'message' => __('work_logs.messages.attachment_deleted'),
            'work_log' => $workLog,
        ]);
    }

    /**
     * Start work log timer (AJAX).
     */
    public function startTimer(Request $request): JsonResponse
    {
        $this->authorize('Create work logs');

        try {
            $workLog = $this->workLogService->startTimer(Auth::id(), $request->all());

            return response()->json([
                'success' => true,
                'message' => __('work_logs.messages.timer_started'),
                'data' => $workLog,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Stop work log timer (AJAX).
     */
    public function stopTimer(\App\Models\WorkLog $workLog): JsonResponse
    {
        // Check if user can edit this work log
        if (Auth::id() !== $workLog->user_id) {
            abort(403, __('work_logs.messages.edit_forbidden'));
        }

        try {
            $workLog = $this->workLogService->stopTimer($workLog->id);

            return response()->json([
                'success' => true,
                'message' => __('work_logs.messages.timer_stopped'),
                'data' => $workLog,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Add admin comment to work log.
     */
    public function addComment(Request $request, \App\Models\WorkLog $workLog): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('View all work logs');

        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        try {
            $this->workLogService->addAdminComment($workLog->id, $request->comment, Auth::id());

            return back()->with('success', __('work_logs.messages.comment_added'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
