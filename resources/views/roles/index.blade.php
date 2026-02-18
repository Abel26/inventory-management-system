<x-app-layout>
    <x-slot name="title">{{ __('modules.roles.title') }}</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('modules.roles.title') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('modules.roles.subtitle') }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Export Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button type="button" @click="open = !open" @click.away="open = false" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                        <i class="ph ph-export text-lg"></i>
                        <span>{{ __('modules.common.export') }}</span>
                        <i class="ph ph-caret-down text-sm"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white shadow-xl rounded-lg border border-gray-200 z-50">
                        <div class="py-1">
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
                                <i class="ph ph-file-csv mr-2"></i>
                                Export CSV
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
                                <i class="ph ph-file mr-2"></i>
                                Export Excel
                            </a>
                        </div>
                    </div>
                </div>
                
                <button type="button" id="createNewRole" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                    <i class="ph ph-plus text-lg"></i>
                    <span>{{ __('modules.roles.add_title') }}</span>
                </button>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-8 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">{{ __('modules.roles.total_roles') }}</p>
                        <div class="flex items-center mt-2">
                            <span id="totalRolesSpinner" class="animate-spin h-4 w-4 border-2 border-gray-300 border-t-ebara-600 rounded-full mr-3 hidden"></span>
                            <span id="totalRolesText" class="text-3xl font-bold text-gray-900">{{ $roles->count() ?? 0 }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">User access levels</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-ebara-500 to-ebara-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ph ph-users text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 p-8 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">{{ __('modules.roles.permissions_count') }}</p>
                        <div class="flex items-center mt-2">
                            <span id="totalPermissions" class="text-3xl font-bold text-gray-900">{{ $permissions->count() }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Available permissions</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="ph ph-shield-check text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        

        
        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">{{ __('modules.roles.title') }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ __('modules.roles.subtitle') }}</p>
            </div>
            <div class="overflow-x-auto">
                <table id="rolesTable" class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('modules.roles.role_name') }}</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('modules.roles.guard_name') }}</th>
                            <th class="px-8 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('modules.roles.permissions_count') }}</th>
                            <th class="px-8 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('modules.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
    var storeUrl = "{{ route('roles.store') }}";
    var showUrlTemplate = "{{ route('roles.show', ':id') }}";
    var updateUrlTemplate = "{{ route('roles.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('roles.destroy', ':id') }}";
    var getPermissionsByModuleUrl = "{{ route('roles.permissions.by-module') }}";
    var dataUrl = "{{ route('roles.data') }}";

    $(document).ready(function() {
        // Check if DataTables is available
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTables is not loaded');
            return;
        }
        
        // Initialize DataTables directly (like other working tables)
        let table = $('#rolesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: dataUrl,
            searching: true,
            paging: true,
            ordering: true,
            info: true,
            lengthChange: true,
            pageLength: 10,
            order: [[0, 'asc']],
            createdRow: function(row, data, dataIndex) {
                $(row).addClass('group hover:bg-gray-50 transition-colors duration-200');
            },
            columnDefs: [
                {
                    targets: 0, // Nama Role
                    className: 'px-8 py-4 text-sm font-semibold text-gray-900'
                },
                {
                    targets: 1, // Guard
                    className: 'px-8 py-4 text-sm text-gray-600'
                },
                {
                    targets: 2, // Jumlah Permission
                    className: 'px-8 py-4 text-sm text-center'
                },
                {
                    targets: 3, // Aksi
                    className: 'px-8 py-4 text-center',
                    width: '140px'
                }
            ],
            columns: [
                { 
                    data: 'name', 
                    name: 'name'
                },
                { 
                    data: 'guard_name', 
                    name: 'guard_name'
                },
                {
                    data: 'permissions_count',
                    name: 'permissions_count',
                    searchable: false,
                    render: function(data, type, row) {
                        const badgeColor = data > 20 ? 'bg-green-100 text-green-800' : data > 10 ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800';
                        return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${badgeColor}">${data} permissions</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center space-x-3">
                                <button onclick="editRole(${row.id})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200" title="{{ __('modules.common.edit') }}">
                                    <i class="ph ph-pencil-simple text-sm"></i>
                                </button>
                                <button onclick="deleteRole(${row.id})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors duration-200" title="{{ __('modules.common.delete') }}">
                                    <i class="ph ph-trash text-sm"></i>
                                </button>
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
                        next: "{{ __('modules.datatable.next') }}",
                        previous: "{{ __('modules.datatable.previous') }}"
                    },
                    processing: "{{ __('modules.datatable.processing') }}"
                },
            dom: '<"flex flex-col sm:flex-row justify-between items-center gap-4 mb-4"lf>rt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4"ip>',
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
        });
        
        // Update stats on data load
        table.on('xhr.dt', function(e, settings, json, xhr) {
            if (json && typeof json.recordsTotal !== 'undefined') {
                // Update total roles counter with animation
                $('#totalRolesSpinner').addClass('hidden');
                $('#totalRolesText').text(json.recordsTotal);
            }
        });
        
        // Add loading state
        table.on('preXhr.dt', function(e, settings, data) {
            $('#totalRolesSpinner').removeClass('hidden');
        });
        
        // Set total permissions immediately
        $('#totalPermissions').text({{ $permissions->count() }});
    });
    </script>
    @endpush
</x-app-layout>