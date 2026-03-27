<x-app-layout>
    <x-slot name="title">{{ __('modules.users.title') }}</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('modules.users.title') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('modules.users.subtitle') }}</p>
            </div>
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <!-- Export Dropdown -->
                <div x-data="{ open: false }" class="relative w-full md:w-auto">
                    <button @click="open = !open" @click.outside="open = false" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
                        <i class="ph ph-download-simple text-lg"></i>
                        <span>{{ __('modules.common.export') }}</span>
                        <i class="ph ph-caret-down text-sm" x-show="open" x-transition></i>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                        <a href="{{ route('users.export') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition">
                            <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                            <span class="text-sm font-medium">{{ __('modules.common.export_excel') }}</span>
                        </a>
                        <a href="{{ route('users.export-pdf') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition border-t border-gray-100">
                            <i class="ph ph-file-pdf text-lg text-red-600"></i>
                            <span class="text-sm font-medium">{{ __('modules.common.export_pdf') }}</span>
                        </a>
                    </div>
                </div>
                <button type="button" id="createNewUser" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
                    <i class="ph ph-plus text-lg"></i>
                    <span>{{ __('modules.users.add_title') }}</span>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('modules.users.total_users') }}</p>
                        <h5 class="text-xl lg:text-2xl font-bold text-gray-900 mt-1">{{ $statistics['total'] ?? 0 }}</h5>
                    </div>
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-users text-xl lg:text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('modules.users.active_users') }}</p>
                        <h5 class="text-xl lg:text-2xl font-bold text-gray-900 mt-1">{{ $statistics['active'] ?? 0 }}</h5>
                    </div>
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-user-check text-xl lg:text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('modules.users.inactive') }}</p>
                        <h5 class="text-xl lg:text-2xl font-bold text-gray-900 mt-1">{{ $statistics['inactive'] ?? 0 }}</h5>
                    </div>
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-user-minus text-xl lg:text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('modules.roles.total_roles') }}</p>
                        <h5 class="text-xl lg:text-2xl font-bold text-gray-900 mt-1">{{ $roles->count() ?? 0 }}</h5>
                    </div>
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-shield-check text-xl lg:text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Controls Header -->
            <div class="p-5 border-b border-gray-100 bg-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="{{ __('modules.users.search_placeholder') }}" class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="usersTable" class="w-full" width="100%">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap w-1/3">{{ __('modules.users.user_info') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.role') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.users.phone') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.status') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.created_at') }}</th>
                        <th class="px-6 py-4 text-center font-semibold whitespace-nowrap">{{ __('modules.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Data will be loaded by DataTables -->
                </tbody>
            </table>
            
            <!-- Empty State -->
            <div id="emptyState" class="p-12 text-center flex flex-col items-center justify-center hidden">
                <i class="ph ph-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg font-medium">{{ __('modules.users.empty_state') }}</p>
                <p class="text-gray-400 text-sm mt-1">{{ __('modules.users.empty_state_desc') }}</p>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="userModal" x-data="{ showModal: false }" x-show="showModal" x-cloak
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
            <div class="relative bg-white rounded-xl shadow-2xl w-full mx-4 sm:max-w-2xl flex flex-col max-h-[90vh]"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.away="showModal = false">
                <!-- Header -->
                <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-ebara-100 rounded-lg flex items-center justify-center">
                            <i class="ph ph-user-circle-plus text-ebara-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">{{ __('modules.users.add_title') }}</h3>
                            <p class="text-sm text-gray-500" id="modalSubtitle">{{ __('modules.users.add_subtitle') }}</p>
                        </div>
                    </div>
                    <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100" tabindex="0" role="button" aria-label="{{ __('modules.common.close_modal') }}">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="userForm" x-data="{ form: { role: '' } }" class="p-6 overflow-y-auto flex-1" action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="userId" name="id">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('modules.common.name') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-user text-gray-400 text-lg"></i>
                                    </div>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           required
                                           value="{{ old('name') }}"
                                           placeholder="{{ __('modules.users.name_placeholder') }}"
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors">
                                </div>
                                @error('name')
                                    <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                        <i class="ph ph-warning-circle text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <!-- Username Field -->
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">{{ __('modules.users.username') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-at text-gray-400 text-lg"></i>
                                    </div>
                                    <input type="text"
                                           id="username"
                                           name="username"
                                           required
                                           value="{{ old('username') }}"
                                           placeholder="{{ __('modules.users.username_placeholder') }}"
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors">
                                </div>
                                @error('username')
                                    <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                        <i class="ph ph-warning-circle text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Phone Field -->
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">{{ __('modules.users.phone') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-phone text-gray-400 text-lg"></i>
                                    </div>
                                    <input type="text"
                                           id="phone_number"
                                           name="phone_number"
                                           value="{{ old('phone_number') }}"
                                           placeholder="{{ __('modules.users.phone_placeholder') }}"
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors">
                                </div>
                                @error('phone_number')
                                    <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                        <i class="ph ph-warning-circle text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <!-- Role Field -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">{{ __('modules.common.role') }}</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-shield-check text-gray-400 text-lg"></i>
                                    </div>
                                    <select
                                        id="role"
                                        name="role"
                                        x-model="form.role"
                                        @change="$el.dispatchEvent(new Event('input', { bubbles: true }))"
                                        class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors"
                                    >
                                        <option value="" disabled>{{ __('modules.users.select_role') }}</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('role')
                                    <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                        <i class="ph ph-warning-circle text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Password Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="passwordFields">
                            <!-- Current Password Info (Edit Mode Only) -->
                            <div class="md:col-span-2 hidden" id="currentPasswordInfo">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                    <div class="flex items-start gap-2">
                                        <i class="ph ph-info text-blue-600 mt-0.5"></i>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-blue-900">{{ __('modules.users.user_info') }}</p>
                                            <p class="text-xs text-blue-700 mt-1">{{ __('modules.users.password_info') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Password Field -->
                            <div x-data="{ show: false }">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('modules.common.password') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-lock text-gray-400 text-lg"></i>
                                    </div>
                                    <input type="password"
                                           id="password"
                                           name="password"
                                           value="{{ old('password') }}"
                                           :type="show ? 'text' : 'password'"
                                           placeholder="{{ __('modules.users.password_placeholder') }}"
                                           class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <i class="ph text-lg" :class="show ? 'ph-eye-slash' : 'ph-eye'"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                        <i class="ph ph-warning-circle text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <!-- Password Confirmation Field -->
                            <div x-data="{ show: false }">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ __('modules.users.confirm_password') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-lock text-gray-400 text-lg"></i>
                                    </div>
                                    <input type="password"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           value="{{ old('password_confirmation') }}"
                                           :type="show ? 'text' : 'password'"
                                           placeholder="{{ __('modules.users.password_conf_placeholder') }}"
                                           class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors">
                                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <i class="ph text-lg" :class="show ? 'ph-eye-slash' : 'ph-eye'"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                        <i class="ph ph-warning-circle text-xs"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Active Status -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active') ? 'checked' : '' }}
                                       class="h-4 w-4 text-ebara-600 focus:ring-2 focus:ring-ebara-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-3 block text-sm font-medium text-gray-900 cursor-pointer">{{ __('modules.users.is_active') }}</label>
                            </div>
                            <div class="text-xs text-gray-500">
                                <i class="ph ph-info"></i>
                                {{ __('modules.users.active_help') }}
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Footer -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 p-6 border-t border-gray-200 flex-shrink-0">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        {{ __('modules.common.cancel') }}
                    </button>
                    <button type="button" id="submitUserBtn" class="px-4 py-2 bg-ebara-600 text-white rounded-lg hover:bg-ebara-700 transition-colors font-medium flex items-center gap-2">
                        <i class="ph ph-floppy-disk"></i>
                        <span>{{ __('modules.common.save') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    var storeUrl = "{{ route('users.store') }}";
    var showUrlTemplate = "{{ route('users.show', ':id') }}";
    var updateUrlTemplate = "{{ route('users.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('users.destroy', ':id') }}";

    $(document).ready(function() {
        let table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{{ route('users.data') }}',
            createdRow: function(row, data, dataIndex) {
                // Add group class and smooth hover effect to rows
                $(row).addClass('group hover:bg-gray-50 transition-colors duration-200');
            },
            columnDefs: [
                {
                    targets: 0, // User Info - Custom rendering
                    className: 'text-sm',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-ebara-100 flex items-center justify-center text-ebara-600 font-bold">
                                        ${row.name.charAt(0)}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">${row.name}</div>
                                    <div class="text-xs text-gray-500">
                                        <span class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600 font-mono">
                                            @${row.username}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    targets: 1, // Role
                    className: 'text-sm',
                    render: function(data, type, row) {
                        let rolesHtml = '';
                        if (row.roles && row.roles.length > 0) {
                            row.roles.forEach(role => {
                                rolesHtml += `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mr-1">${role.name}</span>`;
                            });
                        }
                        return rolesHtml || '-';
                    }
                },
                {
                    targets: [2, 4], // Phone, Created At
                    className: 'text-sm text-gray-600'
                },
                {
                    targets: 3, // Status
                    className: 'text-sm',
                    render: function(data, type, row) {
                        if (row.is_active) {
                            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('modules.users.active') }}</span>';
                        } else {
                            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ __('modules.users.inactive') }}</span>';
                        }
                    }
                },
                {
                    targets: 5, // Aksi
                    className: 'text-center'
                }
            ],
            columns: [
                { data: 'name' },
                { data: 'roles' },
                { data: 'phone_number' },
                { data: 'is_active' },
                { 
                    data: 'created_at',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleDateString('id-ID', { 
                            day: 'numeric', 
                            month: 'short', 
                            year: 'numeric' 
                        });
                    }
                },
                {
                    data: 'actions',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editUser(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="{{ __('modules.common.edit') }}">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteUser(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="{{ __('modules.common.delete') }}">
                                    <i class="ph ph-trash text-xl"></i>
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
                    infoFiltered: "{{ __('modules.datatable.info_filtered') }}",
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
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            initComplete: function() {
                // Show/hide empty state based on data count
                let api = this.api();
                let count = api.page.info().recordsTotal;
                if (count === 0) {
                    $('#emptyState').removeClass('hidden');
                } else {
                    $('#emptyState').addClass('hidden');
                }
            }
        });

    // Global Modal helpers
    window.openUserModal = function() {
        const modal = document.getElementById('userModal');
        if (modal && modal._x_dataStack) {
            modal._x_dataStack[0].showModal = true;
        }
    }
    
    window.closeUserModal = function() {
        const modal = document.getElementById('userModal');
        if (modal && modal._x_dataStack) {
            modal._x_dataStack[0].showModal = false;
        }
    }


        // Create button handler
        $('#createNewUser').on('click', function() {
            $('#modalTitle').text('{{ __('modules.users.add_title') }}');
            $('#modalSubtitle').text('{{ __('modules.users.add_subtitle') }}');
            $('#userForm')[0].reset();
            $('#userId').val('');
            // Remove required from password fields when creating
            $('#password').prop('required', true);
            $('#password_confirmation').prop('required', true);
            // Show password fields
            $('#passwordFields').show();
            // Hide current password info (only for edit)
            $('#currentPasswordInfo').addClass('hidden');
            // Reset placeholders for create mode
            $('#password').attr('placeholder', '{{ __('modules.users.password_placeholder') }}');
            $('#password_confirmation').attr('placeholder', '{{ __('modules.users.password_conf_placeholder') }}');
            // Set form action for create
            $('#userForm').attr('action', storeUrl);
            $('#_method').val('POST');
            // Clear role selection and Alpine.js data
            $('#role').val('');
            const formElement = document.getElementById('userForm');
            if (formElement && formElement._x_dataStack) {
                formElement._x_dataStack[0].form.role = '';
            }
            openUserModal();
        });

        // Close button handlers (redundant since using Alpine.js @click)
        // Keeping for compatibility with existing code
        $('#closeModalBtn').on('click', function() {
            closeUserModal();
        });

        $('#cancelBtn').on('click', function() {
            closeUserModal();
        });

        // Submit button handler
        $('#submitUserBtn').on('click', function(e) {
            e.preventDefault();
            
            // Fix aria-hidden conflict by removing focus before SweetAlert
            $(this).blur();
            
            let id = $('#userId').val();
            let isEdit = id !== '';
            
            if (isEdit) {
                // Show confirmation dialog for edit
                let confirmTitle = '{{ __('modules.swal.confirm_title') }}';
                let confirmText = '{{ __('modules.swal.update_warning') }}';
                
                // Store reference to button for later focus restoration
                var submitBtn = this;
                
                Swal.fire({
                    title: confirmTitle,
                    text: confirmText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#009B77',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '{{ __('modules.swal.yes_save') }}',
                    cancelButtonText: '{{ __('modules.swal.cancel') }}',
                    // Fix for production timing issues
                    didOpen: function() {
                        // Ensure proper focus management in SweetAlert
                        // Remove any aria-hidden conflicts
                        $('.flex.h-screen').removeAttr('aria-hidden');
                    },
                    didClose: function() {
                        // Restore focus after SweetAlert closes
                        setTimeout(function() {
                            if (submitBtn && $(submitBtn).is(':visible')) {
                                submitBtn.focus();
                            }
                        }, 100);
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Trigger form submission manually instead of using submit()
                        $('#userForm').trigger('submit');
                    }
                });
            } else {
                // Direct submit for new user
                $('#userForm').trigger('submit');
            }
        });

        // Form submission handler
        $('#userForm').on('submit', function(e) {
            e.preventDefault();

            // HTML5 validation — browser highlights empty required fields inline
            if (!this.reportValidity()) { return; }

            try {
                let formData = $(this).serialize();
            let id = $('#userId').val();
            let url = storeUrl;
            let method = 'POST';
            let successMessage = '{{ __('modules.swal.data_saved') }}';

            if (id) {
                url = updateUrlTemplate.replace(':id', id);
                // Update form action and method for edit
                $('#userForm').attr('action', url);
                $('#_method').val('PUT');
                method = 'POST'; // Always use POST with _method field
                successMessage = '{{ __('modules.swal.data_updated') }}';
            } else {
                // Reset form action and method for create
                $('#userForm').attr('action', storeUrl);
                $('#_method').val('POST');
            }

            // Disable submit button to prevent double submission
            $('#submitUserBtn').prop('disabled', true).text('{{ __('modules.common.saving') }}...');

            $.ajax({
                url: url,
                method: method,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    closeUserModal();
                    
                    // Reload DataTable with a small delay to ensure server has processed update
                    setTimeout(function() {
                        $('#usersTable').DataTable().ajax.reload(null, false); // false = keep current page
                    }, 500);
                    
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __('modules.swal.success') }}',
                        text: successMessage,
                        confirmButtonColor: '#009B77'
                    });
                    
                    // Reset form and re-enable button
                    $('#userForm')[0].reset();
                    // Hide current password info after reset
                    $('#currentPasswordInfo').addClass('hidden');
                    // Reset placeholders to default
                    $('#password').attr('placeholder', '{{ __('modules.users.password_placeholder') }}');
                    $('#password_confirmation').attr('placeholder', '{{ __('modules.users.password_conf_placeholder') }}');
                    // Clear Alpine.js form data
                    const formElement = document.getElementById('userForm');
                    if (formElement && formElement._x_dataStack) {
                        formElement._x_dataStack[0].form.role = '';
                    }
                    $('#submitUserBtn').prop('disabled', false).text('{{ __('modules.common.save') }}');
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    for (let key in errors) {
                        errorMessage += errors[key][0] + '\n';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#dc2626'
                    });
                    
                    // Re-enable button on error
                    $('#submitUserBtn').prop('disabled', false).text('{{ __('modules.common.save') }}');
                }
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan sistem: ' + error.message,
                    confirmButtonColor: '#dc2626'
                });
                // Re-enable button on error
                $('#submitUserBtn').prop('disabled', false).html('<i class="ph ph-floppy-disk text-lg"></i> {{ __('modules.common.save') }}');
            }
        });
    });

    function editUser(id) {
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#modalTitle').text('{{ __('modules.users.edit_title') }}');
                    $('#userId').val(data.id);
                    $('#name').val(data.name);
                    $('#username').val(data.username);
                    $('#phone_number').val(data.phone_number);
                    
                    // Set role
                    $('#role').val(''); // Clear selection first
                    if (data.roles && data.roles.length > 0) {
                        // Use the first role name for single select
                        const roleName = data.roles[0].name;
                        $('#role').val(roleName);
                        // Update Alpine.js form data
                        const formElement = document.getElementById('userForm');
                        if (formElement && formElement._x_dataStack) {
                            formElement._x_dataStack[0].form.role = roleName;
                        }
                    }
                    
                    // Add visual feedback for selected role
                    setTimeout(function() {
                        $('#role').focus();
                    }, 100);
                    
                    $('#is_active').prop('checked', data.is_active);
                    
                    // Show password fields for edit with current password
                    $('#passwordFields').show();
                    // Show current password info for edit mode
                    $('#currentPasswordInfo').removeClass('hidden');
                    // Update placeholders for edit mode
                    $('#password').attr('placeholder', '{{ __('modules.users.password_placeholder') }}');
                    $('#password_confirmation').attr('placeholder', '{{ __('modules.users.password_conf_placeholder') }}');
                    
                    // Set form action and method for edit
                    $('#userForm').attr('action', updateUrlTemplate.replace(':id', data.id));
                    $('#_method').val('PUT');
                    $('#modalTitle').text('{{ __('modules.users.edit_title') }}');
                    $('#modalSubtitle').text('{{ __('modules.users.edit_subtitle') }}');
                    
                    openUserModal();
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
                    text: '{{ __('modules.users.fetch_error') }}',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    function deleteUser(id) {
        Swal.fire({
            title: '{{ __('modules.swal.confirm_title') }}',
            text: '{{ __('modules.swal.delete_warning') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#009B77',
            confirmButtonText: '{{ __('modules.swal.yes_delete') }}',
            cancelButtonText: '{{ __('modules.swal.cancel') }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: destroyUrlTemplate.replace(':id', id),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#usersTable').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __('modules.swal.success') }}',
                            text: '{{ __('modules.swal.data_deleted') }}',
                            confirmButtonColor: '#009B77'
                        });
                    }
                });
            }
        });
    }
    </script>
    
    <style>
        /* Single select styling */
        #role {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        #role:focus {
            outline: none;
            border-color: #009B77;
            box-shadow: 0 0 0 3px rgba(0, 155, 119, 0.1);
        }
        
        /* Hide default dropdown arrow */
        #role::-ms-expand {
            display: none;
        }
    </style>
    @endpush
</x-app-layout>