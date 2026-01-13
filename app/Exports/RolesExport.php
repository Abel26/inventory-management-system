<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Spatie\Permission\Models\Role;

class RolesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Role::with('permissions')->get()->map(function ($role) {
            return [
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'permissions_count' => $role->permissions->count(),
                'created_at' => $role->created_at ? $role->created_at->format('d-m-Y') : '-',
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Role',
            'Guard',
            'Jumlah Permission',
            'Tanggal Dibuat',
        ];
    }

    /**
     * @param mixed $row
     * @param mixed $key
     * @return array
     */
    public function map($row): array
    {
        return [
            $row['name'],
            $row['guard_name'],
            $row['permissions_count'],
            $row['created_at'],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Daftar Role';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Get the highest row index
                $highestRow = $sheet->getHighestRow();
                
                // Apply styles to header row (row 1)
                if ($highestRow) {
                    $headerStyle = $this->getHeaderStyle();
                    
                    foreach (range('A', $highestRow['columnLetter']) as $column) {
                        $sheet->getStyle($column . '1')->applyFromArray($headerStyle);
                    }
                }
            },
        ];
    }

    /**
     * Get header style
     */
    private function getHeaderStyle(): array
    {
        return [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE5E7EB'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFD1D5DB'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
    }
}
