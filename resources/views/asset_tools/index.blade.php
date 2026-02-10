<x-app-layout>
    <x-slot name="title">Alat Aset</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Alat Aset</h1>
                <p class="text-gray-600 mt-1">Kelola inventaris alat dan peralatan</p>
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
                        <a href="{{ route('assets.tools.export') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition">
                            <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                            <span class="text-sm font-medium">Ekspor ke Excel</span>
                        </a>
                        <a href="{{ route('assets.tools.export-pdf') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition border-t border-gray-100">
                            <i class="ph ph-file-pdf text-lg text-red-600"></i>
                            <span class="text-sm font-medium">Ekspor ke PDF</span>
                        </a>
                    </div>
                </div>
                <button type="button" id="createNewTool" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
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
                        <p class="text-sm text-gray-500">Total Alat</p>
                        <h5 class="text-2xl font-bold text-gray-900 mt-1" id="totalTools">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-ebara-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-wrench text-2xl text-ebara-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Kondisi Baik</p>
                        <h5 class="text-2xl font-bold text-green-600 mt-1" id="goodCondition">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-check-circle text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Perbaikan</p>
                        <h5 class="text-2xl font-bold text-yellow-600 mt-1" id="repairCondition">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-wrench text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Rusak</p>
                        <h5 class="text-2xl font-bold text-red-600 mt-1" id="damagedCondition">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-warning-circle text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Controls Header -->
            <div class="p-5 border-b border-gray-100 bg-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Cari alat..." class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="toolsTable" class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Kode</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Nama</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Kategori</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Merk</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Tipe</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Tahun</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Jumlah</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Kondisi</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Lokasi</th>
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
                <p class="text-sm text-gray-500">Belum ada data Alat Aset ditemukan.</p>
            </div>
        </div>
 
    </div>
 
    <!-- Add/Edit Modal -->
    <div id="toolModal" class="fixed inset-0 bg-gray-900/50 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full mx-4 sm:mx-auto sm:max-w-2xl flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Alat</h3>
                    <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <form id="toolForm" class="p-6 space-y-4 overflow-y-auto flex-1" novalidate>
                    <input type="hidden" id="toolId" name="id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Alat</label>
                        <input type="text" id="tool_code" name="tool_code" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Contoh: TL-001">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Alat</label>
                        <input type="text" id="name" name="name" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select id="category" name="category" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                            <option value="">Pilih Kategori</option>
                            <option value="hand_tools">Alat Tangan</option>
                            <option value="power_tools">Alat Listrik</option>
                            <option value="measuring">Alat Ukur</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <x-ui.form-grid columns="2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Merk</label>
                            <input type="text" id="brand" name="brand" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Contoh: Mitsubishi, Bosch">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                            <input type="text" id="type" name="type" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Contoh: MX-200">
                        </div>
                    </x-ui.form-grid>
                    <x-ui.form-grid columns="2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembelian</label>
                            <input type="date" id="purchase_date" name="purchase_date" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                            <input type="number" id="purchase_price" name="purchase_price" required min="0" step="0.01" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Contoh: 1500000">
                        </div>
                    </x-ui.form-grid>
                    <x-ui.form-grid columns="2">
                        <x-ui.lokasi-gedung-select label="Lokasi" name="location" id="location" :gedungs="$gedungs" />
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                            <input type="number" id="quantity" name="quantity" required min="1" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Contoh: 1">
                        </div>
                    </x-ui.form-grid>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                        <select id="condition" name="condition" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                            <option value="">Pilih Kondisi</option>
                            <option value="Good">Baik</option>
                            <option value="Repair">Perbaikan</option>
                            <option value="Damaged">Rusak</option>
                            <option value="Disposed">Dibuang</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition"></textarea>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">Batal</button>
                        <button type="button" id="submitToolBtn" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
    <!-- QR Code Modal -->
    <div id="qrModal" class="fixed inset-0 bg-gray-900/50 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full mx-4 sm:mx-auto sm:max-w-md flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900">QR Code</h3>
                    <button type="button" id="closeQrModalBtn" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <div class="p-6 text-center overflow-y-auto flex-1">
                    <div id="qrCodeContainer" class="flex justify-center"></div>
                    <p id="qrCodeText" class="mt-4 text-sm text-gray-600"></p>
                </div>
            </div>
        </div>
    </div>
 
    @push('scripts')
    <script>
    var storeUrl = "{{ route('assets.tools.store') }}";
    var showUrlTemplate = "{{ route('assets.tools.show', ':id') }}";
    var updateUrlTemplate = "{{ route('assets.tools.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('assets.tools.destroy', ':id') }}";
    var qrCodeUrlTemplate = "{{ route('assets.tools.qr-code', ':id') }}";
 
    $(document).ready(function() {
        let table = $('#toolsTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{{ route('assets.tools.data') }}',
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
                    targets: 1, // Nama (primary column)
                    className: 'text-sm font-bold text-gray-900'
                },
                {
                    targets: [2, 3, 4, 5, 7], // Kategori, Merk, Tipe, Tahun, Lokasi
                    className: 'text-sm text-gray-600'
                },
                {
                    targets: 6, // Kondisi
                    className: 'text-sm'
                },
                {
                    targets: 8, // Aksi
                    className: 'text-center'
                }
            ],
            columns: [
                { data: 'tool_code' },
                { data: 'name' },
                { data: 'category' },
                { data: 'brand' },
                { data: 'type' },
                { data: 'purchase_year' },
                { data: 'quantity' },
                { 
                    data: 'condition',
                    render: function(data, type, row) {
                        let badgeClass = 'bg-green-50 text-green-700 px-2 py-1 rounded-full text-xs font-medium';
                        if (data === 'Repair') {
                            badgeClass = 'bg-yellow-50 text-yellow-700 px-2 py-1 rounded-full text-xs font-medium';
                        } else if (data === 'Damaged') {
                            badgeClass = 'bg-red-50 text-red-700 px-2 py-1 rounded-full text-xs font-medium';
                        } else if (data === 'Disposed') {
                            badgeClass = 'bg-gray-50 text-gray-700 px-2 py-1 rounded-full text-xs font-medium';
                        }
                        return '<span class="' + badgeClass + '">' + row.condition_label + '</span>';
                    }
                },
                { data: 'location' },
                {
                    data: 'actions',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewQrCode(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="QR Code">
                                    <i class="ph ph-qr-code text-xl"></i>
                                </button>
                                <button onclick="editTool(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="Edit">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteTool(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
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
                $('#totalTools').text(json.stats.total || 0);
                $('#goodCondition').text(json.stats.good || 0);
                $('#repairCondition').text(json.stats.repair || 0);
                $('#damagedCondition').text(json.stats.damaged || 0);
            }
        });
 
        $('#createNewTool').on('click', function() {
            $('#modalTitle').text('Tambah Alat');
            $('#toolForm')[0].reset();
            $('#toolId').val('');
            $('#toolModal').removeClass('hidden');
        });
 
        $('#closeModalBtn').on('click', function() {
            $('#toolModal').addClass('hidden');
        });
 
        $('#cancelBtn').on('click', function() {
            $('#toolModal').addClass('hidden');
        });
 
        $('#closeQrModalBtn').on('click', function() {
            $('#qrModal').addClass('hidden');
        });
    });
 
    function editTool(id) {
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#modalTitle').text('Edit Alat');
                    $('#toolId').val(data.id);
                    $('#tool_code').val(data.tool_code);
                    $('#name').val(data.name);
                    $('#category').val(data.category);
                    $('#brand').val(data.brand);
                    $('#type').val(data.type);
                    $('#purchase_date').val(data.purchase_date);
                    $('#purchase_price').val(data.purchase_price);
                    $('#quantity').val(data.quantity);
                    $('#location').val(data.location);
                    $('#condition').val(data.condition);
                    $('#description').val(data.description);
                    $('#toolModal').removeClass('hidden');
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
                    text: 'Gagal mengambil data alat',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }
 
    // Add click handler for submit button
    $('#submitToolBtn').on('click', function(e) {
        e.preventDefault();
        console.log('Submit button clicked');
        
        let id = $('#toolId').val();
        let isEdit = id !== '';
        
        // Always show confirmation dialog for both create and edit
        let confirmTitle = isEdit ? 'Apakah Anda yakin?' : 'Apakah Anda yakin?';
        let confirmText = isEdit ? 'Data alat akan diperbarui. Pastikan data sudah benar.' : 'Data alat akan ditambahkan. Pastikan data sudah benar.';
        let successMessage = isEdit ? 'Data berhasil diupdate' : 'Data berhasil disimpan';
        
        Swal.fire({
            title: confirmTitle,
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009B77',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitToolForm(successMessage);
            }
        });
    });

    function submitToolForm(successMessage = 'Data berhasil disimpan') {
        let formData = $('#toolForm').serialize();
        let id = $('#toolId').val();
        let url = storeUrl;
        let method = 'POST';
        
        if (id) {
            url = updateUrlTemplate.replace(':id', id);
            // Add _method field for Laravel method spoofing
            formData += '&_method=PUT';
            method = 'POST'; // Always use POST with _method field
        }
 
        // Disable submit button to prevent double submission
        $('#submitToolBtn').prop('disabled', true).text('Menyimpan...');
 
        $.ajax({
            url: url,
            method: method,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Update successful:', response);
                $('#toolModal').addClass('hidden');
                
                // Reload DataTable with a small delay to ensure server has processed update
                setTimeout(function() {
                    $('#toolsTable').DataTable().ajax.reload(null, false); // false = keep current page
                    console.log('DataTable reloaded');
                }, 500);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: successMessage,
                    confirmButtonColor: '#009B77'
                });
                // Reset form and re-enable button
                $('#toolForm')[0].reset();
                $('#submitToolBtn').prop('disabled', false).text('Simpan');
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        // Handle validation errors
                        let errors = xhr.responseJSON.errors;
                        let errorMessages = [];
                        for (let key in errors) {
                            errorMessages.push(errors[key][0]);
                        }
                        errorMessage = errorMessages.join('<br>');
                    } else if (xhr.responseJSON.message) {
                        // Handle single error message
                        errorMessage = xhr.responseJSON.message;
                    }
                } else if (xhr.status === 422) {
                    errorMessage = 'Validation error. Silakan periksa kembali input Anda.';
                } else if (xhr.status === 403) {
                    errorMessage = 'Anda tidak memiliki izin untuk melakukan aksi ini.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Data tidak ditemukan.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Terjadi kesalahan server. Silakan coba lagi.';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errorMessage,
                    confirmButtonColor: '#dc2626'
                });
                // Re-enable button on error
                $('#submitToolBtn').prop('disabled', false).text('Simpan');
            }
        });
    }
 
 
    function deleteTool(id) {
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
                        $('#toolsTable').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil dihapus',
                            confirmButtonColor: '#009B77'
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Gagal menghapus data';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            errorMessage = 'Data tidak ditemukan';
                        } else if (xhr.status === 403) {
                            errorMessage = 'Anda tidak memiliki izin untuk menghapus data ini';
                        } else if (xhr.status === 500) {
                            errorMessage = 'Terjadi kesalahan server. Silakan coba lagi.';
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonColor: '#dc2626'
                        });
                    }
                });
            }
        });
    }
 
    function viewQrCode(id) {
        $.ajax({
            url: qrCodeUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#qrCodeContainer').html(response.qrCode);
                    $('#qrCodeText').text('QR Code untuk: ' + response.toolCode);
                    $('#qrModal').removeClass('hidden');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Gagal generate QR Code',
                        confirmButtonColor: '#dc2626'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Gagal memuat QR Code';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }
    </script>
    @endpush
</x-app-layout>
