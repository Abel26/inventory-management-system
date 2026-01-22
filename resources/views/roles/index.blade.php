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
                        <i class="ph ph-download-simple text-lg"></i>
                        <span>Ekspor</span>
                        <i class="ph ph-caret-down text-sm" x-show="open" x-transition></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white shadow-xl rounded-lg border border-gray-200 z-50">
                        <div class="py-1">
                            <a href="{{ route('roles.export') }}" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                                <span>Export Excel</span>
                            </a>
                            <a href="{{ route('roles.export-pdf') }}" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2 border-t border-gray-100">
                                <i class="ph ph-file-pdf text-lg text-red-600"></i>
                                <span>Export PDF</span>
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
                        <p class="text-sm text-gray-500">Total Role</p>
                        <h5 class="text-2xl font-bold text-gray-900 mt-1" id="totalRoles">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-ebara-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-users text-2xl text-ebara-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Permission</p>
                        <h5 class="text-2xl font-bold text-gray-900 mt-1" id="totalPermissions">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-shield-check text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Table Card -->
        <x-ui.data-table-wrapper tableId="rolesTable" minWidth="700px">
            <table id="rolesTable" class="w-full">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <th class="px-4 py-3 text-left font-semibold">Nama Role</th>
                        <th class="px-4 py-3 text-left font-semibold">Guard</th>
                        <th class="px-4 py-3 text-left font-semibold">Jumlah Permission</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded by DataTables -->
                </tbody>
            </table>
        </x-ui.data-table-wrapper>
    </div>
    
    <!-- Add/Edit Modal -->
    <div id="roleModal" class="fixed inset-0 bg-gray-900/50 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full mx-4 sm:mx-auto sm:max-w-4xl flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Role</h3>
                    <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <form id="roleForm" action="{{ route('roles.store') }}" method="POST" class="flex-1 flex flex-col overflow-hidden">
                    @csrf
                    <input type="hidden" id="roleId" name="id">
                    <div class="p-6 space-y-4 flex-shrink-0">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Role</label>
                            <input type="text" id="name" name="name" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Guard</label>
                            <select id="guard_name" name="guard_name" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                                <option value="web">Web</option>
                                <option value="api">API</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto px-6 pb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="permissionsContainer">
                            <!-- Permissions will be loaded dynamically -->
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-3 p-6 border-t border-gray-200 flex-shrink-0">
                        <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">Batal</button>
                        <button type="submit" id="submitRoleBtn" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg transition">Simpan</button>
                    </div>
                </form>
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
    var roles = @json($roles);
    
    $(document).ready(function() {
        let table = $('#rolesTable').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            data: roles,
            columns: [
                { data: 'name' },
                { data: 'guard_name' },
                { 
                    data: 'permissions_count',
                    render: function(data, type, row) {
                        return '<span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">' + data + '</span>';
                    }
                },
                {
                    data: 'actions',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editRole(${row.id})" class="text-indigo-600 hover:text-indigo-800 transition" title="Edit">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteRole(${row.id})" class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                    <i class="ph ph-trash text-xl"></i>
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
        
        // Update stats
        $('#totalRoles').text(table.rows().count());
        $('#totalPermissions').text({{ $permissions->count() }});
        $('#superAdminCount').text(roles.filter(r => r.name === 'Super Admin').length);
        
        $('#createNewRole').on('click', function() {
            handleAjaxForm('#roleForm', '#rolesTable', '#roleModal');
        });
        
        $('#closeModalBtn').on('click', function() {
            $('#roleModal').addClass('hidden');
        });
        
        $('#cancelBtn').on('click', function() {
            $('#roleModal').addClass('hidden');
        });
        
        // Load permissions grouped by module
        $.ajax({
            url: getPermissionsByModuleUrl,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    renderPermissionsByModule(response.data);
                }
            }
        });
    });
    
    function renderPermissionsByModule(data) {
        var container = $('#permissionsContainer');
        container.empty();
        
        var modules = Object.keys(data);
        
        modules.forEach(function(module) {
            var modulePermissions = data[module] || [];
            
            if (modulePermissions.length > 0) {
                var moduleHtml = `
                    <div class="module-card border border-gray-200 rounded-lg overflow-hidden flex flex-col">
                        <div class="bg-gray-50 px-3 py-2 border-b border-gray-200 flex-shrink-0">
                            <div class="flex items-center justify-between">
                                <h4 class="font-semibold text-gray-900 text-sm">${module}</h4>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" id="selectAll_${module}" class="w-4 h-4 text-ebara-600 rounded" onchange="toggleModulePermissions('${module}')">
                                    <span class="text-sm text-gray-600">Pilih Semua</span>
                                </label>
                            </div>
                        </div>
                        <div class="p-3 bg-white flex-1 overflow-y-auto">
                            <div class="grid grid-cols-2 gap-2">
                `;
                
                modulePermissions.forEach(function(perm) {
                    moduleHtml += `
                        <div class="permission-item">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-ebara-600 rounded" value="${perm.id}" name="permissions[]" onchange="updatePermissionCount()">
                                <span class="text-sm text-gray-700">${perm.name}</span>
                            </label>
                        </div>
                    `;
                });
                
                moduleHtml += `
                            </div>
                        </div>
                    </div>
                `;
                
                container.append(moduleHtml);
            }
        });
    }
    
    function toggleModulePermissions(module) {
        var checkbox = document.getElementById('selectAll_' + module);
        var moduleContainer = checkbox.closest('.module-card');
        var checkboxes = moduleContainer.querySelectorAll('input[name="permissions[]"]');
        
        checkboxes.forEach(function(cb) {
            cb.checked = checkbox.checked;
        });
    }
    
    function updatePermissionCount() {
        var checkedCount = document.querySelectorAll('input[name="permissions[]"]:checked').length;
        $('#permissionsCount').text(checkedCount);
    }
    
    function editRole(id) {
        handleAjaxForm('#roleForm', '#rolesTable', '#roleModal', 'Edit Role', 'Apakah Anda yakin ingin mengedit role ini?');
    }
    
    function deleteRole(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Role yang dihapus tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#009B77',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: destroyUrlTemplate.replace(':id', id),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.rows().invalidate().draw();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Role berhasil dihapus',
                            confirmButtonColor: '#009B77'
                        });
                    }
                });
            }
        });
    }
    </script>
    @endpush
</x-app-layout>
