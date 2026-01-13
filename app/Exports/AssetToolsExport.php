<?php

namespace App\Exports;

use App\Models\AssetTool;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetToolsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return collection of tools.
     */
    public function collection()
    {
        return AssetTool::orderBy('created_at', 'desc')->get();
    }

    /**
     * Return headings for Excel file.
     */
    public function headings(): array
    {
        return [
            'Kode Alat',
            'Nama Alat',
            'Kategori',
            'Merk',
            'Tipe',
            'Tahun Pembelian',
            'Jumlah',
            'Lokasi',
            'Kondisi',
            'Deskripsi',
            'Tanggal Dibuat',
        ];
    }

    /**
     * Map data to columns.
     */
    public function map($tool): array
    {
        return [
            $tool->tool_code,
            $tool->name,
            $tool->category,
            $tool->brand ?? '-',
            $tool->type ?? '-',
            $tool->purchase_year ?? '-',
            $tool->quantity,
            $tool->location ?? '-',
            $tool->condition_label,
            $tool->description ?? '-',
            $tool->created_at->format('d/m/Y H:i'),
        ];
    }
}
