<?php

namespace App\Services;

use App\Enums\WorkPriority;
use App\Enums\WorkStatus;
use App\Enums\WorkType;
use App\Models\WorkLog;
use App\Repositories\Contracts\WorkLogRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class WorkLogService
{
    public function __construct(
        protected WorkLogRepositoryInterface $workLogRepository
    ) {}

    /**
     * Get all work logs with pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllWorkLogs(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->workLogRepository->getAll(array_merge($filters, ['per_page' => $perPage]));
    }

    /**
     * Get a single work log by ID.
     *
     * @param int $id
     * @return WorkLog|null
     */
    public function getWorkLogById(int $id): ?WorkLog
    {
        return $this->workLogRepository->findById($id);
    }

    /**
     * Create a new work log.
     *
     * @param array $data
     * @return WorkLog
     * @throws ValidationException
     */
    public function createWorkLog(array $data): WorkLog
    {
        $this->validateWorkLog($data);

        // Calculate total work minutes if not provided
        if (!isset($data['total_work_minutes']) && isset($data['start_time']) && isset($data['end_time'])) {
            $data['total_work_minutes'] = $this->calculateTotalWorkMinutes($data);
        }

        // Generate work code if not provided
        if (!isset($data['work_code'])) {
            $data['work_code'] = $this->generateWorkCode();
        }

        return $this->workLogRepository->create($data);
    }

    /**
     * Update a work log.
     *
     * @param int $id
     * @param array $data
     * @return WorkLog|null
     * @throws ValidationException
     */
    public function updateWorkLog(int $id, array $data): ?WorkLog
    {
        $workLog = $this->workLogRepository->findById($id);

        if (!$workLog) {
            return null;
        }

        // Check if work log is locked
        $this->checkWorkLogLock($id);

        $this->validateWorkLog($data, $id);

        // Recalculate total work minutes if time fields changed
        if (isset($data['start_time']) || isset($data['end_time']) || isset($data['break_duration'])) {
            $data['total_work_minutes'] = $this->calculateTotalWorkMinutes(array_merge($workLog->toArray(), $data));
        }

        return $this->workLogRepository->update($id, $data);
    }

    /**
     * Delete a work log.
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteWorkLog(int $id): bool
    {
        // Check if work log is locked
        $this->checkWorkLogLock($id);

        return $this->workLogRepository->delete($id);
    }

    /**
     * Get work logs by user.
     *
     * @param int $userId
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getWorkLogsByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->workLogRepository->getByUser($userId, $filters);
    }

    /**
     * Get work logs by date range.
     *
     * @param Carbon $start
     * @param Carbon $end
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getWorkLogsByDateRange(Carbon $start, Carbon $end, array $filters = []): LengthAwarePaginator
    {
        return $this->workLogRepository->getByDateRange($start, $end, $filters);
    }

    /**
     * Get work log statistics.
     *
     * @param array $filters
     * @return array
     */
    public function getStatistics(array $filters = []): array
    {
        return $this->workLogRepository->getStatistics($filters);
    }

    /**
     * Get monthly summary for a user.
     *
     * @param int $userId
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getMonthlySummary(int $userId, int $year, int $month): array
    {
        return $this->workLogRepository->getMonthlySummary($userId, $year, $month);
    }

    /**
     * Get today's work logs for a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getTodayWorkLogs(int $userId): Collection
    {
        return $this->workLogRepository->getTodayWorkLogs($userId);
    }

    /**
     * Get active work logs for a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getActiveWorkLogs(int $userId): Collection
    {
        return $this->workLogRepository->getActiveWorkLogs($userId);
    }

    /**
     * Get weekly breakdown for a user (current week).
     *
     * @param int $userId
     * @return array
     */
    public function getWeeklyBreakdown(int $userId): array
    {
        return $this->workLogRepository->getWeeklyBreakdown($userId);
    }

    /**
     * Get today's statistics for a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getTodayStats(int $userId): array
    {
        return $this->workLogRepository->getTodayStats($userId);
    }

    /**
     * Search work logs.
     *
     * @param string $keyword
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function searchWorkLogs(string $keyword, array $filters = []): LengthAwarePaginator
    {
        return $this->workLogRepository->search($keyword, $filters);
    }

    /**
     * Get employee comparison data.
     *
     * @param array $filters
     * @return array
     */
    public function getEmployeeComparison(array $filters = []): array
    {
        return $this->workLogRepository->getEmployeeComparison($filters);
    }

    /**
     * Generate a unique work code.
     *
     * @return string
     */
    public function generateWorkCode(): string
    {
        $date = now()->format('Ymd');
        $prefix = "WP-{$date}";

        $lastWorkLog = WorkLog::withTrashed()
            ->where('work_code', 'like', "{$prefix}%")
            ->orderBy('work_code', 'desc')
            ->first();

        if ($lastWorkLog instanceof WorkLog) {
            $lastNumber = (int) substr($lastWorkLog->work_code, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$newNumber}";
    }

    /**
     * Calculate total work minutes.
     *
     * @param array $data
     * @return int|null
     */
    public function calculateTotalWorkMinutes(array $data): ?int
    {
        if (!isset($data['start_time']) || !isset($data['end_time'])) {
            return null;
        }

        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_date'] ?? $data['end_time']); // Fallback to end_time if end_date not present
        $totalMinutes = $endTime->diffInMinutes($startTime, true); // Use absolute difference
        $breakDuration = $data['break_duration'] ?? 0;

        return max(0, $totalMinutes - $breakDuration);
    }

    /**
     * Validate work log data.
     *
     * @param array $data
     * @param int|null $id
     * @return void
     * @throws ValidationException
     */
    public function validateWorkLog(array $data, ?int $id = null): void
    {
        $rules = [
            'work_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'description' => 'required|string|max:1000',
            'status' => 'nullable|in:' . implode(',', WorkStatus::values()),
            'priority' => 'nullable|in:' . implode(',', WorkPriority::values()),
            'work_type' => 'nullable|in:' . implode(',', WorkType::values()),
            'completion_percentage' => 'nullable|integer|min:0|max:100',
            'break_duration' => 'nullable|integer|min:0|max:480',
            'notes' => 'nullable|string|max:500',
            'location_id' => 'nullable|exists:gedungs,id',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Check if work log is locked.
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function checkWorkLogLock(int $id): void
    {
        $workLog = $this->workLogRepository->findById($id);

        if (!$workLog) {
            throw new \Exception('Work log not found.');
        }

        if ($workLog->isLocked()) {
            $user = Auth::user();
            if (!$user || !$user->canViewUserManagement()) {
                throw new \Exception('Work log is locked and cannot be edited.');
            }
        }
    }

    /**
     * Add admin comment to work log.
     *
     * @param int $id
     * @param string $comment
     * @param int $adminId
     * @return WorkLog
     * @throws \Exception
     */
    public function addAdminComment(int $id, string $comment, int $adminId): WorkLog
    {
        $workLog = $this->workLogRepository->findById($id);

        if (!$workLog) {
            throw new \Exception('Work log not found.');
        }

        $workLog->addAdminComment($comment, $adminId);

        return $workLog;
    }

    /**
     * Upload attachment to work log.
     *
     * @param int $id
     * @param \Illuminate\Http\UploadedFile $file
     * @return WorkLog
     * @throws \Exception
     */
    public function uploadAttachment(int $id, $file): WorkLog
    {
        $workLog = $this->workLogRepository->findById($id);

        if (!$workLog) {
            throw new \Exception('Work log not found.');
        }

        // Validate file
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $maxSize = 2 * 1024 * 1024; // 2MB in bytes

        if ($file->getSize() > $maxSize) {
            throw new \Exception('File size exceeds maximum of 2MB.');
        }

        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \Exception('Invalid file type. Allowed types: JPG, PNG, PDF, DOC, DOCX.');
        }

        // Generate unique filename
        $filename = 'work-log-' . $workLog->work_code . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = 'work-logs/' . $filename;

        // Store file on public disk so it's accessible via web
        $file->storeAs('work-logs', $filename, 'public');

        // Update work log
        $workLog->attachment_path = $path;
        $workLog->attachment_name = $file->getClientOriginalName();
        $workLog->attachment_size = $file->getSize();
        $workLog->attachment_mime_type = $file->getMimeType();
        $workLog->save();

        return $workLog;
    }

    /**
     * Delete attachment from work log.
     *
     * @param int $id
     * @return WorkLog
     * @throws \Exception
     */
    public function deleteAttachment(int $id): WorkLog
    {
        $workLog = $this->workLogRepository->findById($id);

        if (!$workLog) {
            throw new \Exception('Work log not found.');
        }

        $workLog->deleteAttachment();

        return $workLog;
    }

    /**
     * Start a new work log timer.
     *
     * @param int $userId
     * @param array $data
     * @return WorkLog
     */
    public function startTimer(int $userId, array $data): WorkLog
    {
        // Check if there's already an active timer
        $activeTimer = WorkLog::where('user_id', $userId)
            ->where('status', WorkStatus::IN_PROGRESS->value)
            ->first();

        if ($activeTimer) {
            throw new \Exception(__('work_logs.messages.timer_already_running'));
        }

        $data['user_id'] = $userId;
        $data['work_code'] = WorkLog::generateWorkCode();
        $data['work_date'] = now()->toDateString();
        $data['start_time'] = now()->format('H:i:s');
        $data['status'] = WorkStatus::IN_PROGRESS->value;
        $data['priority'] = $data['priority'] ?? WorkPriority::MEDIUM->value;
        $data['work_type'] = $data['work_type'] ?? WorkType::PRODUCTION->value;

        return $this->workLogRepository->create($data);
    }

    /**
     * Stop a running work log timer.
     *
     * @param int $id
     * @return WorkLog
     */
    public function stopTimer(int $id): WorkLog
    {
        $workLog = $this->workLogRepository->findById($id);

        if (!$workLog) {
            throw new \Exception('Work log not found.');
        }

        if ($workLog->status !== WorkStatus::IN_PROGRESS) {
            throw new \Exception(__('work_logs.messages.timer_not_running'));
        }

        $workLog->end_time = now()->format('H:i:s');
        $workLog->status = WorkStatus::COMPLETED;
        $workLog->total_work_minutes = $workLog->calculateTotalWorkMinutes();
        $workLog->save();

        return $workLog;
    }
}
