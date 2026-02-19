<x-app-layout>
    <x-slot name="title">{{ __('modules.reports.title') }}</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('modules.reports.title') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('modules.reports.title') }}</h2>
                    <p class="text-gray-600 mt-1">{{ __('modules.reports.subtitle') }}</p>
                </div>
                <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                    <!-- Export Dropdown -->
                    <div x-data="{ open: false }" class="relative w-full md:w-auto">
                        <button @click="open = !open" @click.outside="open = false" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
                            <i class="ph ph-download-simple text-lg"></i>
                            <span>{{ __('modules.common.export') }}</span>
                            <i class="ph ph-caret-down text-sm" x-show="open" x-transition></i>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10" style="display: none;">
                            <a href="{{ route('reports.export') }}" id="exportExcelBtn" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition">
                                <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                                <span class="text-sm font-medium">{{ __('modules.common.export_excel') }}</span>
                            </a>
                            <a href="{{ route('reports.export-pdf') }}" id="exportPdfBtn" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition border-t border-gray-100">
                                <i class="ph ph-file-pdf text-lg text-red-600"></i>
                                <span class="text-sm font-medium">{{ __('modules.common.export_pdf') }}</span>
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('reports.create') }}" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
                        <i class="ph ph-plus text-lg"></i>
                        <span>{{ __('modules.reports.create_new') }}</span>
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total -->
                <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('modules.reports.total_reports') }}</p>
                            <h5 class="text-xl lg:text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</h5>
                        </div>
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="ph ph-file-text text-xl lg:text-2xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('modules.reports.status_pending') }}</p>
                            <h5 class="text-xl lg:text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending'] }}</h5>
                        </div>
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="ph ph-clock text-xl lg:text-2xl text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <!-- In Progress -->
                <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('modules.reports.status_in_progress') }}</p>
                            <h5 class="text-xl lg:text-2xl font-bold text-blue-600 mt-1">{{ $stats['in_progress'] }}</h5>
                        </div>
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="ph ph-spinner text-xl lg:text-2xl text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <!-- Resolved -->
                <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('modules.reports.status_resolved') }}</p>
                            <h5 class="text-xl lg:text-2xl font-bold text-green-600 mt-1">{{ $stats['resolved'] }}</h5>
                        </div>
                        <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="ph ph-check-circle text-xl lg:text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Buttons & Date Range -->
            <div class="flex flex-col xl:flex-row gap-4 justify-between items-start xl:items-center">
                <div class="flex flex-wrap gap-2">
                    <button id="filter-all" onclick="filterByStatus('all')" class="px-4 py-2 rounded-lg text-sm font-medium bg-ebara-600 text-white transition">
                        {{ __('modules.reports.all') }}
                    </button>
                    <button id="filter-Pending" onclick="filterByStatus('Pending')" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-700 transition">
                        {{ __('modules.reports.status_pending') }}
                    </button>
                    <button id="filter-In Progress" onclick="filterByStatus('In Progress')" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-700 transition">
                        {{ __('modules.reports.status_in_progress') }}
                    </button>
                    <button id="filter-Resolved" onclick="filterByStatus('Resolved')" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-700 transition">
                        {{ __('modules.reports.status_resolved') }}
                    </button>
                    <button id="filter-Rejected" onclick="filterByStatus('Rejected')" class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-200 text-gray-700 transition">
                        {{ __('modules.reports.status_rejected') }}
                    </button>
                </div>
                
                <div class="flex flex-wrap items-center gap-2 bg-white p-1.5 rounded-lg border border-gray-200 shadow-sm">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-calendar text-gray-400"></i>
                        </div>
                        <input type="date" id="startDate" onchange="filterData()" class="pl-10 pr-3 py-1.5 border-none text-sm focus:ring-0 text-gray-600 bg-transparent" placeholder="Start Date">
                    </div>
                    <span class="text-gray-400">-</span>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="ph ph-calendar text-gray-400"></i>
                        </div>
                        <input type="date" id="endDate" onchange="filterData()" class="pl-10 pr-3 py-1.5 border-none text-sm focus:ring-0 text-gray-600 bg-transparent" placeholder="End Date">
                    </div>
                    <button onclick="resetDateFilter()" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-md transition" title="Reset Date">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <x-ui.data-table-wrapper tableId="reportsTable" minWidth="1000px">
                <table id="reportsTable" class="w-full" width="100%">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                            <th class="px-4 py-3 text-left font-semibold">{{ __('modules.reports.report_code') }}</th>
                            <th class="px-4 py-3 text-left font-semibold">{{ __('modules.reports.asset_name') }}</th>
                            <th class="px-4 py-3 text-left font-semibold">{{ __('modules.reports.reporter') }}</th>
                            <th class="px-4 py-3 text-left font-semibold">{{ __('modules.reports.issue_type') }}</th>
                            <th class="px-4 py-3 text-left font-semibold">{{ __('modules.reports.priority') }}</th>
                            <th class="px-4 py-3 text-left font-semibold">{{ __('modules.common.status') }}</th>
                            <th class="px-4 py-3 text-left font-semibold hidden sm:table-cell">{{ __('modules.reports.date') }}</th>
                            <th class="px-4 py-3 text-center font-semibold">{{ __('modules.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded by DataTables -->
                    </tbody>
                </table>
            </x-ui.data-table-wrapper>
        </div>
    </div>

    @push('scripts')
    <script>
    var reports = @json($reports);
    var allReports = @json($reports);
    var currentFilter = 'all';
    var table = null;
    
    // Translation map
    var translations = {
        priority: {
            'Low': "{{ __('modules.reports.priority_low') }}",
            'Medium': "{{ __('modules.reports.priority_medium') }}",
            'High': "{{ __('modules.reports.priority_high') }}",
            'Critical': "{{ __('modules.reports.priority_critical') }}"
        },
        status: {
            'Pending': "{{ __('modules.reports.status_pending') }}",
            'In Progress': "{{ __('modules.reports.status_in_progress') }}",
            'Resolved': "{{ __('modules.reports.status_resolved') }}",
            'Rejected': "{{ __('modules.reports.status_rejected') }}"
        },
        issue_type: {
            'Damage': "{{ __('modules.reports.issue_damage') }}",
            'Maintenance': "{{ __('modules.reports.issue_maintenance') }}",
            'Lost': "{{ __('modules.reports.issue_lost') }}",
            'Stock Discrepancy': "{{ __('modules.reports.issue_stock_discrepancy') }}"
        }
    };

    $(document).ready(function() {
        // Check for success message and show SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '{{ __('modules.swal.success') }}',
                text: "{{ session('success') }}",
                showConfirmButton: true,
                confirmButtonText: '{{ __('modules.swal.ok') }}',
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
                        var routeName = '';
                        
                        if (assetType === 'AssetMaterial') routeName = 'assets.materials.show';
                        else if (assetType === 'AssetTool') routeName = 'assets.tools.show';
                        else if (assetType === 'AssetModel') routeName = 'assets.models.show';
                        
                        if (routeName) {
                            return '<a href="/assets/' + assetType.toLowerCase().replace('asset', '') + 's/' + assetId + '" class="text-ebara-600 hover:text-ebara-800 font-medium">' + assetName + '</a>';
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
                        var label = translations.issue_type[data] || data;
                        return '<div class="flex items-center gap-2"><i class="ph ' + icon + '"></i><span>' + label + '</span></div>';
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
                                <a href="/reports/${row.id}" class="text-indigo-600 hover:text-indigo-800 transition" title="{{ __('modules.reports.detail_title') }}">
                                    <i class="ph ph-eye text-xl"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            language: {
                search: "{{ __('modules.datatable.search') }}",
                lengthMenu: "{{ __('modules.datatable.length_menu') }}",
                info: "{{ __('modules.datatable.info') }}",
                infoEmpty: "{{ __('modules.datatable.info_empty') }}",
                zeroRecords: "{{ __('modules.datatable.zero_records') }}",
                emptyTable: "{{ __('modules.datatable.empty_table') }}",
                paginate: {
                    first: "{{ __('modules.datatable.first') }}",
                    last: "{{ __('modules.datatable.last') }}",
                    next: "{{ __('modules.datatable.next') }}",
                    previous: "{{ __('modules.datatable.previous') }}"
                },
                aria: {
                    sortAscending: "{{ __('modules.datatable.sort_ascending') }}",
                    sortDescending: "{{ __('modules.datatable.sort_descending') }}"
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
                button.classList.add('bg-gray-200', 'text-gray-700');
                button.classList.remove('bg-ebara-600', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500', 'bg-red-500', 'text-white');
            }
        });

        filterData();
    }

    function filterData() {
        var status = currentFilter;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        
        var filteredData = allReports.filter(function(row) {
            var matchStatus = (status === 'all') || (row.status === status);
            var matchDate = true;
            
            if (startDate) {
                var rowDate = row.created_at.substring(0, 10);
                if (rowDate < startDate) matchDate = false;
            }
            
            if (endDate) {
                var rowDate = row.created_at.substring(0, 10);
                if (rowDate > endDate) matchDate = false;
            }
            
            return matchStatus && matchDate;
        });
        
        table.clear().rows.add(filteredData).draw();
        
        // Update Export URLs
        updateExportUrls();
    }
    
    function resetDateFilter() {
        $('#startDate').val('');
        $('#endDate').val('');
        filterData();
    }
    
    function updateExportUrls() {
        var status = currentFilter;
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        
        var queryParams = [];
        if (status !== 'all') queryParams.push('status=' + status);
        if (startDate) queryParams.push('start_date=' + startDate);
        if (endDate) queryParams.push('end_date=' + endDate);
        
        var queryString = queryParams.length > 0 ? '?' + queryParams.join('&') : '';
        
        $('#exportExcelBtn').attr('href', "{{ route('reports.export') }}" + queryString);
        $('#exportPdfBtn').attr('href', "{{ route('reports.export-pdf') }}" + queryString);
    }
    </script>
    @endpush
</x-app-layout>
