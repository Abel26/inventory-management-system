<?php

namespace App\Exports;

use App\Models\AssetMaterial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetMaterialsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return collection of materials.
     */
    public function collection()
    {
        return AssetMaterial::orderBy('created_at', 'desc')->get();
    }

    /**
     * Return headings for Excel file.
     */
    public function headings(): array
    {
        return [
            'Kode Material',
            'Nama Material',
            'Tipe',
            'Jumlah',
            'Satuan',
            'Min. Stok',
            'Supplier',
            'Tanggal Masuk',
            'Tanggal Kadaluarsa',
            'Lokasi',
            'Deskripsi',
            'Harga Satuan',
            'Status Stok',
            'Tanggal Dibuat',
        ];
    }

    /**
     * Map data to columns.
     */
    public function map($material): array
    {
        return [
            $material->material_code,
            $material->name,
            $material->type,
            $material->quantity,
            $material->unit,
            $material->min_threshold,
            $material->supplier ?? '-',
            $material->entry_date ? $material->entry_date->format('d/m/Y') : '-',
            $material->expiry_date ? $material->expiry_date->format('d/m/Y') : '-',
            $material->location ?? '-',
            $material->description ?? '-',
            $material->unit_price,
            $material->stock_status_label,
            $material->created_at->format('d/m/Y H:i'),
        ];
    }
}
