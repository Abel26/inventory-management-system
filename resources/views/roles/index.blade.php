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
                    <button type="button" @click="open = !open" @click.outside="open = false" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                        <i class="ph ph-export text-lg"></i>
                        <span>{{ __('modules.common.export') }}</span>
                        <i class="ph ph-caret-down text-sm"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white shadow-xl rounded-lg border border-gray-200 z-50">
                        <div class="py-1">
                            <a href="{{ route('roles.export') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
                                <i class="ph ph-file-csv mr-2 text-green-600"></i>
                                Export Excel
                            </a>
                            <a href="{{ route('roles.export-pdf') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition border-t border-gray-100">
                                <i class="ph ph-file-pdf mr-2 text-red-600"></i>
                                Export PDF
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
                            <span id="totalRolesText" class="text-3xl font-bold text-gray-900">{{ count($roles) ?? 0 }}</span>
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
            <div class="px-8 py-6 border-b border-gray-100 bg-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                 <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('modules.roles.title') }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ __('modules.roles.subtitle') }}</p>
                </div>
                <div class="w-full md:w-72">
                     <input type="text" id="searchInput" placeholder="{{ __('modules.roles.search') }}" class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full transition-all">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="rolesTable" class="w-full" width="100%">
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
             <!-- Empty State -->
            <div id="emptyState" class="p-12 text-center flex flex-col items-center justify-center hidden">
                <i class="ph ph-magnifying-glass text-5xl mb-4 text-gray-300"></i>
                <p class="text-sm text-gray-500">{{ __('modules.roles.empty_state') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Modal -->
    <!-- Add/Edit Modal -->
    <div id="roleModal" x-data="{ showModal: false }" x-show="showModal" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 bg-gray-900/50 z-50 overflow-y-auto"
         @keydown.escape.window="showModal = false"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full mx-4 sm:max-w-4xl flex flex-col max-h-[90vh]"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.away="showModal = false">
                
                 <!-- Modal Header -->
                <div class="relative bg-gradient-to-r from-ebara-600 to-ebara-700 rounded-t-xl p-6 text-white flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="ph ph-shield-check text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold" id="modalTitle">{{ __('modules.roles.add_title') }}</h3>
                                <p class="text-ebara-100 text-sm" id="modalSubtitle">{{ __('modules.roles.add_subtitle') }}</p>
                            </div>
                        </div>
                        <button type="button" @click="showModal = false" class="text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200 p-2 rounded-lg cursor-pointer">
                            <i class="ph ph-x text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form id="roleForm" class="p-6 space-y-6 overflow-y-auto flex-1">
                    <input type="hidden" id="roleId" name="id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Role Name -->
                        <div class="space-y-2">
                             <label for="name" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-identification-card text-ebara-600 mr-2"></i>
                                {{ __('modules.roles.role_name') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" id="name" name="name" required class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400" placeholder="e.g. Administrator">
                        </div>
                        
                         <!-- Guard Name -->
                        <div class="space-y-2">
                             <label for="guard_name" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-lock-key text-ebara-600 mr-2"></i>
                                {{ __('modules.roles.guard_name') }}
                            </label>
                            <input type="text" id="guard_name" name="guard_name" value="web" readonly class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed text-sm font-medium">
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-list-checks text-ebara-600 mr-2"></i>
                                {{ __('modules.roles.permissions') }}
                            </label>
                            <div class="flex gap-2">
                                <button type="button" id="selectAllBtn" class="text-xs font-medium bg-gray-100 hover:bg-ebara-100 hover:text-ebara-600 text-gray-700 px-3 py-1.5 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer">
                                    {{ __('modules.common.select_all') }}
                                </button>
                                <button type="button" id="deselectAllBtn" class="text-xs font-medium bg-gray-100 hover:bg-red-100 hover:text-red-600 text-gray-700 px-3 py-1.5 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer">
                                    {{ __('modules.common.deselect_all') }}
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($permissionsByModule as $module => $modulePermissions)
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 h-full">
                                    <h4 class="font-bold text-gray-800 text-sm mb-3 border-b border-gray-200 pb-2 flex items-center justify-between">
                                        {{ $module }}
                                        <input type="checkbox" class="module-checkbox rounded border-gray-300 text-ebara-600 focus:ring-ebara-500 cursor-pointer" title="Select All in Module">
                                    </h4>
                                    <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                        @foreach($modulePermissions as $permission)
                                            <div class="flex items-start">
                                                <input type="checkbox" id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}" class="permission-checkbox mt-1 rounded border-gray-300 text-ebara-600 focus:ring-ebara-500 cursor-pointer">
                                                <label for="perm_{{ $permission->id }}" class="ml-2 text-sm text-gray-600 cursor-pointer select-none leading-tight hover:text-gray-900">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action Buttons -->
                     <div class="flex flex-col sm:flex-row justify-end gap-3 p-6 border-t border-gray-200 flex-shrink-0 bg-gray-50 rounded-b-xl -mx-6 -mb-6">
                        <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-all duration-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 cursor-pointer">
                            {{ __('modules.common.cancel') }}
                        </button>
                        <button type="button" id="submitRoleBtn" class="px-5 py-2.5 bg-gradient-to-r from-ebara-600 to-ebara-700 hover:from-ebara-700 hover:to-ebara-800 text-white font-medium rounded-xl shadow-lg shadow-ebara-500/25 hover:shadow-ebara-500/40 transition-all duration-200 text-sm flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-ebara-500 cursor-pointer">
                            <i class="ph ph-floppy-disk text-lg"></i>
                            {{ __('modules.common.save') }}
                        </button>
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
    var dataUrl = "{{ route('roles.data') }}";

    $(document).ready(function() {
        // Initialize DataTables
        let table = $('#rolesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: dataUrl,
            columns: [
                { data: 'name', name: 'name', className: 'px-8 py-4 text-sm font-semibold text-gray-900' },
                { data: 'guard_name', name: 'guard_name', className: 'px-8 py-4 text-sm text-gray-600' },
                {
                    data: 'permissions_count',
                    name: 'permissions_count',
                    className: 'px-8 py-4 text-sm text-center',
                    searchable: false,
                    render: function(data, type, row) {
                        const badgeColor = data > 20 ? 'bg-green-100 text-green-800' : data > 10 ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800';
                        return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${badgeColor}">${data} permissions</span>`;
                    }
                },
                {
                    data: null,
                    className: 'px-8 py-4 text-center align-middle',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                         // Prevent editing/deleting Super Admin if name matches
                        if(row.name === 'Super Admin') {
                             return `
                                <div class="flex items-center justify-center space-x-3">
                                    <span class="text-xs text-gray-400 italic">Protected</span>
                                </div>
                            `;
                        }
                        
                        return `
                            <div class="flex items-center justify-center space-x-3">
                                <button class="btn-edit-role inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500" data-role-id="${row.id}" title="{{ __('modules.common.edit') }}" style="cursor: pointer; pointer-events: auto;">
                                    <i class="ph ph-pencil-simple text-lg"></i>
                                </button>
                                <button class="btn-delete-role inline-flex items-center justify-center w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500" data-role-id="${row.id}" title="{{ __('modules.common.delete') }}" style="cursor: pointer; pointer-events: auto;">
                                    <i class="ph ph-trash text-lg"></i>
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
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            initComplete: function() {
                 let api = this.api();
                let count = api.page.info().recordsTotal;
                if (count === 0) {
                    $('#emptyState').removeClass('hidden');
                } else {
                    $('#emptyState').addClass('hidden');
                }
            }
        });

        // Search functionality
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Update stats on data load
        table.on('xhr.dt', function(e, settings, json, xhr) {
            if (json && typeof json.recordsTotal !== 'undefined') {
                $('#totalRolesSpinner').addClass('hidden');
                $('#totalRolesText').text(json.recordsTotal);
            }
        });
        
        table.on('preXhr.dt', function(e, settings, data) {
            $('#totalRolesSpinner').removeClass('hidden');
        });

        // Event Delegation for Edit/Delete buttons
        $(document).on('click', '.btn-edit-role', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const roleId = $(this).data('role-id');
            if (roleId) {
                window.editRole(roleId);
            }
        });

        $(document).on('click', '.btn-delete-role', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const roleId = $(this).data('role-id');
            if (roleId) {
                window.deleteRole(roleId);
            }
        });

        // Global Modal helpers linked to Alpine.js
        window.openRoleModal = function() {
            const modal = document.getElementById('roleModal');
            if (modal && modal._x_dataStack) {
                modal._x_dataStack[0].showModal = true;
            }
        }
        
        window.closeRoleModal = function() {
            const modal = document.getElementById('roleModal');
            if (modal && modal._x_dataStack) {
                modal._x_dataStack[0].showModal = false;
            }
        }

        // Create New Role
        $('#createNewRole').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#modalTitle').text('{{ __('modules.roles.add_title') }}');
            $('#modalSubtitle').text('{{ __('modules.roles.add_subtitle') }}');
            $('#roleForm')[0].reset();
            $('#roleId').val('');
            $('.permission-checkbox').prop('checked', false);
            $('.module-checkbox').prop('checked', false);
            window.openRoleModal();
        });

        // Permission selection helpers
        $('#selectAllBtn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.permission-checkbox').prop('checked', true);
            $('.module-checkbox').prop('checked', true);
        });

        $('#deselectAllBtn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.permission-checkbox').prop('checked', false);
            $('.module-checkbox').prop('checked', false);
        });

        $('.module-checkbox').on('change', function() {
            const isChecked = $(this).is(':checked');
            $(this).closest('.bg-gray-50').find('.permission-checkbox').prop('checked', isChecked);
        });

        // Submit Form
        $('#submitRoleBtn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // HTML5 validation — browser highlights empty required fields inline
            if (!document.getElementById('roleForm').reportValidity()) { return; }

            const id = $('#roleId').val();
            let formData = $('#roleForm').serialize();
            let url = storeUrl;
            let method = 'POST';

            if (id) {
                url = updateUrlTemplate.replace(':id', id);
                formData += '&_method=PUT';
                method = 'POST';
            }

            $('#submitRoleBtn').prop('disabled', true).html('<i class="ph ph-spinner animate-spin text-lg"></i> {{ __('modules.common.saving') }}');

            $.ajax({
                url: url,
                method: method,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#submitRoleBtn').prop('disabled', false).html('<i class="ph ph-floppy-disk text-lg"></i> {{ __('modules.common.save') }}');
                    window.closeRoleModal();
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __('modules.swal.success') }}',
                        text: response.message,
                        position: 'center',
                        confirmButtonColor: '#009B77',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    $('#submitRoleBtn').prop('disabled', false).html('<i class="ph ph-floppy-disk text-lg"></i> {{ __('modules.common.save') }}');
                    let errorMessage = '{{ __('modules.swal.server_error') }}';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        } else if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: errorMessage,
                        position: 'center',
                        allowOutsideClick: false,
                        confirmButtonColor: '#dc2626'
                    });
                }
            });
        });

        // Edit Role
        window.editRole = function(id) {
            $.ajax({
                url: showUrlTemplate.replace(':id', id),
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const data = response.data;
                        $('#modalTitle').text('{{ __('modules.roles.edit_title') }}');
                        $('#modalSubtitle').text('{{ __('modules.roles.edit_subtitle') }}');
                        $('#roleId').val(data.id);
                        $('#name').val(data.name);
                        
                        // Reset permissions
                        $('.permission-checkbox').prop('checked', false);
                        $('.module-checkbox').prop('checked', false);
                        
                        // Check permissions
                        if (data.permissions && data.permissions.length > 0) {
                            data.permissions.forEach(permId => {
                                $(`#perm_${permId}`).prop('checked', true);
                            });
                        }
                        
                        window.openRoleModal();
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '{{ __('modules.roles.fetch_error') }}',
                        position: 'center',
                        confirmButtonColor: '#dc2626'
                    });
                }
            });
        }

        // Delete Role
        window.deleteRole = function(id) {
            Swal.fire({
                title: '{{ __('modules.swal.confirm_title') }}',
                text: '{{ __('modules.swal.delete_warning') }}',
                icon: 'warning',
                position: 'center',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '{{ __('modules.swal.yes_delete') }}',
                cancelButtonText: '{{ __('modules.swal.cancel') }}',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: destroyUrlTemplate.replace(':id', id),
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('modules.swal.success') }}',
                                text: response.message,
                                position: 'center',
                                confirmButtonColor: '#009B77',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = '{{ __('modules.swal.delete_error') }}';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                                position: 'center',
                                confirmButtonColor: '#dc2626'
                            });
                        }
                    });
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>