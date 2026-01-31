<x-app-layout>
    <x-slot name="title">Manajemen User</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen User</h1>
                <p class="text-gray-600 mt-1">Kelola pengguna dan hak akses sistem</p>
            </div>
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <!-- Export Dropdown -->
                <div x-data="{ open: false }" class="relative w-full md:w-auto">
                    <button @click="open = !open" @click.outside="open = false" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
                        <i class="ph ph-download-simple text-lg"></i>
                        <span>Ekspor</span>
                        <i class="ph ph-caret-down text-sm" x-show="open" x-transition></i>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                        <a href="{{ route('users.export') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition">
                            <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                            <span class="text-sm font-medium">Ekspor ke Excel</span>
                        </a>
                        <a href="{{ route('users.export-pdf') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition border-t border-gray-100">
                            <i class="ph ph-file-pdf text-lg text-red-600"></i>
                            <span class="text-sm font-medium">Ekspor ke PDF</span>
                        </a>
                    </div>
                </div>
                <button type="button" id="createNewUser" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
                    <i class="ph ph-plus text-lg"></i>
                    <span>Tambah User</span>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total User</p>
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
                        <p class="text-sm text-gray-500">User Aktif</p>
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
                        <p class="text-sm text-gray-500">User Tidak Aktif</p>
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
                        <p class="text-sm text-gray-500">Total Role</p>
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
                    <input type="text" id="searchInput" placeholder="Cari user..." class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="usersTable" class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap w-1/3">User Info</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Role</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Phone</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Created At</th>
                        <th class="px-6 py-4 text-center font-semibold whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Data will be loaded by DataTables -->
                </tbody>
            </table>
            
            <!-- Empty State -->
            <div id="emptyState" class="p-12 text-center flex flex-col items-center justify-center hidden">
                <i class="ph ph-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg font-medium">Data tidak ditemukan</p>
                <p class="text-gray-400 text-sm mt-1">Mulai dengan menambahkan user pertama</p>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="userModal" class="fixed inset-0 bg-gray-900/50 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full mx-4 sm:mx-auto sm:max-w-2xl flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah User</h3>
                    <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <form id="userForm" x-data="{ form: { role: '' } }" class="p-6 space-y-6 overflow-y-auto flex-1" action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="userId" name="id">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name Field -->
                        <div class="space-y-1">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ph ph-user text-gray-400 text-lg"></i>
                                </div>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       required
                                       value="{{ old('name') }}"
                                       placeholder="Masukkan nama lengkap"
                                       class="block w-full pl-10 pr-3 py-2.5 sm:text-sm border-gray-300 rounded-xl focus:ring-ebara-500 focus:border-ebara-500 shadow-sm transition-colors">
                            </div>
                            @error('name')
                                <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                    <i class="ph ph-warning-circle text-xs"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Username Field -->
                        <div class="space-y-1">
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ph ph-at text-gray-400 text-lg"></i>
                                </div>
                                <input type="text"
                                       id="username"
                                       name="username"
                                       required
                                       value="{{ old('username') }}"
                                       placeholder="Masukkan username"
                                       class="block w-full pl-10 pr-3 py-2.5 sm:text-sm border-gray-300 rounded-xl focus:ring-ebara-500 focus:border-ebara-500 shadow-sm transition-colors">
                            </div>
                            @error('username')
                                <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                    <i class="ph ph-warning-circle text-xs"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone Field -->
                        <div class="space-y-1">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ph ph-phone text-gray-400 text-lg"></i>
                                </div>
                                <input type="text"
                                       id="phone_number"
                                       name="phone_number"
                                       value="{{ old('phone_number') }}"
                                       placeholder="Masukkan nomor telepon"
                                       class="block w-full pl-10 pr-3 py-2.5 sm:text-sm border-gray-300 rounded-xl focus:ring-ebara-500 focus:border-ebara-500 shadow-sm transition-colors">
                            </div>
                            @error('phone_number')
                                <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                    <i class="ph ph-warning-circle text-xs"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        
                        <!-- Role Field -->
                        <div class="space-y-1">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="ph ph-shield-check text-gray-400 text-lg"></i>
                                </div>
                                <select
                                    id="role"
                                    name="role"
                                    x-model="form.role"
                                    @change="$el.dispatchEvent(new Event('input', { bubbles: true }))"
                                    class="block w-full pl-10 pr-10 py-2.5 sm:text-sm border-gray-300 rounded-xl focus:ring-ebara-500 focus:border-ebara-500 shadow-sm transition-colors"
                                >
                                    <option value="" disabled>Pilih Role</option>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="passwordFields">
                        <!-- Current Password Info (Edit Mode Only) -->
                        <div class="md:col-span-2 hidden" id="currentPasswordInfo">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex items-start gap-2">
                                    <i class="ph ph-info text-blue-600 mt-0.5"></i>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-blue-900">Informasi Password</p>
                                        <p class="text-xs text-blue-700 mt-1">Kosongkan field password jika tidak ingin mengubah password saat ini.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Password Field -->
                        <div x-data="{ show: false }" class="space-y-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password
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
                                       placeholder="Masukkan password baru"
                                       class="block w-full pl-10 pr-10 py-2.5 sm:text-sm border-gray-300 rounded-xl focus:ring-ebara-500 focus:border-ebara-500 shadow-sm transition-colors">
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
                        <div x-data="{ show: false }" class="space-y-1">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Konfirmasi Password
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
                                       placeholder="Konfirmasi password baru"
                                       class="block w-full pl-10 pr-10 py-2.5 sm:text-sm border-gray-300 rounded-xl focus:ring-ebara-500 focus:border-ebara-500 shadow-sm transition-colors">
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
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active') ? 'checked' : '' }}
                                   class="h-5 w-5 text-ebara-600 focus:ring-2 focus:ring-ebara-500/20 border-gray-300 rounded transition-all duration-200">
                            <label for="is_active" class="ml-3 block text-sm font-medium text-gray-900 cursor-pointer">User Aktif</label>
                        </div>
                        <div class="text-xs text-gray-500">
                            <i class="ph ph-info"></i>
                            Aktifkan untuk memberikan akses login
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2.5 px-4 rounded-xl transition">Batal</button>
                        <button type="button" id="submitUserBtn" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl transition">Simpan</button>
                    </div>
                </form>
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
                            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>';
                        } else {
                            return '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>';
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
                                <button onclick="editUser(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="Edit">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteUser(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
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
                infoFiltered: "(difilter dari _MAX_ total data)",
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

        // Create button handler
        $('#createNewUser').on('click', function() {
            $('#modalTitle').text('Tambah User');
            $('#userForm')[0].reset();
            $('#userId').val('');
            $('#passwordFields').show();
            // Hide current password info for create mode
            $('#currentPasswordInfo').addClass('hidden');
            // Reset placeholders for create mode
            $('#password').attr('placeholder', 'Masukkan password');
            $('#password_confirmation').attr('placeholder', 'Masukkan ulang password');
            // Reset form action and method for create
            $('#userForm').attr('action', storeUrl);
            $('#_method').val('POST');
            // Clear role selection and Alpine.js data
            $('#role').val('');
            const formElement = document.getElementById('userForm');
            if (formElement && formElement._x_dataStack) {
                formElement._x_dataStack[0].form.role = '';
            }
            $('#userModal').removeClass('hidden');
        });

        // Close button handlers
        $('#closeModalBtn').on('click', function() {
            $('#userModal').addClass('hidden');
        });

        $('#cancelBtn').on('click', function() {
            $('#userModal').addClass('hidden');
        });

        // Close modal when clicking outside
        $('#userModal').on('click', function(e) {
            if (e.target === this) {
                $('#userModal').addClass('hidden');
            }
        });

        // Submit button handler
        $('#submitUserBtn').on('click', function(e) {
            e.preventDefault();
            
            let id = $('#userId').val();
            let isEdit = id !== '';
            
            if (isEdit) {
                // Show confirmation dialog for edit
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data user akan diperbarui. Pastikan data sudah benar.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#009B77',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Simpan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#userForm').submit();
                    }
                });
            } else {
                // Direct submit for new user
                $('#userForm').submit();
            }
        });

        // Form submission handler
        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            
            let formData = $(this).serialize();
            let id = $('#userId').val();
            let url = storeUrl;
            let method = 'POST';
            let successMessage = 'Data berhasil disimpan';

            if (id) {
                url = updateUrlTemplate.replace(':id', id);
                // Update form action and method for edit
                $('#userForm').attr('action', url);
                $('#_method').val('PUT');
                method = 'POST'; // Always use POST with _method field
                successMessage = 'Data berhasil diupdate';
            } else {
                // Reset form action and method for create
                $('#userForm').attr('action', storeUrl);
                $('#_method').val('POST');
            }

            // Disable submit button to prevent double submission
            $('#submitUserBtn').prop('disabled', true).text('Menyimpan...');

            $.ajax({
                url: url,
                method: method,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#userModal').addClass('hidden');
                    
                    // Reload DataTable with a small delay to ensure server has processed update
                    setTimeout(function() {
                        $('#usersTable').DataTable().ajax.reload(null, false); // false = keep current page
                    }, 500);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: successMessage,
                        confirmButtonColor: '#009B77'
                    });
                    
                    // Reset form and re-enable button
                    $('#userForm')[0].reset();
                    // Hide current password info after reset
                    $('#currentPasswordInfo').addClass('hidden');
                    // Reset placeholders to default
                    $('#password').attr('placeholder', 'Masukkan password');
                    $('#password_confirmation').attr('placeholder', 'Masukkan ulang password');
                    // Clear Alpine.js form data
                    const formElement = document.getElementById('userForm');
                    if (formElement && formElement._x_dataStack) {
                        formElement._x_dataStack[0].form.role = '';
                    }
                    $('#submitUserBtn').prop('disabled', false).text('Simpan');
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
                    $('#submitUserBtn').prop('disabled', false).text('Simpan');
                }
            });
        });
    });

    function editUser(id) {
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#modalTitle').text('Edit User');
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
                    $('#password').attr('placeholder', 'Masukkan password baru');
                    $('#password_confirmation').attr('placeholder', 'Konfirmasi password baru');
                    
                    // Set form action and method for edit
                    $('#userForm').attr('action', updateUrlTemplate.replace(':id', data.id));
                    $('#_method').val('PUT');
                    
                    $('#userModal').removeClass('hidden');
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
                    text: 'Gagal mengambil data user',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    function deleteUser(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data yang dihapus tidak dapat dikembalikan',
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
                        $('#usersTable').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil dihapus',
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