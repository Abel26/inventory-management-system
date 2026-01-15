<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Laporan' }} - PT. EBARA INDONESIA</title>
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

        /* Header / Kop Surat */
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

        /* Double line separator */
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

        /* Report Title */
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

        /* Table Styles */
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
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data-table tbody tr:hover {
            background-color: #f0f0f0;
        }

        /* Status Badges */
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

        /* Footer */
        .footer {
            position: fixed;
            bottom: 30px;
            left: 50px;
            right: 50px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        /* Content wrapper */
        .content {
            min-height: 600px;
        }

        /* Signature section */
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

        /* Print styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
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
        <h2>{{ $title ?? 'Laporan' }}</h2>
        <p>{{ $subtitle ?? '' }}</p>
    </div>

    <!-- Content -->
    <div class="content">
        {{ $slot ?? '' }}
    </div>

    <!-- Footer with Page Numbers -->
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial", "normal");
                $size = 10;
                $color = array(0.5, 0.5, 0.5);
                $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
                $x = $pdf->get_width() - 50;
                $y = $pdf->get_height() - 30;
                $pdf->text($x, $y, $text, $font, $size, $color, "right");
            ');
        }
    </script>
</body>
</html>
