<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DashboardExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $rows = [];

        // Statistics
        $rows[] = ['LAPORAN RINGKASAN INVENTARIS EBARA'];
        $rows[] = ['Dibuat:', now()->format('d F Y H:i')];
        $rows[] = [];

        // Stats Section
        $rows[] = ['STATISTIK'];
        $rows[] = ['Metrik', 'Nilai'];
        $rows[] = ['Total Nilai Aset (IDR)', number_format($this->data['stats']['totalAssetValue'], 2, ',', '.')];
        $rows[] = ['Total Masalah Kritis', $this->data['stats']['totalCriticalIssues']];
        $rows[] = ['Peringatan Stok Menipis', $this->data['stats']['lowStockAlerts']];
        $rows[] = ['Pengguna Aktif', $this->data['stats']['activeUsers']];
        $rows[] = ['Total Material', $this->data['stats']['totalMaterials']];
        $rows[] = ['Total Peralatan', $this->data['stats']['totalTools']];
        $rows[] = ['Total Cetakan', $this->data['stats']['totalModels']];
        $rows[] = ['Total Laporan', $this->data['stats']['totalReports']];
        $rows[] = [];

        // Monthly Trend
        $rows[] = ['TREN PELAPORAN (12 Bulan Terakhir)'];
        $rows[] = ['Bulan', 'Jumlah Laporan'];
        foreach ($this->data['charts']['monthlyTrend']['categories'] as $index => $month) {
            $rows[] = [$month, $this->data['charts']['monthlyTrend']['series'][0]['data'][$index] ?? 0];
        }
        $rows[] = [];

        // Asset Composition
        $rows[] = ['KOMPOSISI ASET'];
        $rows[] = ['Tipe', 'Jumlah'];
        foreach ($this->data['charts']['assetComposition']['labels'] as $index => $label) {
            $rows[] = [$label, $this->data['charts']['assetComposition']['series'][$index] ?? 0];
        }
        $rows[] = [];

        // Assets by Location
        $rows[] = ['ASET BERDASARKAN LOKASI'];
        $rows[] = ['Lokasi', 'Jumlah'];
        foreach ($this->data['charts']['assetsByLocation']['categories'] as $index => $location) {
            $rows[] = [$location, $this->data['charts']['assetsByLocation']['series'][0]['data'][$index] ?? 0];
        }
        $rows[] = [];

        // Critical Items
        $rows[] = ['ITEM KRITIS PERLU PERHATIAN'];
        $rows[] = ['Nama', 'Kode', 'Tipe', 'Status', 'Stok', 'Batas'];
        foreach ($this->data['critical'] as $item) {
            // Translate condition to Indonesian
            $conditionTranslation = $item['status'] ?? 'Unknown';
            if ($item['status'] == 'good') {
                $conditionTranslation = 'Baik';
            } elseif ($item['status'] == 'out_of_stock') {
                $conditionTranslation = 'Stok Habis';
            } elseif ($item['status'] == 'low_stock') {
                $conditionTranslation = 'Stok Menipis';
            } elseif ($item['status'] == 'critical') {
                $conditionTranslation = 'Kritis';
            } else {
                $conditionTranslation = $item['status'];
            }

            $rows[] = [
                $item['name'],
                $item['code'],
                $item['type'],
                $conditionTranslation,
                $item['stock'],
                $item['threshold'],
            ];
        }

        return collect($rows);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Dashboard Eksekutif';
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 16]],
            2 => ['font' => ['italic' => true]],
            4 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
            15 => ['font' => ['bold' => true]],
            16 => ['font' => ['bold' => true]],
            21 => ['font' => ['bold' => true]],
            22 => ['font' => ['bold' => true]],
            27 => ['font' => ['bold' => true]],
            28 => ['font' => ['bold' => true]],
        ];
    }
}
