<?php

namespace App\Exports;

use App\Models\WorkLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class WorkLogExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, WithTitle, WithEvents
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

    public function title(): string
    {
        return 'Laporan Pekerjaan - ' . Carbon::now()->format('d-m-Y');
    }

    public function collection()
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

        return $query->orderBy('work_date', 'desc')
                     ->orderBy('start_time', 'desc')
                     ->get();
    }

    public function headings(): array
    {
        return [
            'Kode Pekerjaan',
            'Pegawai',
            'Tanggal',
            'Jam Mulai',
            'Jam Selesai',
            'Durasi Istirahat',
            'Total Jam Kerja',
            'Deskripsi',
            'Tipe',
            'Status',
            'Catatan',
        ];
    }

    public function map($workLog): array
    {
        $duration = $workLog->total_work_minutes ?? 0;
        $hours = floor($duration / 60);
        $minutes = $duration % 60;

        return [
            $workLog->work_code,
            $workLog->user->name ?? '-',
            $workLog->work_date->format('d M Y'),
            $workLog->start_time->format('H:i'),
            $workLog->end_time ? $workLog->end_time->format('H:i') : '-',
            $workLog->break_duration . ' menit',
            $hours . 'j ' . $minutes . 'm',
            $workLog->description,
            $workLog->work_type?->getLabel() ?? '-',
            $workLog->status->getLabel(),
            $workLog->notes ?? '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '0',
            'B' => '0',
            'C' => '0',
            'D' => '0',
            'E' => '0',
            'F' => '0',
            'G' => '0',
            'H' => '0',
            'I' => '0',
            'J' => '0',
            'K' => '0',
            'L' => '0',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('A1:L' . $highestRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle('A1:L1')->getFont()->setBold(true);
                $sheet->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF009B77');
                $sheet->getStyle('A1:L1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
            },
        ];
    }
}
