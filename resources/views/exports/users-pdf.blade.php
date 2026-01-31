<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info {
            margin-bottom: 20px;
        }
        .info span {
            margin-right: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Manajemen User</h1>
        <p>EBARA Inventory Management System</p>
        <p>{{ date('d F Y') }}</p>
    </div>

    <div class="info">
        <span><strong>Total User:</strong> {{ $users->count() }}</span>
        <span><strong>User Aktif:</strong> {{ $users->where('is_active', true)->count() }}</span>
        <span><strong>User Tidak Aktif:</strong> {{ $users->where('is_active', false)->count() }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>No. Telepon</th>
                <th>Role</th>
                <th>Status</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($users as $user)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->phone_number ?? '-' }}</td>
                    <td>{{ $user->roles_list }}</td>
                    <td>{{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dicetak pada {{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>