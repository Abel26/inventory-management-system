<x-app-layout>
    <x-slot name="title">Data Satuan</x-slot>
    
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Satuan</h1>
                <p class="text-gray-600 mt-1">Kelola data satuan untuk inventory</p>
            </div>
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <button type="button" id="createNewSatuan" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
                    <i class="ph ph-plus text-lg"></i>
                    <span>Tambah Data</span>
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Controls Header -->
            <div class="p-5 border-b border-gray-100 bg-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Cari satuan..." class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="satuansTable" class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">No</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Kode</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Nama Satuan</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Dibuat</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Diubah</th>
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
                <p class="text-sm text-gray-500">Belum ada data Satuan ditemukan.</p>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="satuanModal" class="fixed inset-0 bg-gray-900/50 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full mx-4 sm:mx-auto sm:max-w-md flex flex-col max-h-[90vh]">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Satuan</h3>
                    <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <form id="satuanForm" class="p-6 space-y-4 overflow-y-auto flex-1">
                    <input type="hidden" id="satuanId" name="id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Satuan</label>
                        <input type="text" id="nama" name="nama" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
                        <input type="text" id="kode" name="kode" required class="w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition">
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">Batal</button>
                        <button type="button" id="submitSatuanBtn" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    var storeUrl = "{{ route('master-data.satuans.store') }}";
    var showUrlTemplate = "{{ route('master-data.satuans.show', ':id') }}";
    var updateUrlTemplate = "{{ route('master-data.satuans.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('master-data.satuans.destroy', ':id') }}";
    var dataUrl = "{{ route('master-data.satuans.data') }}";

    $(document).ready(function() {
        // Check if DataTables is loaded
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTables is not loaded');
            return;
        }
        
        // Test the data URL first
        console.log('Testing data URL:', dataUrl);
        
        // Initialize DataTables
        let table = $('#satuansTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            stateSave: true,
            ajax: {
                url: dataUrl,
                type: 'GET',
                data: function(d) {
                    console.log('DataTables request:', d);
                    return d;
                },
                dataSrc: function(json) {
                    console.log('DataTables response:', json);
                    if (json.error) {
                        console.error('Server error:', json.error);
                    }
                    return json.data || [];
                },
                error: function(xhr, error, code) {
                    console.error('DataTables error:', error, code);
                    console.error('Response:', xhr.responseText);
                }
            },
            searching: true,
            paging: true,
            ordering: true,
            info: true,
            lengthChange: true,
            pageLength: 10,
            order: [[1, 'asc']], // Order by kode column by default
            createdRow: function(row, data, dataIndex) {
                $(row).addClass('group hover:bg-gray-50 transition-colors duration-200');
            },
            columnDefs: [
                {
                    targets: 0, // No
                    className: 'text-sm text-center',
                    width: '50px'
                },
                {
                    targets: [1, 2, 3, 4], // Kode, Nama, Dibuat, Diubah
                    className: 'text-sm'
                },
                {
                    targets: 5, // Aksi
                    className: 'text-center',
                    width: '120px'
                }
            ],
            columns: [
                { data: 'DT_RowIndex' },
                { data: 'kode' },
                { data: 'nama' },
                { data: 'created_at' },
                { data: 'updated_at' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        console.log('Row data for actions:', row);
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editSatuan(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="Edit">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteSatuan(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
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
            initComplete: function(settings, json) {
                console.log('DataTables initialized:', settings);
                console.log('Initial data:', json);
                
                // Show/hide empty state based on data count
                let api = this.api();
                let count = api.page.info().recordsTotal;
                if (count === 0) {
                    $('#emptyState').removeClass('hidden');
                } else {
                    $('#emptyState').addClass('hidden');
                }
            },
            drawCallback: function(settings) {
                console.log('DataTables redraw:', settings);
                
                // Show/hide empty state based on data count
                let api = this.api();
                let count = api.page.info().recordsTotal;
                if (count === 0) {
                    $('#emptyState').removeClass('hidden');
                } else {
                    $('#emptyState').addClass('hidden');
                }
            },
            error: function(xhr, error, code) {
                console.error('DataTables initialization error:', error, code);
                console.error('Response:', xhr.responseText);
                $('#emptyState').removeClass('hidden');
                $('#emptyState p').text('Terjadi kesalahan saat memuat data. Silakan refresh halaman.');
            }
        });

        // Connect search input to DataTables
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#createNewSatuan').on('click', function() {
            console.log('Opening create modal');
            $('#modalTitle').text('Tambah Satuan');
            $('#satuanForm')[0].reset();
            $('#satuanId').val('');
            $('#nama').val('');
            $('#kode').val('');
            $('#satuanModal').removeClass('hidden');
        });

        $('#closeModalBtn').on('click', function() {
            $('#satuanModal').addClass('hidden');
        });

        $('#cancelBtn').on('click', function() {
            $('#satuanModal').addClass('hidden');
        });

        $('#submitSatuanBtn').on('click', function() {
            console.log('Submit button clicked');
            $('#satuanForm').submit();
        });
    });

    function editSatuan(id) {
        console.log('Editing satuan with ID:', id);
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                console.log('Edit response:', response);
                if (response.success) {
                    const data = response.data;
                    console.log('Satuan data:', data);
                    $('#modalTitle').text('Edit Satuan');
                    $('#satuanId').val(data.id);
                    $('#nama').val(data.nama);
                    $('#kode').val(data.kode);
                    $('#satuanModal').removeClass('hidden');
                } else {
                    console.error('Edit failed:', response.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonColor: '#dc2626'
                    });
                }
            },
            error: function(xhr) {
                console.error('Edit error:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal mengambil data satuan',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    function deleteSatuan(id) {
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
                        $('#satuansTable').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil dihapus',
                            confirmButtonColor: '#009B77'
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal menghapus data',
                            confirmButtonColor: '#dc2626'
                        });
                    }
                });
            }
        });
    }

    $('#satuanForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = $(this).serialize();
        let id = $('#satuanId').val();
        let url = storeUrl;
        let method = 'POST';
        let successMessage = 'Data berhasil disimpan';

        console.log('Form submit - ID:', id);
        console.log('Form submit - URL:', url);
        console.log('Form submit - Data:', formData);

        if (id) {
            url = updateUrlTemplate.replace(':id', id);
            formData += '&_method=PUT';
            method = 'POST';
            successMessage = 'Data berhasil diperbarui';
            console.log('Form submit - Update URL:', url);
        }

        $.ajax({
            url: url,
            method: method,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Form submit success:', response);
                $('#satuanModal').addClass('hidden');
                $('#satuansTable').DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: successMessage,
                    confirmButtonColor: '#009B77'
                });
            },
            error: function(xhr) {
                console.error('Form submit error:', xhr);
                let errorMessage = 'Terjadi kesalahan';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        errorMessage = '';
                        for (let key in errors) {
                            errorMessage += errors[key][0] + '\n';
                        }
                    }
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
    </script>
    @endpush
</x-app-layout>