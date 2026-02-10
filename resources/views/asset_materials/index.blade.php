<x-app-layout>
    <x-slot name="title">Material Aset</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Material Aset</h1>
                <p class="text-gray-600 mt-1">Kelola inventaris material dan persediaan</p>
            </div>
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <!-- Export Dropdown -->
                <div x-data="{ open: false }" class="relative w-full md:w-auto">
                    <button @click="open = !open" @click.outside="open = false" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg inline-flex items-center justify-center gap-2 transition">
                        <i class="ph ph-download-simple text-lg"></i>
                        <span>Ekspor</span>
                        <i class="ph ph-caret-down text-sm" x-show="open" x-transition></i>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                        <a href="{{ route('assets.materials.export') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition">
                            <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                            <span class="text-sm font-medium">Ekspor ke Excel</span>
                        </a>
                        <a href="{{ route('assets.materials.export-pdf') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition border-t border-gray-100">
                            <i class="ph ph-file-pdf text-lg text-red-600"></i>
                            <span class="text-sm font-medium">Ekspor ke PDF</span>
                        </a>
                    </div>
                </div>
                <button type="button" id="createNewMaterial" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-lg inline-flex items-center justify-center gap-2 transition">
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
                        <p class="text-sm text-gray-500">Total Material</p>
                        <h5 class="text-2xl font-bold text-gray-900 mt-1" id="totalMaterials">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-ebara-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-package text-2xl text-ebara-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Stok Menipis</p>
                        <h5 class="text-2xl font-bold text-yellow-600 mt-1" id="lowStock">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-warning text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Stok Habis</p>
                        <h5 class="text-2xl font-bold text-red-600 mt-1" id="outOfStock">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-x-circle text-2xl text-red-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Nilai</p>
                        <h5 class="text-2xl font-bold text-gray-900 mt-1" id="totalValue">Rp 0</h5>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-currency-dollar text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Controls Header -->
            <div class="p-5 border-b border-gray-100 bg-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Cari material..." class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="materialsTable" class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Kode</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Nama Aset</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Tipe</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Stok</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Supplier</th>
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
                <p class="text-sm text-gray-500">Belum ada data Material Aset ditemukan.</p>
            </div>
        </div>

    </div>

    <!-- Add/Edit Modal -->
    <div id="materialModal" class="fixed inset-0 bg-gray-900/50 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full mx-4 sm:mx-auto sm:max-w-2xl flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Material</h3>
                    <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <form id="materialForm" class="p-6 space-y-4 overflow-y-auto flex-1">
                    <input type="hidden" id="materialId" name="id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Material</label>
                        <input type="text" id="material_code" name="material_code" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Opsional, akan digenerate otomatis">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Material</label>
                        <input type="text" id="name" name="name" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                        <select id="type" name="type" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                            <option value="">Pilih Tipe</option>
                            <option value="bahan_baku">Bahan Baku</option>
                            <option value="komponen">Komponen</option>
                            <option value="aksesoris">Aksesoris</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <x-ui.form-grid columns="2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                            <input type="number" id="quantity" name="quantity" required min="0" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                            <input type="text" id="unit" name="unit" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                        </div>
                    </x-ui.form-grid>
                    <x-ui.form-grid columns="2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min. Stok</label>
                            <input type="number" id="min_threshold" name="min_threshold" required min="0" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan</label>
                            <input type="number" id="unit_price" name="unit_price" min="0" step="0.01" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Opsional">
                        </div>
                    </x-ui.form-grid>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <input type="text" id="supplier" name="supplier" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Opsional">
                    </div>
                    <x-ui.form-grid columns="2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk</label>
                            <input type="date" id="entry_date" name="entry_date" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                            <input type="date" id="expiry_date" name="expiry_date" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Opsional">
                        </div>
                    </x-ui.form-grid>
                    <x-ui.lokasi-gedung-select label="Lokasi" name="location" id="location" :gedungs="$gedungs" />
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition"></textarea>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">Batal</button>
                        <button type="button" id="submitMaterialBtn" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg transition">Simpan</button>
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
    var storeUrl = "{{ route('assets.materials.store') }}";
    var showUrlTemplate = "{{ route('assets.materials.show', ':id') }}";
    var updateUrlTemplate = "{{ route('assets.materials.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('assets.materials.destroy', ':id') }}";
    var qrCodeUrlTemplate = "{{ route('assets.materials.qr-code', ':id') }}";

    $(document).ready(function() {
        let table = $('#materialsTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{{ route('assets.materials.data') }}',
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
                    targets: 1, // Nama Aset (primary column)
                    className: 'text-sm font-bold text-gray-900'
                },
                {
                    targets: [2, 4, 5], // Tipe, Supplier, Lokasi
                    className: 'text-sm text-gray-600'
                },
                {
                    targets: 6, // Aksi
                    className: 'text-center'
                }
            ],
            columns: [
                { data: 'material_code' },
                { data: 'name' },
                { data: 'type' },
                {
                    data: 'quantity',
                    render: function(data, type, row) {
                        let badgeClass = 'bg-green-50 text-green-700 px-2 py-1 rounded-full text-xs font-medium';
                        if (data == 0) {
                            badgeClass = 'bg-red-50 text-red-700 px-2 py-1 rounded-full text-xs font-medium';
                        } else if (data <= row.min_threshold) {
                            badgeClass = 'bg-yellow-50 text-yellow-700 px-2 py-1 rounded-full text-xs font-medium';
                        }
                        return '<span class="' + badgeClass + '">' + data + ' ' + row.unit + '</span>';
                    }
                },
                { data: 'supplier' },
                { data: 'location' },
                {
                    data: 'actions',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewQrCode(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="QR Code">
                                    <i class="ph ph-qr-code text-xl"></i>
                                </button>
                                <button onclick="editMaterial(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="Edit">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteMaterial(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
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
                $('#totalMaterials').text(json.stats.total || 0);
                $('#lowStock').text(json.stats.lowStock || 0);
                $('#outOfStock').text(json.stats.outOfStock || 0);
                $('#totalValue').text('Rp ' + (json.stats.totalValue || 0).toLocaleString('id-ID'));
            }
        });

        $('#createNewMaterial').on('click', function() {
            $('#modalTitle').text('Tambah Material');
            $('#materialForm')[0].reset();
            $('#materialId').val('');
            $('#materialModal').removeClass('hidden');
        });

        $('#closeModalBtn').on('click', function() {
            $('#materialModal').addClass('hidden');
        });

        $('#cancelBtn').on('click', function() {
            $('#materialModal').addClass('hidden');
        });

        $('#closeQrModalBtn').on('click', function() {
            $('#qrModal').addClass('hidden');
        });
    });

    function editMaterial(id) {
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    $('#modalTitle').text('Edit Material');
                    $('#materialId').val(data.id);
                    $('#material_code').val(data.material_code);
                    $('#name').val(data.name);
                    $('#type').val(data.type);
                    $('#quantity').val(data.quantity);
                    $('#unit').val(data.unit);
                    $('#min_threshold').val(data.min_threshold);
                    $('#unit_price').val(data.unit_price);
                    $('#supplier').val(data.supplier);
                    $('#entry_date').val(data.entry_date);
                    $('#expiry_date').val(data.expiry_date);
                    $('#location').val(data.location);
                    $('#description').val(data.description);
                    $('#materialModal').removeClass('hidden');
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
                    text: 'Gagal mengambil data material',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    $('#materialForm').on('submit', function(e) {
        e.preventDefault();
        
        // Debug: Check if form is being submitted
        console.log('Form submit triggered');
        
        // Basic form validation
        if (!this.checkValidity()) {
            // If HTML5 validation fails, trigger browser validation UI
            this.reportValidity();
            return false;
        }
        
        let formData = $(this).serialize();
        let id = $('#materialId').val();
        let url = storeUrl;
        let method = 'POST';
        let successMessage = 'Data berhasil disimpan';

        if (id) {
            url = updateUrlTemplate.replace(':id', id);
            // Add _method field for Laravel method spoofing
            formData += '&_method=PUT';
            method = 'POST'; // Always use POST with _method field
            successMessage = 'Data berhasil diupdate';
        }

        // Disable submit button to prevent double submission
        $('#submitMaterialBtn').prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: url,
            method: method,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Update successful:', response);
                $('#materialModal').addClass('hidden');
                
                // Reload DataTable with a small delay to ensure server has processed the update
                setTimeout(function() {
                    $('#materialsTable').DataTable().ajax.reload(null, false); // false = keep current page
                    console.log('DataTable reloaded');
                }, 500);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: successMessage,
                    confirmButtonColor: '#009B77'
                });
                // Reset form and re-enable button
                $('#materialForm')[0].reset();
                $('#submitMaterialBtn').prop('disabled', false).text('Simpan');
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
                $('#submitMaterialBtn').prop('disabled', false).text('Simpan');
            }
        });
    });

    // Also add click handler as backup with confirmation
    $('#submitMaterialBtn').on('click', function(e) {
        e.preventDefault();
        console.log('Submit button clicked');
        
        let id = $('#materialId').val();
        let isEdit = id !== '';
        
        if (isEdit) {
            // Show confirmation dialog for edit
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data material akan diperbarui. Pastikan data sudah benar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#009B77',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#materialForm').submit();
                }
            });
        } else {
            // Direct submit for new material
            $('#materialForm').submit();
        }
    });

    function deleteMaterial(id) {
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
                        $('#materialsTable').DataTable().ajax.reload();
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

    function viewQrCode(id) {
        $.ajax({
            url: qrCodeUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(data) {
                $('#qrCodeContainer').html(data.qrCode);
                $('#qrCodeText').text(data.materialCode);
                $('#qrModal').removeClass('hidden');
            }
        });
    }
    </script>
    @endpush
</x-app-layout>
