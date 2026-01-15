<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan Alat Aset' }} - PT. EBARA INDONESIA</title>
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
            text-align: center;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #009B77;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .company-address {
            font-size: 10px;
            color: #555;
            line-height: 1.4;
        }

        .separator {
            margin: 15px 0;
        }

        .separator-table {
            width: 100%;
            border-collapse: collapse;
        }

        .separator-table tr {
            height: 3px;
        }

        .separator-table .line-thick {
            border-top: 3px solid #009B77;
        }

        .separator-table .line-thin {
            border-top: 1px solid #009B77;
        }

        .report-title {
            text-align: center;
            margin: 20px 0;
        }

        .report-title h2 {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .report-title p {
            font-size: 11px;
            color: #666;
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

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-secondary {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .content {
            min-height: 600px;
        }

        .summary-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 33%;
            text-align: center;
            vertical-align: top;
        }

        .signature-name {
            margin-top: 80px;
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
        <h2>{{ $title ?? 'Laporan Data Alat' }}</h2>
        <p>{{ $subtitle ?? 'Data Inventaris Alat dan Peralatan' }}</p>
    </div>

    <!-- Content -->
    <div class="content">
        @if($tools->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 12%;">Kode</th>
                        <th style="width: 18%;">Nama</th>
                        <th style="width: 10%;">Kategori</th>
                        <th style="width: 10%;">Merk</th>
                        <th style="width: 8%;">Tipe</th>
                        <th style="width: 6%;">Tahun</th>
                        <th style="width: 6%;">Jml</th>
                        <th style="width: 10%;">Kondisi</th>
                        <th style="width: 15%;">Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tools as $index => $tool)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $tool->tool_code }}</td>
                            <td>{{ $tool->name }}</td>
                            <td>{{ $tool->category }}</td>
                            <td>{{ $tool->brand ?? '-' }}</td>
                            <td>{{ $tool->type ?? '-' }}</td>
                            <td style="text-align: center;">{{ $tool->purchase_year ?? '-' }}</td>
                            <td style="text-align: center;">{{ $tool->quantity }}</td>
                            <td style="text-align: center;">
                                @if($tool->condition === 'Good')
                                    <span class="badge badge-success">Baik</span>
                                @elseif($tool->condition === 'Repair')
                                    <span class="badge badge-warning">Perbaikan</span>
                                @elseif($tool->condition === 'Damaged')
                                    <span class="badge badge-danger">Rusak</span>
                                @elseif($tool->condition === 'Disposed')
                                    <span class="badge badge-secondary">Dibuang</span>
                                @else
                                    <span class="badge badge-secondary">{{ $tool->condition }}</span>
                                @endif
                            </td>
                            <td>{{ $tool->location ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; font-size: 14px; color: #666; padding: 50px 0;">
                <strong>Tidak ada data yang tersedia.</strong>
            @endif
    </div>

    <!-- Summary Section -->
    @if($tools->count() > 0)
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td style="width: 50%; vertical-align: top; padding: 10px; border: 1px solid #ddd;">
                        <strong>Ringkasan Data:</strong><br><br>
                        Total Alat: <strong>{{ $tools->count() }}</strong> item<br>
                        Kondisi Baik: <strong>{{ $tools->where('condition', 'Good')->count() }}</strong> item<br>
                        Perlu Perbaikan: <strong>{{ $tools->where('condition', 'Repair')->count() }}</strong> item<br>
                        Rusak: <strong>{{ $tools->where('condition', 'Damaged')->count() }}</strong> item<br>
                        Dibuang: <strong>{{ $tools->where('condition', 'Disposed')->count() }}</strong> item
                    </td>
                    <td style="width: 50%; vertical-align: top; padding: 10px; border: 1px solid #ddd;">
                        <strong>Distribusi per Kategori:</strong><br><br>
                        @foreach($tools->groupBy('category') as $category => $items)
                            {{ $category }}: <strong>{{ $items->count() }}</strong> item<br>
                        @endforeach
                        <br>
                        <em style="font-size: 10px; color: #666;">* Laporan ini dibuat pada tanggal {{ $date }}</em>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    <!-- Signature Section -->
    @if($tools->count() > 0)
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        <p>Dibuat Oleh,</p>
                        <div class="signature-name">________________</div>
                        <div class="signature-title">Staff Inventory</div>
                    </td>
                    <td>
                        <p>Diperiksa Oleh,</p>
                        <div class="signature-name">________________</div>
                        <div class="signature-title">Supervisor</div>
                    </td>
                    <td>
                        <p>Disetujui Oleh,</p>
                        <div class="signature-name">________________</div>
                        <div class="signature-title">Manager</div>
                    </td>
                </tr>
            </table>
        </div>
    @endif
</body>
</html>
