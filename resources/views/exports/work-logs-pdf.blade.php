<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pekerjaan</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; color: #008065; margin-bottom: 10px; }
        .period { font-size: 14px; color: #666; margin-bottom: 20px; }
        .summary { text-align: center; margin-bottom: 20px; padding: 15px; background-color: #f3f4f6; border-radius: 8px; }
        .summary-value { font-size: 16px; font-weight: bold; color: #008065; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th { background-color: #008065; color: white; padding: 10px; font-weight: bold; text-align: left; font-size: 11px; }
        .table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .table tr:nth-child(even) { background-color: #f9f9f9; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 500; }
        .badge-completed { background-color: #10b981; color: white; }
        .badge-in_progress { background-color: #3b82f6; color: white; }
        .badge-pending { background-color: #9ca3af; color: white; }
        .badge-on_hold { background-color: #f59e0b; color: white; }
        .badge-cancelled { background-color: #ef4444; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Laporan Pekerjaan</h1>
        <p class="period">
            Periode: {{ $startDate ?? 'Semua' }} s/d {{ $endDate ?? 'Semua' }}
        </p>
    </div>

    <div class="summary">
        <p><strong>Total Jam Kerja:</strong> <span class="summary-value">{{ number_format($totalHours, 2, ',', '.') }} jam</span></p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Pegawai</th>
                <th>Tanggal</th>
                <th>Jam Kerja</th>
                <th>Total</th>
                <th>Deskripsi</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workLogs as $workLog)
            <tr>
                <td>{{ $workLog->work_code }}</td>
                <td>{{ $workLog->user->name ?? '-' }}</td>
                <td>{{ $workLog->work_date->format('d M Y') }}</td>
                <td>{{ $workLog->start_time->format('H:i') }} - {{ $workLog->end_time ? $workLog->end_time->format('H:i') : '-' }}</td>
                <td>{{ $workLog->total_work_minutes ? round($workLog->total_work_minutes / 60, 1) . 'j' : '-' }}</td>
                <td>{{ $workLog->description }}</td>
                <td>{{ $workLog->work_type ? $workLog->work_type->getLabel() : '-' }}</td>
                <td>
                    <span class="badge badge-{{ $workLog->status->value }}">{{ $workLog->status->getLabel() }}</span>
                </td>
                <td>{{ $workLog->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
