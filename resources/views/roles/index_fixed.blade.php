<x-app-layout>
    <x-slot name="title">Manajemen Role</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Role</h1>
                <p class="text-gray-600 mt-1">Kelola peran dan hak akses pengguna</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Export Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button type="button" @click="open = !open" @click.away="open = false" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                        <i class="ph ph-export text-lg"></i>
                        <span>Ekspor</span>
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
                    <span>Tambah Role</span>
                </button>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Role</p>
                        <div class="flex items-center mt-1">
                            <span id="totalRolesSpinner" class="animate-spin h-4 w-4 border-2 border-gray-300 border-t-ebara-600 rounded-full mr-2"></span>
                            <span id="totalRolesText" class="text-2xl font-bold text-gray-900">Loading...</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-ebara-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-users text-ebara-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Permission</p>
                        <p id="totalPermissions" class="text-2xl font-bold text-gray-900">{{ $permissions->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-shield-check text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Quick Debug</p>
                        <a href="/roles/debug-endpoint" target="_blank" class="text-yellow-600 hover:text-yellow-800 font-medium inline-flex items-center gap-1 mt-1">
                            <i class="ph ph-bug-beetle text-lg"></i>
                            Test API Endpoint
                        </a>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-gear text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- No Data Alert (Hidden by default) -->
        <div id="noDataAlert" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg hidden">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="ph ph-warning text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Tidak Ada Data Ditemukan</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Data role tidak ditemukan. Kemungkinan penyebab:</p>
                        <ul class="mt-1 ml-4 list-disc">
                            <li>Database kosong atau belum di-seed</li>
                            <li>API endpoint bermasalah</li>
                            <li>Koneksi database terputus</li>
                        </ul>
                        <p class="mt-2 font-medium">Solusi: Jalankan <code class="bg-yellow-100 px-1 rounded">php artisan db:seed --class=FreshRolePermissionSeeder</code></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Table Card -->
        <x-ui.data-table-wrapper tableId="rolesTable" minWidth="700px">
            <table id="rolesTable" class="w-full">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <th class="px-6 py-3 text-left">Nama Role</th>
                        <th class="px-6 py-3 text-left">Guard</th>
                        <th class="px-6 py-3 text-center">Jumlah Permission</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded by DataTables -->
                </tbody>
            </table>
        </x-ui.data-table-wrapper>
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
                    className: 'text-sm font-medium'
                },
                {
                    targets: 1, // Guard
                    className: 'text-sm'
                },
                {
                    targets: 2, // Jumlah Permission
                    className: 'text-sm text-center'
                },
                {
                    targets: 3, // Aksi
                    className: 'text-center',
                    width: '120px'
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
                        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${data}</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="flex space-x-2 justify-center">
                                <button onclick="editRole(${row.id})" class="text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="ph ph-pencil-simple text-lg"></i>
                                </button>
                                <button onclick="deleteRole(${row.id})" class="text-red-600 hover:text-red-800 font-medium">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
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
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                processing: "Sedang memuat data..."
            },
            dom: '<"flex flex-col sm:flex-row justify-between items-center gap-4 mb-4"lf>rt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4"ip>',
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
        });
        
        // Update stats on data load
        table.on('xhr.dt', function(e, settings, json, xhr) {
            if (json && typeof json.recordsTotal !== 'undefined') {
                // Update total roles counter
                $('#totalRolesSpinner').hide();
                $('#totalRolesText').text(json.recordsTotal);
                
                // Show/hide no data alert
                if (json.recordsTotal === 0) {
                    $('#noDataAlert').removeClass('hidden');
                } else {
                    $('#noDataAlert').addClass('hidden');
                }
            }
        });
        
        // Set total permissions immediately
        $('#totalPermissions').text({{ $permissions->count() }});
    });
    </script>
    @endpush
</x-app-layout>