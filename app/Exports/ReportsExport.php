<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $status;
    protected $startDate;
    protected $endDate;

    public function __construct($status = null, $startDate = null, $endDate = null)
    {
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Return collection of reports.
     */
    public function collection()
    {
        $query = Report::with(['user', 'reportable'])->orderBy('created_at', 'desc');

        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
        }

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->get();
    }

    /**
     * Return headings for Excel file.
     */
    public function headings(): array
    {
        return [
            'Kode Laporan',
            'Aset',
            'Pelapor',
            'Jenis Masalah',
            'Prioritas',
            'Status',
            'Deskripsi',
            'Tanggal Dibuat',
            'Catatan Admin',
        ];
    }

    /**
     * Map data to columns.
     */
    public function map($report): array
    {
        $assetName = $report->reportable ? ($report->reportable->name ?? $report->reportable->model_name ?? 'Unknown Asset') : '-';

        return [
            $report->report_code,
            $assetName,
            $report->user->name ?? '-',
            $report->issue_type->value,
            $report->priority->value,
            $report->status->value,
            $report->description,
            $report->created_at->format('d/m/Y H:i'),
            $report->admin_note ?? '-',
        ];
    }
}
