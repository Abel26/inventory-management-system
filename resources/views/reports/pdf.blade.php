<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan Masalah' }} - PT. EBARA INDONESIA</title>
    <style>
        @page {
            margin: 30px 30px 40px 30px;
            size: A4;
            orientation: landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background-color: #fff;
        }

        .header {
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            padding: 5px;
            vertical-align: middle;
        }

        .logo-cell {
            width: 60px;
        }

        .logo-cell img {
            width: 50px;
            height: auto;
        }

        .company-info {
            text-align: center;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #009B77;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .company-address {
            font-size: 9px;
            color: #555;
            line-height: 1.3;
        }

        .separator {
            margin: 10px 0;
        }

        .separator-table {
            width: 100%;
            border-collapse: collapse;
        }

        .separator-table tr {
            height: 3px;
        }

        .separator-table .line-thick {
            border-top: 2px solid #009B77;
        }

        .separator-table .line-thin {
            border-top: 1px solid #009B77;
        }

        .report-title {
            text-align: center;
            margin: 15px 0;
        }

        .report-title h2 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .report-title p {
            font-size: 10px;
            color: #666;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table thead {
            background-color: #009B77;
            color: #fff;
        }

        .data-table th {
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #007a5f;
            white-space: nowrap;
        }

        .data-table td {
            padding: 5px;
            border: 1px solid #ddd;
            font-size: 10px;
            text-align: left;
            vertical-align: top;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            white-space: nowrap;
        }

        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .badge-secondary { background-color: #e2e3e5; color: #383d41; }
        .badge-info { background-color: #d1ecf1; color: #0c5460; }
        .badge-orange { background-color: #ffeeba; color: #856404; }

        .content {
            min-height: 400px;
        }

        .summary-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            width: 50%;
            vertical-align: top;
            padding: 8px;
            border: 1px solid #ddd;
        }
        
        .footer-note {
             margin-top: 10px;
             font-size: 9px;
             font-style: italic;
             color: #666;
             text-align: right;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="Logo">
                </td>
                <td class="company-info">
                    <div class="company-name">PT. EBARA INDONESIA</div>
                    <div class="company-address">
                        Jl. Raya Jakarta-Bogor No.KM.32, Curug, Kec. Cimanggis,<br>
                        Kota Depok, Jawa Barat 16453
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Separator -->
    <div class="separator">
        <table class="separator-table">
            <tr class="line-thick"></tr>
            <tr class="line-thin"></tr>
        </table>
    </div>

    <!-- Report Title -->
    <div class="report-title">
        <h2>{{ $title ?? 'Laporan Masalah' }}</h2>
        <p>{{ $subtitle ?? 'Daftar Laporan Masalah dan Kerusakan Aset' }}</p>
    </div>

    <!-- Content -->
    <div class="content">
        @if(count($reports) > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 3%;">No</th>
                        <th style="width: 10%;">Kode</th>
                        <th style="width: 15%;">Aset</th>
                        <th style="width: 10%;">Pelapor</th>
                        <th style="width: 10%;">Masalah</th>
                        <th style="width: 8%;">Prioritas</th>
                        <th style="width: 8%;">Status</th>
                        <th style="width: 26%;">Deskripsi</th>
                        <th style="width: 10%;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $index => $report)
                    @php
                        $assetName = $report->reportable ? ($report->reportable->name ?? $report->reportable->model_name ?? '-') : '-';
                    @endphp
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $report->report_code }}</td>
                            <td>{{ $assetName }}</td>
                            <td>{{ $report->user->name ?? '-' }}</td>
                            <td>
                                @if($report->issue_type === 'Damage') Kerusakan
                                @elseif($report->issue_type === 'Maintenance') Perawatan
                                @elseif($report->issue_type === 'Lost') Kehilangan
                                @elseif($report->issue_type === 'Stock Discrepancy') Selisih Stok
                                @else {{ $report->issue_type }}
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($report->priority === 'High') <span class="badge badge-orange">Tinggi</span>
                                @elseif($report->priority === 'Critical') <span class="badge badge-danger">Kritis</span>
                                @elseif($report->priority === 'Medium') <span class="badge badge-info">Sedang</span>
                                @else <span class="badge badge-secondary">Rendah</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($report->status === 'Resolved') <span class="badge badge-success">Selesai</span>
                                @elseif($report->status === 'In Progress') <span class="badge badge-warning">Diproses</span>
                                @elseif($report->status === 'Pending') <span class="badge badge-secondary">Menunggu</span>
                                @else <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($report->description, 100) }}</td>
                            <td>{{ $report->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; font-size: 14px; color: #666; padding: 50px 0;">
                <strong>Tidak ada data laporan yang tersedia.</strong>
            </p>
        @endif
    </div>

    <!-- Summary Section -->
    @if(count($reports) > 0)
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td>
                        <strong>Ringkasan Status:</strong><br><br>
                        Total Laporan: <strong>{{ $reports->count() }}</strong><br>
                        Selesai: <strong>{{ $reports->where('status', 'Resolved')->count() }}</strong><br>
                        Diproses: <strong>{{ $reports->where('status', 'In Progress')->count() }}</strong><br>
                        Menunggu: <strong>{{ $reports->where('status', 'Pending')->count() }}</strong>
                    </td>
                    <td>
                        <strong>Ringkasan Masalah:</strong><br><br>
                        Kerusakan: <strong>{{ $reports->where('issue_type', 'Damage')->count() }}</strong><br>
                        Perawatan: <strong>{{ $reports->where('issue_type', 'Maintenance')->count() }}</strong><br>
                        Kehilangan: <strong>{{ $reports->where('issue_type', 'Lost')->count() }}</strong>
                    </td>
                </tr>
            </table>
            <div class="footer-note">* Laporan ini dibuat pada tanggal {{ $date }}</div>
        </div>
    @endif
</body>
</html>
