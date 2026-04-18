<?php

namespace App\Repositories\Contracts;

use App\Models\WorkLog;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface WorkLogRepositoryInterface
{
    /**
     * Get all work logs with optional filters.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator;

    /**
     * Find a work log by ID.
     *
     * @param int $id
     * @return WorkLog|null
     */
    public function findById(int $id): ?WorkLog;

    /**
     * Find a work log by code.
     *
     * @param string $code
     * @return WorkLog|null
     */
    public function findByCode(string $code): ?WorkLog;

    /**
     * Create a new work log.
     *
     * @param array $data
     * @return WorkLog
     */
    public function create(array $data): WorkLog;

    /**
     * Update a work log.
     *
     * @param int $id
     * @param array $data
     * @return WorkLog|null
     */
    public function update(int $id, array $data): ?WorkLog;

    /**
     * Delete a work log (soft delete).
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get work logs by user.
     *
     * @param int $userId
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getByUser(int $userId, array $filters = []): LengthAwarePaginator;

    /**
     * Get work logs by date range.
     *
     * @param Carbon $start
     * @param Carbon $end
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getByDateRange(Carbon $start, Carbon $end, array $filters = []): LengthAwarePaginator;

    /**
     * Get work log statistics.
     *
     * @param array $filters
     * @return array
     */
    public function getStatistics(array $filters = []): array;

    /**
     * Get monthly summary for a user.
     *
     * @param int $userId
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getMonthlySummary(int $userId, int $year, int $month): array;

    /**
     * Get today's work logs for a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getTodayWorkLogs(int $userId): Collection;

    /**
     * Get active work logs for a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getActiveWorkLogs(int $userId): Collection;

    /**
     * Search work logs.
     *
     * @param string $keyword
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function search(string $keyword, array $filters = []): LengthAwarePaginator;

    /**
     * Get weekly breakdown for a user (current week).
     *
     * @param int $userId
     * @return array
     */
    public function getWeeklyBreakdown(int $userId): array;

    /**
     * Get today's statistics for a specific user.
     *
     * @param int $userId
     * @return array
     */
    public function getTodayStats(int $userId): array;

    /**
     * Get employee comparison data.
     *
     * @param array $filters
     * @return array
     */
    public function getEmployeeComparison(array $filters = []): array;
}
