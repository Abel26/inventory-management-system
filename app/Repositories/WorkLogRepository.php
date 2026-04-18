<?php

namespace App\Repositories;

use App\Models\WorkLog;
use App\Repositories\Contracts\WorkLogRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class WorkLogRepository implements WorkLogRepositoryInterface
{
    /**
     * Get all work logs with optional filters.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = $this->applyFilters(WorkLog::query(), $filters);

        return $query->with(['user', 'location'])
            ->orderBy('work_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Find a work log by ID.
     *
     * @param int $id
     * @return WorkLog|null
     */
    public function findById(int $id): ?WorkLog
    {
        return WorkLog::with(['user', 'location'])->find($id);
    }

    /**
     * Find a work log by code.
     *
     * @param string $code
     * @return WorkLog|null
     */
    public function findByCode(string $code): ?WorkLog
    {
        return WorkLog::with(['user', 'location'])->where('work_code', $code)->first();
    }

    /**
     * Create a new work log.
     *
     * @param array $data
     * @return WorkLog
     */
    public function create(array $data): WorkLog
    {
        return WorkLog::create($data);
    }

    /**
     * Update a work log.
     *
     * @param int $id
     * @param array $data
     * @return WorkLog|null
     */
    public function update(int $id, array $data): ?WorkLog
    {
        $workLog = $this->findById($id);

        if ($workLog) {
            $workLog->update($data);
            return $workLog->fresh();
        }

        return null;
    }

    /**
     * Delete a work log (soft delete).
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $workLog = $this->findById($id);

        if ($workLog) {
            return $workLog->delete();
        }

        return false;
    }

    /**
     * Get work logs by user.
     *
     * @param int $userId
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = WorkLog::byUser($userId);
        $query = $this->applyFilters($query, $filters);

        return $query->with(['user', 'location'])
            ->orderBy('work_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get work logs by date range.
     *
     * @param Carbon $start
     * @param Carbon $end
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getByDateRange(Carbon $start, Carbon $end, array $filters = []): LengthAwarePaginator
    {
        $query = WorkLog::byDateRange($start, $end);
        $query = $this->applyFilters($query, $filters);

        return $query->with(['user', 'location'])
            ->orderBy('work_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get work log statistics.
     *
     * @param array $filters
     * @return array
     */
    public function getStatistics(array $filters = []): array
    {
        $query = $this->applyFilters(WorkLog::query(), $filters);
        
        $totalLogs = $query->count();
        $totalHours = $query->sum('total_work_minutes') / 60;
        
        // Calculate average hours per day if date range is provided
        $averageHoursPerDay = 0;
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $days = $filters['start_date']->diffInDays($filters['end_date']) + 1;
            $averageHoursPerDay = $days > 0 ? $totalHours / $days : 0;
        }

        return [
            'total_logs' => $totalLogs,
            'total_hours' => round($totalHours, 2),
            'average_hours_per_day' => round($averageHoursPerDay, 2),
            'completed' => (clone $query)->where('status', \App\Enums\WorkStatus::COMPLETED->value)->count(),
            'in_progress' => (clone $query)->where('status', \App\Enums\WorkStatus::IN_PROGRESS->value)->count(),
            'pending' => (clone $query)->where('status', \App\Enums\WorkStatus::PENDING->value)->count(),
            'overdue' => 0, // Placeholder for now
            'on_hold' => (clone $query)->where('status', \App\Enums\WorkStatus::ON_HOLD->value)->count(),
            'cancelled' => (clone $query)->where('status', \App\Enums\WorkStatus::CANCELLED->value)->count(),
        ];
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
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $workLogs = WorkLog::byUser($userId)
            ->byDateRange($startDate, $endDate)
            ->get();

        $totalHours = $workLogs->sum('total_work_minutes') / 60;
        $completedCount = $workLogs->where('status', \App\Enums\WorkStatus::COMPLETED)->count();
        $totalCount = $workLogs->count();

        // Calculate daily breakdown
        $dailyBreakdown = [];
        $ineffectiveDays = 0;
        
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            if ($date->gt(now())) break;
            
            $dayLogs = $workLogs->filter(fn($log) => $log->work_date->isSameDay($date));
            $dayHours = $dayLogs->sum('total_work_minutes') / 60;
            
            if ($dayHours === 0 && !$date->isWeekend()) {
                $ineffectiveDays++;
            }
            
            $dailyBreakdown[] = [
                'date' => $date->format('d M Y'),
                'total_hours' => $dayHours,
                'completed_count' => $dayLogs->where('status', \App\Enums\WorkStatus::COMPLETED)->count(),
                'in_progress_count' => $dayLogs->where('status', \App\Enums\WorkStatus::IN_PROGRESS)->count(),
                'pending_count' => $dayLogs->where('status', \App\Enums\WorkStatus::PENDING)->count(),
            ];
        }

        return [
            'year' => (int)$year,
            'month' => (int)$month,
            'month_name' => $startDate->translatedFormat('F'),
            'total_work_logs' => $totalCount,
            'total_hours' => round($totalHours, 2),
            'completed_work_logs' => $completedCount,
            'completion_rate' => $totalCount > 0 ? round(($completedCount / $totalCount) * 100, 2) : 0,
            'average_hours_per_day' => $totalCount > 0 ? round($totalHours / $startDate->daysInMonth, 2) : 0,
            'daily_breakdown' => array_reverse($dailyBreakdown),
            'ineffective_days' => $ineffectiveDays,
        ];
    }

    /**
     * Get today's work logs for a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getTodayWorkLogs(int $userId): Collection
    {
        return WorkLog::byUser($userId)
            ->today()
            ->with(['user', 'location'])
            ->orderBy('start_time', 'desc')
            ->get();
    }

    /**
     * Get active work logs for a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getActiveWorkLogs(int $userId): Collection
    {
        return WorkLog::byUser($userId)
            ->active()
            ->with(['user', 'location'])
            ->orderBy('start_time', 'desc')
            ->get();
    }

    /**
     * Get weekly breakdown for a user (current week).
     *
     * @param int $userId
     * @return array
     */
    public function getWeeklyBreakdown(int $userId): array
    {
        $startOfWeek = Carbon::now()->timezone('Asia/Jakarta')->startOfWeek();
        $endOfWeek = Carbon::now()->timezone('Asia/Jakarta')->endOfWeek();

        $workLogs = WorkLog::byUser($userId)
            ->byDateRange($startOfWeek, $endOfWeek)
            ->get();

        $labels = [];
        $dates = [];
        $hours = [];
        $completed = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

            $dayLogs = $workLogs->filter(fn($log) => $log->work_date->isSameDay($date));

            $labels[] = $dayNames[$i];
            $dates[] = $date->format('Y-m-d');
            $hours[] = round($dayLogs->sum('total_work_minutes') / 60, 2);
            $completed[] = $dayLogs->where('status', \App\Enums\WorkStatus::COMPLETED)->count();
        }

        return [
            'labels' => $labels,
            'dates' => $dates,
            'hours' => $hours,
            'completed' => $completed,
            'total_hours' => round($workLogs->sum('total_work_minutes') / 60, 2),
            'total_logs' => $workLogs->count(),
            'week_start' => $startOfWeek->format('d M Y'),
            'week_end' => $endOfWeek->format('d M Y'),
        ];
    }

    /**
     * Get today's statistics for a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getTodayStats(int $userId): array
    {
        $todayLogs = WorkLog::byUser($userId)
            ->today()
            ->get();

        return [
            'today_total' => $todayLogs->count(),
            'today_hours' => round($todayLogs->sum('total_work_minutes') / 60, 2),
            'today_in_progress' => $todayLogs->where('status', \App\Enums\WorkStatus::IN_PROGRESS)->count(),
            'today_completed' => $todayLogs->where('status', \App\Enums\WorkStatus::COMPLETED)->count(),
        ];
    }

    /**
     * Search work logs.
     *
     * @param string $keyword
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function search(string $keyword, array $filters = []): LengthAwarePaginator
    {
        $query = WorkLog::query();
        $query = $this->applyFilters($query, $filters);

        $query->where(function ($q) use ($keyword) {
            $q->where('description', 'like', "%{$keyword}%")
                ->orWhere('work_code', 'like', "%{$keyword}%")
                ->orWhere('notes', 'like', "%{$keyword}%");
        });

        return $query->with(['user', 'location'])
            ->orderBy('work_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get employee comparison data.
     *
     * @param array $filters
     * @return array
     */
    public function getEmployeeComparison(array $filters = []): array
    {
        $query = WorkLog::query();
        $query = $this->applyFilters($query, $filters);

        $employees = $query->selectRaw('
                user_id,
                COUNT(*) as total_logs,
                SUM(total_work_minutes) / 60 as total_hours,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed_count
            ', [\App\Enums\WorkStatus::COMPLETED->value])
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total_hours')
            ->get();

        return $employees->map(function ($employee) {
            $completionRate = $employee->total_logs > 0
                ? round(($employee->completed_count / $employee->total_logs) * 100, 2)
                : 0;

            return [
                'user_id' => $employee->user_id,
                'user_name' => $employee->user?->full_name ?? 'Unknown',
                'total_logs' => $employee->total_logs,
                'total_hours' => round($employee->total_hours, 2),
                'completed_count' => $employee->completed_count,
                'completion_rate' => $completionRate,
            ];
        })->toArray();
    }

    /**
     * Apply filters to query.
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    private function applyFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['user_id'])) {
            $query->byUser($filters['user_id']);
        }

        if (isset($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        if (isset($filters['work_type'])) {
            $query->byWorkType($filters['work_type']);
        }

        if (isset($filters['priority'])) {
            $query->byPriority($filters['priority']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->byDateRange($filters['start_date'], $filters['end_date']);
        }

        return $query;
    }
}
