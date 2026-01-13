<x-app-layout>
    <x-slot name="title">Manajemen Role</x-slot>
    
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
            width: 200px;
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
        .dataTables_wrapper .dataTables_length {
            float: left;
            text-align: left;
        }
        
        .dataTables_wrapper .dataTables_filter {
            float: right;
            text-align: right;
        }
        
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            font-weight: 500;
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        /* Clear floats for pagination */
        .dataTables_wrapper:after {
            content: "";
            display: table;
            clear: both;
        }
        
        /* Permission Matrix Styles */
        .permission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .permission-item {
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background-color: white;
        }
        
        .permission-item:hover {
            border-color: #009B77;
        }
        
        .module-header {
            background-color: #f8fafc;
            border-bottom: 2px solid #e5e7eb;
            padding: 1rem;
            margin-bottom: 1rem;
        }
    </style>
    @endpush
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Role</h1>
                <p class="text-gray-600 mt-1">Kelola peran dan hak akses pengguna</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('roles.export') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                    <i class="ph ph-file-excel text-lg"></i>
                    <span>Export Excel</span>
                </a>
                <button type="button" id="createNewRole" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                    <i class="ph ph-plus text-lg"></i>
                    <span>Tambah Role</span>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Super Admin</p>
                        <h5 class="text-2xl font-bold text-gray-900 mt-1" id="superAdminCount">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-crown text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-100 p-6 relative overflow-hidden">
            <div class="overflow-x-auto">
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
            </div>
        </div>

    </div>

    <!-- Add/Edit Modal -->
    <div id="roleModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-4">
            <div class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full mx-4 flex flex-col max-h-[80vh]">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Role</h3>
                    <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <form id="roleForm" class="flex-1 flex flex-col overflow-hidden">
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
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 flex-shrink-0 px-6">
                        <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">Batal</button>
                        <button type="submit" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg transition">Simpan</button>
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
            $('#modalTitle').text('Tambah Role');
            $('#roleForm')[0].reset();
            $('#roleId').val('');
            $('#permissionsContainer').empty();
            
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
            
            $('#roleModal').removeClass('hidden');
        });

        $('#closeModalBtn').on('click', function() {
            $('#roleModal').addClass('hidden');
        });

        $('#cancelBtn').on('click', function() {
            $('#roleModal').addClass('hidden');
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
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#modalTitle').text('Edit Role');
                    $('#roleId').val(data.id);
                    $('#name').val(data.name);
                    $('#guard_name').val(data.guard_name);
                    
                    // Load permissions
                    $.ajax({
                        url: getPermissionsByModuleUrl,
                        method: 'GET',
                        success: function(permResponse) {
                            if (permResponse.success) {
                                renderPermissionsByModule(permResponse.data);
                                
                                // Check existing permissions
                                if (data.permissions) {
                                    data.permissions.forEach(function(perm) {
                                        var checkbox = document.querySelector(`input[name="permissions[]"][value="${perm.id}"]`);
                                        if (checkbox) {
                                            checkbox.checked = true;
                                        }
                                    });
                                }
                            }
                        }
                    });
                    
                    $('#roleModal').removeClass('hidden');
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
                    text: 'Gagal mengambil data role',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    $('#roleForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var id = $('#roleId').val();
        var url = storeUrl;
        var method = 'POST';
        var successMessage = 'Role berhasil disimpan';

        if (id) {
            url = updateUrlTemplate.replace(':id', id);
            method = 'PUT';
            successMessage = 'Role berhasil diperbarui';
        }

        $.ajax({
            url: url,
            method: method,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#roleModal').addClass('hidden');
                table.rows().invalidate().draw();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: successMessage,
                    confirmButtonColor: '#009B77'
                });
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '';
                for (var key in errors) {
                    errorMessage += errors[key][0] + '\n';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    });

    function deleteRole(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Role yang dihapus tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#009B77',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
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
