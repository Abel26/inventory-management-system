<?php

namespace App\Exports;

use App\Models\WorkLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class WorkLogPdfExport
{
    protected $startDate;
    protected $endDate;
    protected $userId;

    public function __construct($startDate = null, $endDate = null, $userId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->userId = $userId;
    }

    public function generatePdf()
    {
        $query = WorkLog::with(['user', 'location']);

        if ($this->startDate) {
            $query->whereDate('work_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('work_date', '<=', $this->endDate);
        }

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        $workLogs = $query->orderBy('work_date', 'desc')
                              ->orderBy('start_time', 'desc')
                              ->get();

        $totalHours = $workLogs->sum(function ($log) {
            return ($log->total_work_minutes ?? 0) / 60;
        });

        $pdf = Pdf::loadView('exports.work-logs-pdf', compact('workLogs', 'totalHours', 'startDate', 'endDate'))
                    ->setPaper('a4')
                    ->setOption('margin-top', 20)
                    ->setOption('margin-bottom', 20)
                    ->setOption('margin-left', 15)
                    ->setOption('margin-right', 15);

        return $pdf->download('laporan-kerja-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}
