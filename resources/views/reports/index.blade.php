<x-app-layout>
    <x-slot name="title">Laporan</x-slot>
    
    @push('styles')
    <style>
        /* DataTables Custom Styling */
        .dataTables_wrapper .dataTables_length select {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            font-size: 0.875rem;
            color: #374151;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #009B77;
            box-shadow: 0 0 0 3px rgba(0, 155, 119, 0.1);
        }
        
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            font-size: 0.875rem;
            color: #374151;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
            max-width: 300px;
            margin-bottom: 1.5rem;
        }
        
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #009B77;
            box-shadow: 0 0 0 3px rgba(0, 155, 119, 0.1);
        }
        
        .dataTables_wrapper .dataTables_info {
            padding-top: 1rem;
            padding-bottom: 0.5rem;
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0;
        }
        
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 0.5rem;
            padding-bottom: 0;
            margin-bottom: 0;
            display: flex;
            justify-content: flex-end;
            gap: 0.25rem;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            margin: 0 2px;
            padding: 6px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background-color: white;
            color: #374151 !important;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f3f4f6;
            color: #111827 !important;
            border-color: #d1d5db;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #4f46e5 !important;
            color: white !important;
            border-color: #4f46e5 !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .dataTables_wrapper .dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            margin-left: -100px;
            margin-top: -25px;
            border: 1px solid #ddd;
            text-align: center;
            color: #333;
            font-size: 14px;
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Fix table wrapper overflow */
        .dataTables_wrapper {
            width: 100%;
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        
        .dataTables_wrapper table {
            width: 100% !important;
            margin-bottom: 0 !important;
        }
        
        /* Fix excessive spacing from DataTables */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 0.5rem !important;
        }
        
        /* Fix alignment for length and filter */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            display: flex;
            align-items: center;
        }
        
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            font-weight: 500;
            color: #6b7280;
            font-size: 0.875rem;
            white-space: nowrap;
        }
        
        /* Clear floats for pagination */
        .dataTables_wrapper:after {
            content: "";
            display: table;
            clear: both;
        }
        
        /* Mobile Responsiveness */
        @media (max-width: 640px) {
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: none;
                text-align: left;
                display: flex;
                flex-direction: column;
                width: 100%;
                margin-bottom: 1rem;
                align-items: stretch;
            }
            
            .dataTables_wrapper .dataTables_length select,
            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
    @endpush
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan</h1>
                <p class="text-gray-600 mt-1">Kelola dan pantau status laporan aset</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('reports.scan') }}" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                    <i class="ph ph-qr-code text-lg"></i>
                    <span>Scan QR Code</span>
                </a>
                <a href="{{ route('reports.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                    <i class="ph ph-plus text-lg"></i>
                    <span>Buat Laporan</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Laporan</p>
                        <h5 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</h5>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-file-text text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Pending -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Pending</p>
                        <h5 class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending'] }}</h5>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- In Progress -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">In Progress</p>
                        <h5 class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['in_progress'] }}</h5>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-spinner text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Resolved -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Resolved</p>
                        <h5 class="text-2xl font-bold text-green-600 mt-1">{{ $stats['resolved'] }}</h5>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
            
            <!-- Rejected -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Rejected</p>
                        <h5 class="text-2xl font-bold text-red-600 mt-1">{{ $stats['rejected'] }}</h5>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-x-circle text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-100 p-6 relative overflow-hidden">
            <div class="overflow-x-auto">
                <table id="reportsTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                            <th class="px-4 py-3 text-left font-semibold">Kode Laporan</th>
                            <th class="px-4 py-3 text-left font-semibold">Jenis Masalah</th>
                            <th class="px-4 py-3 text-left font-semibold">Prioritas</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-left font-semibold">Tanggal Dibuat</th>
                            <th class="px-4 py-3 text-left font-semibold">Dilaporkan Oleh</th>
                            <th class="px-4 py-3 text-left font-semibold">Diselesaikan Oleh</th>
                            <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    var reports = @json($reports);

    $(document).ready(function() {
        let table = $('#reportsTable').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            data: reports,
            columns: [
                { data: 'report_code' },
                { data: 'issue_type' },
                { data: 'priority' },
                { 
                    data: 'description',
                    render: function(data, type, row) {
                        if (data) {
                            return '<span class="line-clamp-2">' + data + '</span>';
                        }
                        return '';
                    }
                },
                { 
                    data: 'status',
                    render: function(data, type, row) {
                        var badgeClass = '';
                        var badgeText = '';
                        
                        switch(data) {
                            case 'Pending':
                                badgeClass = 'bg-yellow-100 text-yellow-800';
                                badgeText = 'Pending';
                                break;
                            case 'In Progress':
                                badgeClass = 'bg-blue-100 text-blue-800';
                                badgeText = 'In Progress';
                                break;
                            case 'Resolved':
                                badgeClass = 'bg-green-100 text-green-800';
                                badgeText = 'Resolved';
                                break;
                            case 'Rejected':
                                badgeClass = 'bg-red-100 text-red-800';
                                badgeText = 'Rejected';
                                break;
                            default:
                                badgeClass = 'bg-gray-100 text-gray-800';
                                badgeText = data;
                        }
                        
                        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' + badgeClass + '">' + badgeText + '</span>';
                    }
                },
                { data: 'created_at' },
                { 
                    data: 'resolved_at',
                    render: function(data, type, row) {
                        var createdAt = data ? new Date(data) : '-';
                        var resolvedAt = row.resolved_at ? new Date(row.resolved_at) : '-';
                        
                        return '<div class="text-sm">' + createdAt + '</div>';
                    }
                },
                {
                    data: 'user.name',
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    data: 'resolver.name',
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    data: 'actions',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('reports.show', ['id' => row.id]) }}" class="text-indigo-600 hover:text-indigo-800 transition" title="Lihat Detail">
                                    <i class="ph ph-eye text-xl"></i>
                                </a>
                                <button onclick="updateStatus('${row.id}', 'Resolved')" class="text-green-600 hover:text-green-800 transition" title="Selesaikan Selesai">
                                    <i class="ph ph-check-circle text-xl"></i>
                                </button>
                                @if($row.status !== 'Resolved')
                                    <button onclick="updateStatus('${row.id}', 'Rejected')" class="text-red-600 hover:text-red-800 transition" title="Tolak Laporan">
                                        <i class="ph ph-x-circle text-xl"></i>
                                    </button>
                                @endif
                            </div>
                        `;
                    }
                }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang tersedia",
                zeroRecords: "Tidak ada data yang cocok",
                emptyTable: "Tidak ada data tersedia di tabel",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                aria: {
                    sortAscending: ": aktifkan untuk mengurutkan kolom secara ascending",
                    sortDescending: ": aktifkan untuk mengurutkan kolom secara descending"
                }
            },
            dom: '<"flex flex-col sm:flex-row justify-between items-center gap-4 mb-4"lf>rt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4"ip>',
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
        });
    });

    function updateStatus(id, status) {
        $.ajax({
            url: "{{ route('reports.update-status', ['id' => id]) }}",
            method: 'PUT',
            data: {
                status: status
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Status laporan berhasil diperbarui',
                        confirmButtonColor: '#009B77'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonColor: '#dc2626'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memperbarui status laporan',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }
    </script>
</x-app-layout>
