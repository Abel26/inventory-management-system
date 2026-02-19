<?php

namespace App\Repositories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Contracts\ReportRepositoryInterface;

class ReportRepository implements ReportRepositoryInterface
{
    public function allWithRelations(): Collection
    {
        return Report::with(['user', 'reportable', 'resolver'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function create(array $data): Report
    {
        return Report::create($data);
    }
    
    public function update(int $id, array $data): ?Report
    {
        $report = $this->find($id);
        if (!$report) {
            return null;
        }
        $report->update($data);
        return $report;
    }
    
    public function find(int $id): ?Report
    {
        return Report::with(['user', 'reportable', 'resolver'])->find($id);
    }
    
    public function getByStatus(string $status): Collection
    {
        return Report::with(['user', 'reportable', 'resolver'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function getByUser(int $userId): Collection
    {
        return Report::with(['user', 'reportable', 'resolver'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function delete(int $id): bool
    {
        $report = $this->find($id);
        if (!$report) {
            return false;
        }
        return $report->delete();
    }
}
