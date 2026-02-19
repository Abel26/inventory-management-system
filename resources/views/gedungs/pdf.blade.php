<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan Data Gedung' }} - PT. EBARA INDONESIA</title>
    <style>
        @page {
            margin: 50px 50px 60px 50px;
            size: A4;
            orientation: portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.5;
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
            width: 100px;
        }

        .logo-cell img {
            width: 90px;
            height: auto;
        }

        .company-info {
            text-align: right;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #009B77;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 11px;
            color: #666;
            line-height: 1.3;
        }

        .separator {
            margin: 15px 0;
        }

        .separator-table {
            width: 100%;
            border-collapse: collapse;
        }

        .line-thick {
            height: 3px;
            background-color: #009B77;
        }

        .line-thin {
            height: 1px;
            background-color: #ccc;
        }

        .report-title {
            text-align: center;
            margin: 20px 0;
        }

        .report-title h2 {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .report-title p {
            font-size: 11px;
            color: #666;
        }

        .content {
            margin-top: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table thead {
            background-color: #009B77;
            color: #fff;
        }

        .data-table th {
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            border: 1px solid #007a5f;
        }

        .data-table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
            text-align: left;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .summary-section {
            margin-top: 30px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        .summary-label {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 40%;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .no-data {
            text-align: center;
            padding: 50px;
            font-size: 14px;
            color: #666;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin: 80px 0 10px 0;
        }

        .signature-name {
            margin-top: 10px;
            font-weight: bold;
        }

        .signature-title {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header / Kop Surat -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('assets/img/logo.png') }}" alt="PT. EBARA INDONESIA Logo">
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

    <!-- Double Line Separator -->
    <div class="separator">
        <table class="separator-table">
            <tr class="line-thick"></tr>
            <tr class="line-thin"></tr>
        </table>
    </div>

    <!-- Report Title -->
    <div class="report-title">
        <h2>{{ $title ?? 'Laporan Data Gedung' }}</h2>
        <p>{{ $subtitle ?? 'Data Seluruh Gedung dan Total Aset' }}</p>
    </div>

    <!-- Content -->
    <div class="content">
        @if($gedungs->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Kode Gedung</th>
                        <th style="width: 25%;">Nama Gedung</th>
                        <th style="width: 15%;">Total Model</th>
                        <th style="width: 15%;">Total Material</th>
                        <th style="width: 15%;">Total Tools</th>
                        <th style="width: 10%;">Total Aset</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gedungs as $index => $gedung)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $gedung->gedung_id }}</td>
                            <td>{{ $gedung->nama }}</td>
                            <td style="text-align: center;">{{ $gedung->asset_models_count ?? 0 }}</td>
                            <td style="text-align: center;">{{ $gedung->asset_materials_count ?? 0 }}</td>
                            <td style="text-align: center;">{{ $gedung->asset_tools_count ?? 0 }}</td>
                            <td style="text-align: center; font-weight: bold;">{{ ($gedung->asset_models_count ?? 0) + ($gedung->asset_materials_count ?? 0) + ($gedung->asset_tools_count ?? 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary Section -->
            <div class="summary-section">
                <table class="summary-table">
                    <tr>
                        <td class="summary-label">Total Gedung</td>
                        <td>{{ $gedungs->count() }} gedung</td>
                    </tr>
                    <tr>
                        <td class="summary-label">Total Model Aset</td>
                        <td>{{ $gedungs->sum('asset_models_count') }} model</td>
                    </tr>
                    <tr>
                        <td class="summary-label">Total Material Aset</td>
                        <td>{{ $gedungs->sum('asset_materials_count') }} item</td>
                    </tr>
                    <tr>
                        <td class="summary-label">Total Tools Aset</td>
                        <td>{{ $gedungs->sum('asset_tools_count') }} alat</td>
                    </tr>
                    <tr>
                        <td class="summary-label">Total Seluruh Aset</td>
                        <td><strong>{{ $gedungs->sum(function($g) { return ($g->asset_models_count ?? 0) + ($g->asset_materials_count ?? 0) + ($g->asset_tools_count ?? 0); }) }} item</strong></td>
                    </tr>
                </table>
            </div>
        @else
            <p style="text-align: center; font-size: 14px; color: #666; padding: 50px 0;">
                <strong>Tidak ada data yang tersedia.</strong>
            </p>
        @endif
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">_________________________</div>
            <div class="signature-title">Admin Inventaris</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">_________________________</div>
            <div class="signature-title">Manager Gedung</div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dicetak secara otomatis pada {{ $date ?? date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>