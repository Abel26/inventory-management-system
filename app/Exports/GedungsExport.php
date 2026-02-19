<?php

namespace App\Exports;

use App\Models\Gedung;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GedungsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return collection of gedungs.
     */
    public function collection()
    {
        return Gedung::withCount(['assetModels', 'assetMaterials', 'assetTools'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Return headings for Excel file.
     */
    public function headings(): array
    {
        return [
            'Kode Gedung',
            'Nama Gedung',
            'Total Model',
            'Total Material',
            'Total Tools',
            'Total Aset',
            'Tanggal Dibuat',
        ];
    }

    /**
     * Map data to columns.
     */
    public function map($gedung): array
    {
        return [
            $gedung->gedung_id,
            $gedung->nama,
            $gedung->asset_models_count ?? 0,
            $gedung->asset_materials_count ?? 0,
            $gedung->asset_tools_count ?? 0,
            ($gedung->asset_models_count ?? 0) + ($gedung->asset_materials_count ?? 0) + ($gedung->asset_tools_count ?? 0),
            $gedung->created_at->format('d/m/Y H:i'),
        ];
    }
}