<?php

namespace App\Exports;

use App\Models\AssetModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetModelsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Return collection of models.
     */
    public function collection()
    {
        return AssetModel::with('material')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Return headings for Excel file.
     */
    public function headings(): array
    {
        return [
            'Kode Model',
            'Nama Model',
            'Tipe',
            'Material',
            'Tanggal Pembuatan',
            'Kondisi',
            'Lokasi',
            'Deskripsi',
            'Tanggal Dibuat',
        ];
    }

    /**
     * Map data to columns.
     */
    public function map($model): array
    {
        return [
            $model->model_code,
            $model->name,
            $model->type,
            $model->material?->name ?? '-',
            $model->manufactured_date ? $model->manufactured_date->format('d/m/Y') : '-',
            $model->condition_label,
            $model->location ?? '-',
            $model->description ?? '-',
            $model->created_at->format('d/m/Y H:i'),
        ];
    }
}
