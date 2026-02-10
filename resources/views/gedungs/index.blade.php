<x-app-layout>
    <x-slot name="title">Data Gedung</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Gedung</h1>
                <p class="text-gray-600 mt-1">Kelola data gedung dan lokasi aset</p>
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
                        <div class="flex items-center gap-2 px-4 py-3 text-gray-400">
                            <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                            <span class="text-sm font-medium">Ekspor ke Excel (Coming Soon)</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-3 text-gray-400 border-t border-gray-100">
                            <i class="ph ph-file-pdf text-lg text-red-600"></i>
                            <span class="text-sm font-medium">Ekspor ke PDF (Coming Soon)</span>
                        </div>
                    </div>
                </div>
                <button type="button" id="createNewGedung" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
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
                        <p class="text-sm text-gray-500">Total Gedung</p>
                        <h5 class="text-2xl font-bold text-gray-900 mt-1" id="totalGedungs">0</h5>
                    </div>
                    <div class="w-12 h-12 bg-ebara-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-buildings text-2xl text-ebara-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Aset di Gedung</p>
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
                    <input type="text" id="searchInput" placeholder="Cari gedung..." class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="gedungsTable" class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Kode Gedung</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Nama Gedung</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Total Model</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Total Material</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Total Tool</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Total Aset</th>
                        <th class="px-6 py-4 text-center font-semibold whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Data will be loaded by DataTables -->
                </tbody>
            </table>
            
            <!-- Empty State -->
            <div id="emptyState" class="p-12 text-center flex flex-col items-center justify-center hidden">
                <i class="ph ph-buildings text-5xl mb-4 text-gray-300"></i>
                <p class="text-sm text-gray-500">Belum ada data gedung ditemukan.</p>
            </div>
        </div>
    </div>
  
    <!-- Add/Edit Modal -->
    <div id="gedungModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9999] hidden" style="display: none;" x-data="{ open: false }">
        <div class="flex items-center justify-center min-h-screen w-full p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0 modal-content">
                <!-- Modal Header with Gradient -->
                <div class="relative bg-gradient-to-r from-ebara-600 to-ebara-700 rounded-t-2xl p-6 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="ph ph-buildings text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold" id="modalTitle">Tambah Gedung</h3>
                                <p class="text-ebara-100 text-sm">Isi informasi gedung baru</p>
                            </div>
                        </div>
                        <button type="button" id="closeModalBtn" class="text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200 p-2 rounded-lg" tabindex="0" role="button" aria-label="Tutup modal">
                            <i class="ph ph-x text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <form id="gedungForm" class="p-6 space-y-5" action="{{ route('master-data.gedungs.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="gedungId" name="id">
                    
                    <!-- Kode Gedung Field -->
                    <div class="space-y-2">
                        <label for="gedung_id_field" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-hash text-ebara-600 mr-2"></i>
                            Kode Gedung
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="gedung_id_field"
                                   name="gedung_id"
                                   required
                                   class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400"
                                   placeholder="Contoh: GDG-001"
                                   autocomplete="off">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph ph-tag text-gray-400"></i>
                            </div>
                        </div>
                        @error('gedung_id')
                            <p class="mt-1 text-xs text-red-500 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Nama Gedung Field -->
                    <div class="space-y-2">
                        <label for="nama" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-building text-ebara-600 mr-2"></i>
                            Nama Gedung
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="nama"
                                   name="nama"
                                   required
                                   class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400"
                                   placeholder="Contoh: Gedung Produksi A"
                                   autocomplete="off">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ph ph-text-align-left text-gray-400"></i>
                            </div>
                        </div>
                        @error('nama')
                            <p class="mt-1 text-xs text-red-500 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Info Box -->
                    <div class="bg-ebara-50 border border-ebara-200 rounded-xl p-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <i class="ph ph-info text-ebara-600 text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-ebara-900">Informasi Penting</h4>
                                <p class="text-xs text-ebara-700 mt-1">
                                    Kode gedung bersifat unik dan tidak dapat diubah setelah disimpan. Pastikan kode gedung mengikuti format standar perusahaan.
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-2xl flex flex-col sm:flex-row justify-end gap-3">
                    <button type="button" id="cancelBtn" class="order-2 sm:order-1 w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-xl border border-gray-300 transition-all duration-200 flex items-center justify-center">
                        <i class="ph ph-x mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" form="gedungForm" class="order-1 sm:order-2 w-full sm:w-auto bg-gradient-to-r from-ebara-600 to-ebara-700 hover:from-ebara-700 hover:to-ebara-800 text-white font-medium py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="ph ph-check-circle mr-2"></i>
                        Simpan Gedung
                    </button>
                </div>
            </div>
        </div>
    </div>
  
    @push('styles')
    <style>
        #gedungModal {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 9999 !important;
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }
        
        #gedungModal .flex {
            width: 100% !important;
            height: 100% !important;
            min-height: 100vh !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 1rem !important;
            box-sizing: border-box !important;
        }
        
        #gedungModal.show {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        #gedungModal.hide, #gedungModal.hidden {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }
        
        #gedungModal .modal-content {
            animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: auto !important;
            position: relative;
            margin: 0;
            max-width: 90vw;
            width: 100%;
        }
        
        #gedungModal.show .modal-content {
            transform: scale(1) !important;
            opacity: 1 !important;
        }
        
        @keyframes modalSlideIn {
            0% {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            50% {
                transform: scale(1.02) translateY(5px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        @keyframes modalFadeOut {
            0% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
            100% {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
        }
        
        /* Ensure buttons are clickable */
        #gedungModal button {
            pointer-events: auto !important;
            position: relative !important;
            z-index: 10001 !important;
            cursor: pointer !important;
            transition: all 0.2s ease;
        }
        
        /* Fix any overlay issues */
        #gedungModal {
            pointer-events: auto !important;
        }
        
        /* Ensure modal is on top */
        #gedungModal.show {
            z-index: 9999 !important;
        }
        
        /* Input focus effects */
        .modal-content input:focus {
            box-shadow: 0 0 0 3px rgba(0, 155, 119, 0.1);
        }
        
        /* Button hover effects */
        .modal-content button[type="submit"]:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 155, 119, 0.25);
        }
        
        /* Center modal properly on all screen sizes */
        @media (min-width: 641px) {
            #gedungModal .modal-content {
                max-width: 500px !important;
                width: 100% !important;
            }
        }
        
        @media (max-width: 640px) {
            #gedungModal .modal-content {
                width: 95vw !important;
                max-width: 95vw !important;
                margin: 0 1rem !important;
            }
        }
        
        @media (max-width: 480px) {
            #gedungModal .modal-content {
                width: 98vw !important;
                max-width: 98vw !important;
                margin: 0 0.5rem !important;
            }
        }
        
        /* Fix for iOS Safari */
        @supports (-webkit-touch-callout: none) {
            #gedungModal {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
            }
        }
    </style>
    @endpush
    
    @push('scripts')
    <script>
    var storeUrl = "{{ route('master-data.gedungs.store') }}";
    var showUrlTemplate = "{{ route('master-data.gedungs.show', ':id') }}";
    var updateUrlTemplate = "{{ route('master-data.gedungs.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('master-data.gedungs.destroy', ':id') }}";
    var dataUrl = "{{ route('master-data.gedungs.data') }}";
 
    // Global functions - define outside document ready
    window.showModal = function() {
        console.log('Showing modal...');
        const modal = document.getElementById('gedungModal');
        const modalContent = modal.querySelector('.modal-content');
        
        if (!modal) {
            console.error('Modal element not found!');
            return;
        }
        
        // Reset modal content state
        modalContent.style.transform = 'scale(0.9)';
        modalContent.style.opacity = '0';
        
        // Show modal with proper centering
        modal.classList.remove('hide', 'hidden');
        modal.classList.add('show');
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        
        // Force reflow to ensure proper centering
        modal.offsetHeight;
        
        // Animate modal content
        setTimeout(() => {
            modalContent.style.transform = 'scale(1)';
            modalContent.style.opacity = '1';
        }, 10);
    }
 
    window.hideModal = function() {
        console.log('Hiding modal...');
        const modal = document.getElementById('gedungModal');
        const modalContent = modal.querySelector('.modal-content');
        
        if (!modal) {
            console.error('Modal element not found!');
            return;
        }
        
        // Animate out
        modalContent.style.transform = 'scale(0.9)';
        modalContent.style.opacity = '0';
        
        setTimeout(() => {
            modal.classList.remove('show');
            modal.classList.add('hide', 'hidden');
            modal.style.display = 'none';
            modal.style.visibility = 'hidden';
            modal.style.opacity = '0';
        }, 300);
    }
 
    window.handleCreateNewGedung = function() {
        console.log('Create handler called');
        try {
            const modal = document.getElementById('gedungModal');
            const title = document.getElementById('modalTitle');
            const form = document.getElementById('gedungForm');
            const idField = document.getElementById('gedungId');
            const modalContent = modal.querySelector('.modal-content');
            
            if (title) {
                title.textContent = 'Tambah Gedung';
                const subtitle = title.nextElementSibling;
                if (subtitle) subtitle.textContent = 'Isi informasi gedung baru';
            }
            if (form) form.reset();
            if (idField) idField.value = '';
            
            if (modal) {
                // Reset modal content state
                modalContent.style.transform = 'scale(0.9)';
                modalContent.style.opacity = '0';
                
                // Show modal with proper centering
                modal.style.display = 'flex';
                modal.style.visibility = 'visible';
                modal.style.opacity = '1';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                modal.classList.remove('hide', 'hidden');
                modal.classList.add('show');
                
                // Force reflow to ensure proper centering
                modal.offsetHeight;
                
                // Animate modal content
                setTimeout(() => {
                    modalContent.style.transform = 'scale(1)';
                    modalContent.style.opacity = '1';
                }, 10);
            }
        } catch (error) {
            console.error('Error in create handler:', error);
        }
    }
 
    $(document).ready(function() {
        console.log('Document ready, initializing gedung handlers...');
        
        let table = $('#gedungsTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: dataUrl,
            createdRow: function(row, data, dataIndex) {
                $(row).addClass('group hover:bg-gray-50 transition-colors duration-200');
            },
            columnDefs: [
                {
                    targets: 0, // Kode Gedung - Pill style
                    className: 'text-sm',
                    render: function(data, type, row) {
                        return '<span class="bg-gray-100 text-gray-600 py-1 px-2 rounded-md font-mono text-xs">' + data + '</span>';
                    }
                },
                {
                    targets: 1, // Nama Gedung (primary column)
                    className: 'text-sm font-bold text-gray-900'
                },
                {
                    targets: [2, 3, 4, 5], // Total columns
                    className: 'text-sm text-gray-600'
                },
                {
                    targets: 6, // Aksi
                    className: 'text-center'
                }
            ],
            columns: [
                { data: 'gedung_id' },
                { data: 'nama' },
                { data: 'asset_models_count' },
                { data: 'asset_materials_count' },
                { data: 'asset_tools_count' },
                { data: 'total_assets' },
                {
                    data: 'actions',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editGedung(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="Edit">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteGedung(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus">
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
                $('#totalGedungs').text(json.recordsTotal || 0);
                $('#totalAssets').text(json.data.reduce((sum, row) => sum + (row.total_assets || 0), 0));
            }
        });
 
        // Create button handler
        $('#createNewGedung').on('click', function() {
            console.log('Create button clicked');
            handleCreateNewGedung();
        });
 
        // Close button handlers
        $('#closeModalBtn').on('click', function() {
            console.log('Close button clicked');
            hideModal();
        });
 
        $('#cancelBtn').on('click', function() {
            console.log('Cancel button clicked');
            hideModal();
        });
 
        // Form submit handler
        $('#gedungForm').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const formData = new FormData(form[0]);
            const id = $('#gedungId').val();
            let url = storeUrl;
            let method = 'POST';
            let successMessage = 'Data berhasil disimpan';
 
            if (id) {
                url = updateUrlTemplate.replace(':id', id);
                method = 'PUT';
                successMessage = 'Data berhasil diupdate';
                // Add method override for PUT requests
                formData.append('_method', 'PUT');
            }
 
            $.ajax({
                url: url,
                type: 'POST', // Always use POST with _method override
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                success: function(data) {
                    console.log('Response:', data);
                    hideModal();
                    $('#gedungsTable').DataTable().ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: successMessage,
                        confirmButtonColor: '#009B77'
                    });
                },
                error: function(xhr) {
                    console.error('Error:', xhr);
                    let errorMessage = 'Terjadi kesalahan saat menyimpan data';
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
        });
 
        // Close modal when clicking outside
        $('#gedungModal').on('click', function(e) {
            if (e.target === this) {
                console.log('Clicked outside modal, closing...');
                hideModal();
            }
        });
 
        // Close modal with ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = $('#gedungModal');
                if (modal.hasClass('show')) {
                    console.log('ESC key pressed, closing modal...');
                    hideModal();
                }
            }
        });
    });
 
    function editGedung(id) {
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    const modal = document.getElementById('gedungModal');
                    const modalContent = modal.querySelector('.modal-content');
                    const title = document.getElementById('modalTitle');
                    
                    // Update title and subtitle
                    if (title) {
                        title.textContent = 'Edit Gedung';
                        const subtitle = title.nextElementSibling;
                        if (subtitle) subtitle.textContent = 'Perbarui informasi gedung';
                    }
                    
                    // Fill form data
                    $('#gedungId').val(data.id);
                    $('#gedung_id_field').val(data.gedung_id);
                    $('#nama').val(data.nama);
                    
                    // Show modal with proper centering
                    modalContent.style.transform = 'scale(0.9)';
                    modalContent.style.opacity = '0';
                    
                    modal.style.display = 'flex';
                    modal.style.visibility = 'visible';
                    modal.style.opacity = '1';
                    modal.style.alignItems = 'center';
                    modal.style.justifyContent = 'center';
                    modal.classList.remove('hide', 'hidden');
                    modal.classList.add('show');
                    
                    // Force reflow to ensure proper centering
                    modal.offsetHeight;
                    
                    // Animate modal content
                    setTimeout(() => {
                        modalContent.style.transform = 'scale(1)';
                        modalContent.style.opacity = '1';
                    }, 10);
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
                    text: 'Gagal mengambil data gedung',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }
 
    function deleteGedung(id) {
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
                        $('#gedungsTable').DataTable().ajax.reload();
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
    </script>
    @endpush
</x-app-layout>