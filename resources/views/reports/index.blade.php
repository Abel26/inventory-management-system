<x-app-layout>
    <x-slot name="title">Laporan Masalah</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Masalah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Laporan Masalah</h2>
                    <p class="text-gray-600 mt-1">Kelola dan pantau status laporan aset</p>
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
            </div>

            <!-- Filter Buttons -->
            <div class="flex flex-wrap gap-2">
                <button id="filter-all" onclick="filterByStatus('all')" class="px-4 py-2 rounded-lg text-sm font-medium bg-ebara-600 text-white transition">
                    Semua
                </button>
                <button id="filter-Pending" onclick="filterByStatus('Pending')" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-700 transition">
                    Pending
                </button>
                <button id="filter-In Progress" onclick="filterByStatus('In Progress')" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-700 transition">
                    In Progress
                </button>
                <button id="filter-Resolved" onclick="filterByStatus('Resolved')" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-700 transition">
                    Resolved
                </button>
                <button id="filter-Rejected" onclick="filterByStatus('Rejected')" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-700 transition">
                    Rejected
                </button>
            </div>

            <!-- Table Card -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 p-6 relative overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="reportsTable" class="w-full">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                                <th class="px-4 py-3 text-left font-semibold">Ticket ID</th>
                                <th class="px-4 py-3 text-left font-semibold">Asset</th>
                                <th class="px-4 py-3 text-left font-semibold">Reporter</th>
                                <th class="px-4 py-3 text-left font-semibold">Issue Type</th>
                                <th class="px-4 py-3 text-left font-semibold">Priority</th>
                                <th class="px-4 py-3 text-left font-semibold">Status</th>
                                <th class="px-4 py-3 text-left font-semibold">Date</th>
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
    </div>

    @push('scripts')
    <script>
    var reports = @json($reports);
    var allReports = @json($reports);
    var currentFilter = 'all';
    var table = null;

    $(document).ready(function() {
        // Check for success message and show SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#059669',
                timer: 5000,
                timerProgressBar: true
            });
        @endif

        table = $('#reportsTable').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            data: allReports,
            columns: [
                {
                    data: 'report_code',
                    render: function(data, type, row) {
                        return '<span class="font-mono text-sm">' + data + '</span>';
                    }
                },
                {
                    data: 'reportable',
                    render: function(data, type, row) {
                        if (!data) return '-';
                        var assetName = data.name || data.model_name || 'Unknown Asset';
                        var assetType = row.reportable_type.split('\\').pop();
                        var assetId = row.reportable_id;

                        // Determine route based on asset type
                        var routeName = '';
                        if (assetType === 'AssetMaterial') routeName = 'assets.materials.show';
                        else if (assetType === 'AssetTool') routeName = 'assets.tools.show';
                        else if (assetType === 'AssetModel') routeName = 'assets.models.show';

                        if (routeName) {
                            return '<a href="/assets/' + assetType.toLowerCase().replace('asset', '') + '/' + assetId + '" class="text-ebara-600 hover:text-ebara-800 font-medium">' + assetName + '</a>';
                        }
                        return assetName;
                    }
                },
                {
                    data: 'user.name',
                    render: function(data, type, row) {
                        return data || '-';
                    }
                },
                {
                    data: 'issue_type',
                    render: function(data, type, row) {
                        var icons = {
                            'Damage': 'ph-warning-circle',
                            'Maintenance': 'ph-wrench',
                            'Lost': 'ph-ghost',
                            'Stock Discrepancy': 'ph-chart-bar'
                        };
                        var icon = icons[data] || 'ph-file-text';
                        return '<div class="flex items-center gap-2"><i class="ph ' + icon + '"></i><span>' + data + '</span></div>';
                    }
                },
                {
                    data: 'priority',
                    render: function(data, type, row) {
                        var colors = {
                            'Low': 'bg-gray-100 text-gray-800',
                            'Medium': 'bg-blue-100 text-blue-800',
                            'High': 'bg-orange-100 text-orange-800',
                            'Critical': 'bg-red-100 text-red-800'
                        };
                        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' + (colors[data] || 'bg-gray-100 text-gray-800') + '">' + data + '</span>';
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        var colors = {
                            'Pending': 'bg-yellow-100 text-yellow-800',
                            'In Progress': 'bg-blue-100 text-blue-800',
                            'Resolved': 'bg-green-100 text-green-800',
                            'Rejected': 'bg-red-100 text-red-800'
                        };
                        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' + (colors[data] || 'bg-gray-100 text-gray-800') + '">' + data + '</span>';
                    }
                },
                {
                    data: 'created_at',
                    render: function(data, type, row) {
                        return data ? new Date(data).toLocaleDateString('id-ID') : '-';
                    }
                },
                {
                    data: 'actions',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <a href="/reports/${row.id}" class="text-indigo-600 hover:text-indigo-800 transition" title="Lihat Detail">
                                    <i class="ph ph-eye text-xl"></i>
                                </a>
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

    function filterByStatus(status) {
        currentFilter = status;

        // Update button styles
        var buttons = ['all', 'Pending', 'In Progress', 'Resolved', 'Rejected'];
        buttons.forEach(function(btn) {
            var button = document.getElementById('filter-' + btn);
            if (btn === status) {
                button.classList.remove('bg-gray-200', 'text-gray-700');
                if (status === 'all') {
                    button.classList.add('bg-ebara-600', 'text-white');
                } else if (status === 'Pending') {
                    button.classList.add('bg-yellow-500', 'text-white');
                } else if (status === 'In Progress') {
                    button.classList.add('bg-blue-500', 'text-white');
                } else if (status === 'Resolved') {
                    button.classList.add('bg-green-500', 'text-white');
                } else if (status === 'Rejected') {
                    button.classList.add('bg-red-500', 'text-white');
                }
            } else {
                button.classList.remove('bg-ebara-600', 'text-white', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500', 'bg-red-500');
                button.classList.add('bg-gray-200', 'text-gray-700');
            }
        });

        // Filter data
        var filteredData = allReports;
        if (status !== 'all') {
            filteredData = allReports.filter(function(row) {
                return row.status === status;
            });
        }

        // Update DataTable
        table.clear().rows.add(filteredData).draw();
    }
    </script>
    @endpush
</x-app-layout>

