<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Dashboard Eksekutif - {{ $date }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            color: #374151;
            line-height: 1.5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #009B77;
        }
        .header h1 {
            font-size: 24px;
            color: #111827;
            margin-bottom: 8px;
        }
        .header p {
            color: #6B7280;
            font-size: 14px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #009B77;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #E5E7EB;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #F9FAFB;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #009B77;
        }
        .stat-label {
            font-size: 11px;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-top: 4px;
        }
        .stat-sub {
            font-size: 10px;
            color: #6B7280;
            margin-top: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background: #F3F4F6;
        }
        table th {
            padding: 10px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 11px;
            text-transform: uppercase;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #E5E7EB;
        }
        table tbody tr:last-child td {
            border-bottom: none;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 500;
        }
        .badge-danger {
            background: #FEE2E2;
            color: #DC2626;
        }
        .badge-warning {
            background: #FEF3C7;
            color: #D97706;
        }
        .badge-success {
            background: #D1FAE5;
            color: #059669;
        }
        .activity-item {
            display: flex;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .activity-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            flex-shrink: 0;
        }
        .activity-icon.bg-red {
            background: #FEE2E2;
            color: #DC2626;
        }
        .activity-icon.bg-yellow {
            background: #FEF3C7;
            color: #D97706;
        }
        .activity-icon.bg-blue {
            background: #DBEAFE;
            color: #2563EB;
        }
        .activity-icon.bg-gray {
            background: #F3F4F6;
            color: #6B7280;
        }
        .activity-content {
            flex: 1;
        }
        .activity-message {
            font-size: 12px;
            color: #111827;
        }
        .activity-time {
            font-size: 10px;
            color: #6B7280;
            margin-top: 2px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            color: #6B7280;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Laporan Dashboard Eksekutif</h1>
            <p>Dibuat pada {{ $date }}</p>
        </div>

        <!-- Statistics Section -->
        <div class="section">
            <h2 class="section-title">Metrik Utama</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Nilai Aset</div>
                    <div class="stat-value">Rp {{ number_format($data['stats']['totalAssetValue'], 0, ',', '.') }}</div>
                    <div class="stat-sub">Semua aset digabungkan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Masalah Kritis</div>
                    <div class="stat-value">{{ $data['stats']['totalCriticalIssues'] }}</div>
                    <div class="stat-sub">Memerlukan perhatian segera</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Peringatan Stok Menipis</div>
                    <div class="stat-value">{{ $data['stats']['lowStockAlerts'] }}</div>
                    <div class="stat-sub">Item di bawah ambang batas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Pengguna Aktif</div>
                    <div class="stat-value">{{ $data['stats']['activeUsers'] }}</div>
                    <div class="stat-sub">30 hari terakhir</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Material</div>
                    <div class="stat-value">{{ $data['stats']['totalMaterials'] }}</div>
                    <div class="stat-sub">Inventaris material</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Peralatan</div>
                    <div class="stat-value">{{ $data['stats']['totalTools'] }}</div>
                    <div class="stat-sub">Inventaris peralatan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Cetakan</div>
                    <div class="stat-value">{{ $data['stats']['totalModels'] }}</div>
                    <div class="stat-sub">Inventaris cetakan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Total Laporan</div>
                    <div class="stat-value">{{ $data['stats']['totalReports'] }}</div>
                    <div class="stat-sub">Semua laporan</div>
                </div>
            </div>
        </div>

        <!-- Monthly Trend Section -->
        <div class="section">
            <h2 class="section-title">Tren Laporan Bulanan (12 Bulan Terakhir)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Jumlah Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['charts']['monthlyTrend']['categories'] as $index => $month)
                    <tr>
                        <td>{{ $month }}</td>
                        <td>{{ $data['charts']['monthlyTrend']['series'][0]['data'][$index] ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Asset Composition Section -->
        <div class="section">
            <h2 class="section-title">Komposisi Aset</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tipe Aset</th>
                        <th>Jumlah</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['charts']['assetComposition']['labels'] as $index => $label)
                    @php
                        $total = array_sum($data['charts']['assetComposition']['series']);
                        $count = $data['charts']['assetComposition']['series'][$index] ?? 0;
                        $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td>{{ $label }}</td>
                        <td>{{ $count }}</td>
                        <td>{{ $percentage }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Assets by Location Section -->
        <div class="section">
            <h2 class="section-title">Aset Berdasarkan Lokasi</h2>
            <table>
                <thead>
                    <tr>
                        <th>Lokasi</th>
                        <th>Jumlah Aset</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['charts']['assetsByLocation']['categories'] as $index => $location)
                    <tr>
                        <td>{{ $location }}</td>
                        <td>{{ $data['charts']['assetsByLocation']['series'][0]['data'][$index] ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Critical Items Section -->
        <div class="section">
            <h2 class="section-title">Item Kritis yang Memerlukan Perhatian</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th>Kode</th>
                        <th>Tipe</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['critical'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['type'] }}</td>
                        <td>
                            @if($item['status'] == 'critical')
                                <span class="badge badge-danger">Kritis</span>
                            @elseif($item['status'] == 'out_of_stock')
                                <span class="badge badge-danger">Stok Habis</span>
                            @else
                                <span class="badge badge-warning">Stok Menipis</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #6B7280; padding: 20px;">
                            Semua item dalam kondisi baik!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Recent Activity Section -->
        <div class="section">
            <h2 class="section-title">Aktivitas Terbaru</h2>
            @forelse($data['activity'] as $item)
            <div class="activity-item">
                <div class="activity-icon @if($item['color'] == 'danger') bg-red @elseif($item['color'] == 'warning') bg-yellow @elseif($item['color'] == 'info') bg-blue @else bg-gray @endif">
                    <i class="ph {{ $item['icon'] }}"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-message">{{ $item['message'] }}</div>
                    <div class="activity-time">{{ $item['time'] }}</div>
                </div>
            </div>
            @empty
            <div style="text-align: center; color: #6B7280; padding: 20px;">
                Tidak ada aktivitas terbaru
            </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} Ebara Indonesia. Hak cipta dilindungi.</p>
            <p>Laporan Dashboard Eksekutif - Dibuat secara otomatis</p>
        </div>
    </div>
</body>
</html>
