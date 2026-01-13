<x-app-layout>
    <x-slot name="title">Model Aset</x-slot>
    
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
    </style>
    @endpush
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Model Aset</h1>
                <p class="text-gray-600 mt-1">Kelola inventaris model dan cetakan</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('assets.models.export') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                    <i class="ph ph-download-simple text-lg"></i>
                    <span>Ekspor</span>
                </a>
                <button type="button" id="createNewModel" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                    <i class="ph ph-plus text-lg"></i>
                    <span>Tambah Data</span>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
        <div class="bg-white shadow-lg rounded-xl border border-gray-100 p-6 relative overflow-hidden">
            <table id="modelsTable" class="w-full">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <th class="px-4 py-3 text-left font-semibold">Kode</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold">Tipe</th>
                        <th class="px-4 py-3 text-left font-semibold">Material</th>
                        <th class="px-4 py-3 text-left font-semibold">Tgl Pembuatan</th>
                        <th class="px-4 py-3 text-left font-semibold">Kondisi</th>
                        <th class="px-4 py-3 text-left font-semibold">Lokasi</th>
                        <th class="px-4 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded by DataTables -->
                </tbody>
            </table>
        </div>

    </div>

    <!-- Add/Edit Modal -->
    <div id="modelModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-4">
            <div class="relative bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Model</h3>
                    <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <form id="modelForm" class="p-6 space-y-4">
                    <input type="hidden" id="modelId" name="id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Model</label>
                        <input type="text" id="name" name="name" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                        <input type="text" id="type" name="type" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Contoh: Injection, CNC">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Material Terkait</label>
                        <select id="material_id" name="material_id" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                            <option value="">-- Pilih Material (Opsional) --</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->material_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembuatan</label>
                        <input type="date" id="manufactured_date" name="manufactured_date" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi</label>
                        <select id="condition" name="condition" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                            <option value="">Pilih Kondisi</option>
                            <option value="Good">Baik</option>
                            <option value="Repair">Perbaikan</option>
                            <option value="Damaged">Rusak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <input type="text" id="location" name="location" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition" placeholder="Contoh: Gudang A">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">Batal</button>
                        <button type="submit" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div id="qrModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-4">
            <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
                <div class="flex justify-between items-center p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">QR Code</h3>
                    <button type="button" id="closeQrModalBtn" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <div class="p-6 text-center">
                    <div id="qrCodeContainer" class="flex justify-center"></div>
                    <p id="qrCodeText" class="mt-4 text-sm text-gray-600"></p>
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
            columns: [
                { data: 'model_code' },
                { data: 'name' },
                { data: 'type' },
                { data: 'material_name' },
                { data: 'manufactured_date' },
                { 
                    data: 'condition',
                    render: function(data, type, row) {
                        let badgeClass = 'bg-green-100 text-green-800';
                        if (data === 'Repair') {
                            badgeClass = 'bg-yellow-100 text-yellow-800';
                        } else if (data === 'Damaged') {
                            badgeClass = 'bg-red-100 text-red-800';
                        }
                        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' + badgeClass + '">' + row.condition_label + '</span>';
                    }
                },
                { data: 'location' },
                {
                    data: 'actions',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewQrCode(${row.id})" class="text-blue-600 hover:text-blue-800 transition" title="QR Code">
                                    <i class="ph ph-qr-code text-xl"></i>
                                </button>
                                <button onclick="editModel(${row.id})" class="text-indigo-600 hover:text-indigo-800 transition" title="Edit">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteModel(${row.id})" class="text-red-600 hover:text-red-800 transition" title="Hapus">
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
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
        });

        table.on('xhr', function() {
            let json = table.ajax.json();
            if (json) {
                $('#totalModels').text(json.stats.total || 0);
                $('#goodCondition').text(json.stats.good || 0);
                $('#repairCondition').text(json.stats.repair || 0);
                $('#damagedCondition').text(json.stats.damaged || 0);
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

        $('#closeQrModalBtn').on('click', function() {
            $('#qrModal').addClass('hidden');
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
                    $('#type').val(data.type);
                    $('#material_id').val(data.material_id);
                    $('#manufactured_date').val(data.manufactured_date);
                    $('#condition').val(data.condition);
                    $('#location').val(data.location);
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

    function viewQrCode(id) {
        $.ajax({
            url: qrCodeUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(data) {
                $('#qrCodeContainer').html(data.qrCode);
                $('#qrCodeText').text(data.modelCode);
                $('#qrModal').removeClass('hidden');
            }
        });
    }
    </script>
    @endpush
</x-app-layout>
