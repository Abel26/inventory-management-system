<x-app-layout>
    <x-slot name="title">Model Aset</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Model Aset</h1>
                <p class="text-gray-600 mt-1">Kelola model dan cetakan aset</p>
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
                        <a href="{{ route('assets.models.export') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition">
                            <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                            <span class="text-sm font-medium">Ekspor ke Excel</span>
                        </a>
                        <a href="{{ route('assets.models.export-pdf') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition border-t border-gray-100">
                            <i class="ph ph-file-pdf text-lg text-red-600"></i>
                            <span class="text-sm font-medium">Ekspor ke PDF</span>
                        </a>
                    </div>
                </div>
                <button type="button" id="createNewModel" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
                    <i class="ph ph-plus text-lg"></i>
                    <span>Tambah Data</span>
                </button>
            </div>
        </div>
 
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Model</p>
                        <h5 class="text-2xl font-bold text-gray-900 mt-1" id="totalModels">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-ebara-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-cube text-2xl text-ebara-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Aktif</p>
                        <h5 class="text-2xl font-bold text-green-600 mt-1" id="activeModels">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Non-Aktif</p>
                        <h5 class="text-2xl font-bold text-yellow-600 mt-1" id="inactiveModels">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-clock text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Aset</p>
                        <h5 class="text-2xl font-bold text-gray-900 mt-1" id="totalAssets">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-package text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Controls Header -->
            <div class="p-5 border-b border-gray-100 bg-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Cari model..." class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="modelsTable" class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Kode</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Nama Model</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Kategori</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Merk</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Tipe</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Tahun</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Total Aset</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 text-center font-semibold whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Data will be loaded by DataTables -->
                </tbody>
            </table>
            
            <!-- Empty State -->
            <div id="emptyState" class="p-12 text-center flex flex-col items-center justify-center hidden">
                <i class="ph ph-magnifying-glass text-5xl mb-4 text-gray-300"></i>
                <p class="text-sm text-gray-500">Belum ada data Model Aset ditemukan.</p>
            </div>
        </div>
 
    </div>
 
    <!-- Add/Edit Modal -->
    <div id="modelModal" class="fixed inset-0 bg-gray-900/50 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full mx-4 sm:mx-auto sm:max-w-2xl flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Model</h3>
                    <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <form id="modelForm" class="p-6 space-y-4 overflow-y-auto flex-1">
                    <input type="hidden" id="modelId" name="id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Model</label>
                        <input type="text" id="name" name="name" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                    </div>
                    <x-ui.form-grid columns="2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select id="category" name="category" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                                <option value="">Pilih Kategori</option>
                                <option value="Mold">Mold</option>
                                <option value="Core">Core</option>
                                <option value="Cavity">Cavity</option>
                                <option value="Insert">Insert</option>
                                <option value="Other">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Merk</label>
                            <input type="text" id="brand" name="brand" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Contoh: Mitsubishi, Bosch">
                        </div>
                    </x-ui.form-grid>
                    <x-ui.form-grid columns="2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                            <input type="text" id="type" name="type" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Contoh: MX-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <input type="number" id="year" name="year" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Contoh: 2023">
                        </div>
                    </x-ui.form-grid>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Aset</label>
                        <input type="number" id="total_assets" name="total_assets" required min="0" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                            <option value="">Pilih Status</option>
                            <option value="Active">Aktif</option>
                            <option value="Inactive">Non-Aktif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition"></textarea>
                    </div>
                </form>
                <div class="flex flex-col sm:flex-row justify-end gap-3 p-6 border-t border-gray-200 flex-shrink-0">
                    <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">Batal</button>
                    <button type="submit" id="submitModelBtn" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg transition">Simpan</button>
                </div>
            </div>
        </div>
    </div>
 
    @push('scripts')
    <script>
    var storeUrl = "{{ route('assets.models.store') }}";
    var showUrlTemplate = "{{ route('assets.models.show', ':id') }}";
    var updateUrlTemplate = "{{ route('assets.models.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('assets.models.destroy', ':id') }}";
    var qrCodeUrlTemplate = "{{ route('assets.models.qr-code', ':id') }}";
 
    $(document).ready(function() {
        let table = $('#modelsTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{{ route('assets.models.data') }}',
            createdRow: function(row, data, dataIndex) {
                // Add group class and smooth hover effect to rows
                $(row).addClass('group hover:bg-gray-50 transition-colors duration-200');
            },
            columnDefs: [
                {
                    targets: 0, // Kode - Pill style
                    className: 'text-sm',
                    render: function(data, type, row) {
                        return '<span class="bg-gray-100 text-gray-600 py-1 px-2 rounded-md font-mono text-xs">' + data + '</span>';
                    }
                },
                {
                    targets: 1, // Nama Model (primary column)
                    className: 'text-sm font-bold text-gray-900'
                },
                {
                    targets: [2, 3, 4, 5, 6, 7], // Kategori, Merk, Tipe, Tahun, Total Aset
                    className: 'text-sm text-gray-600'
                },
                {
                    targets: 8, // Status
                    className: 'text-sm'
                },
                {
                    targets: 9, // Aksi
                    className: 'text-center'
                }
            ],
            columns: [
                { data: 'model_code' },
                { data: 'name' },
                { data: 'category' },
                { data: 'brand' },
                { data: 'type' },
                { data: 'year' },
                { data: 'total_assets' },
                { 
                    data: 'status',
                    render: function(data, type, row) {
                        let badgeClass = 'bg-green-50 text-green-700 px-2 py-1 rounded-full text-xs font-medium';
                        if (data === 'Inactive') {
                            badgeClass = 'bg-yellow-50 text-yellow-700 px-2 py-1 rounded-full text-xs font-medium';
                        }
                        return '<span class="' + badgeClass + '">' + (data === 'Active' ? 'Aktif' : 'Non-Aktif') + '</span>';
                    }
                },
                {
                    data: 'actions',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editModel(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="Edit">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteModel(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
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
 
        table.on('xhr', function() {
            let json = table.ajax.json();
            if (json) {
                $('#totalModels').text(json.stats.totalModels || 0);
                $('#activeModels').text(json.stats.activeModels || 0);
                $('#inactiveModels').text(json.stats.inactiveModels || 0);
                $('#totalAssets').text(json.stats.totalAssets || 0);
            }
        });
 
        $('#createNewModel').on('click', function() {
            $('#modalTitle').text('Tambah Model');
            $('#modelForm')[0].reset();
            $('#modelId').val('');
            $('#modelModal').removeClass('hidden');
        });
 
        $('#closeModalBtn').on('click', function() {
            $('#modelModal').addClass('hidden');
        });
 
        $('#cancelBtn').on('click', function() {
            $('#modelModal').addClass('hidden');
        });
    });
 
    function editModel(id) {
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#modalTitle').text('Edit Model');
                    $('#modelId').val(data.id);
                    $('#name').val(data.name);
                    $('#category').val(data.category);
                    $('#brand').val(data.brand);
                    $('#type').val(data.type);
                    $('#year').val(data.year);
                    $('#total_assets').val(data.total_assets);
                    $('#status').val(data.status);
                    $('#description').val(data.description);
                    $('#modelModal').removeClass('hidden');
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
                    text: 'Gagal mengambil data model',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }
 
    $('#modelForm').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();
        let id = $('#modelId').val();
        let url = storeUrl;
        let method = 'POST';
        let successMessage = 'Data berhasil disimpan';
 
        if (id) {
            url = updateUrlTemplate.replace(':id', id);
            method = 'PUT';
            successMessage = 'Data berhasil diupdate';
        }
 
        $.ajax({
            url: url,
            method: method,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#modelModal').addClass('hidden');
                $('#modelsTable').DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: successMessage,
                    confirmButtonColor: '#009B77'
                });
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
            }
        });
    });
 
    function deleteModel(id) {
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
                        $('#modelsTable').DataTable().ajax.reload();
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
    @endpush
</x-app-layout>
