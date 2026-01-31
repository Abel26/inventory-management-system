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
                <button type="button" id="createNewModel" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors" onclick="console.log('Inline create clicked'); handleCreateNewModel();">
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
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Tipe</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Material</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">Tanggal Pembuatan</th>
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
                <p class="text-sm text-gray-500">Belum ada data Model Aset ditemukan.</p>
            </div>
        </div>
 
    </div>
 
    <!-- Add/Edit Modal -->
    <!-- REMINDER: Pastikan controller menggunakan $request->validate([...]) atau StoreModelRequest untuk validasi backend -->
    <div id="modelModal" class="fixed inset-0 bg-gray-900/50 z-[9999] hidden p-4" style="display: none;" x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }">
        <div class="flex items-center justify-center min-h-full">
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl flex flex-col max-h-[90vh] z-[10000] modal-content">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Tambah Model</h3>
                    <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100" tabindex="0" role="button" aria-label="Tutup modal" onclick="console.log('Inline close clicked'); hideModal();">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <form id="modelForm" class="p-6 space-y-6 overflow-y-auto flex-1" action="{{ route('assets.models.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="modelId" name="id">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Model</label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-ebara-500 focus:ring-ebara-500 sm:text-sm py-2.5">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1.5">Tipe</label>
                            <input type="text" id="type" name="type" required value="{{ old('type') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-ebara-500 focus:ring-ebara-500 sm:text-sm py-2.5" placeholder="Contoh: Injection, CNC">
                            @error('type')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="material_id" class="block text-sm font-medium text-gray-700 mb-1.5">Material</label>
                            <select id="material_id" name="material_id" value="{{ old('material_id') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-ebara-500 focus:ring-ebara-500 sm:text-sm py-2.5">
                                <option value="">Pilih Material</option>
                                @if(isset($materials))
                                    @foreach($materials as $material)
                                        <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>{{ $material->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('material_id')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="manufactured_date" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Pembuatan</label>
                            <input type="date" id="manufactured_date" name="manufactured_date" value="{{ old('manufactured_date') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-ebara-500 focus:ring-ebara-500 sm:text-sm py-2.5">
                            @error('manufactured_date')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="condition" class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi</label>
                            <select id="condition" name="condition" required value="{{ old('condition') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-ebara-500 focus:ring-ebara-500 sm:text-sm py-2.5">
                                <option value="">Pilih Kondisi</option>
                                <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Baik</option>
                                <option value="Repair" {{ old('condition') == 'Repair' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="Damaged" {{ old('condition') == 'Damaged' ? 'selected' : '' }}>Rusak</option>
                            </select>
                            @error('condition')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1.5">Lokasi</label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-ebara-500 focus:ring-ebara-500 sm:text-sm py-2.5" placeholder="Contoh: Gudang A, Ruang Produksi">
                        @error('location')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-ebara-500 focus:ring-ebara-500 sm:text-sm py-2.5">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3 flex-shrink-0">
                    <button type="button" id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2.5 px-4 rounded-xl transition" tabindex="0" role="button" aria-label="Batal" onclick="console.log('Inline cancel clicked'); hideModal();">Batal</button>
                    <button type="submit" form="modelForm" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl transition" tabindex="0" role="button" aria-label="Simpan data">Simpan</button>
                </div>
            </div>
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
      
        @push('styles')
    <style>
        #modelModal {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            transition: opacity 0.3s ease-in-out;
        }
        
        #modelModal.show {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        #modelModal.hide, #modelModal.hidden {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }
        
        #modelModal .modal-content {
            animation: modalSlideIn 0.3s ease-out;
            pointer-events: auto !important;
            margin: 0 auto;
            position: relative;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Ensure buttons are clickable */
        #modelModal button {
            pointer-events: auto !important;
            position: relative !important;
            z-index: 10001 !important;
            cursor: pointer !important;
        }
        
        /* Fix any overlay issues */
        #modelModal {
            pointer-events: auto !important;
        }
        
        /* Ensure modal is on top */
        #modelModal.show {
            z-index: 9999 !important;
        }
        
        /* Center modal properly on all screen sizes */
        @media (max-width: 640px) {
            #modelModal .modal-content {
                width: 90vw !important;
                max-width: 90vw !important;
                margin: 0 auto;
            }
        }
    </style>
    @endpush
    
    @push('scripts')
    <script>
    var storeUrl = "{{ route('assets.models.store') }}";
    var showUrlTemplate = "{{ route('assets.models.show', ':id') }}";
    var updateUrlTemplate = "{{ route('assets.models.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('assets.models.destroy', ':id') }}";
    var qrCodeUrlTemplate = "{{ route('assets.models.qr-code', ':id') }}";
 
    // Global functions - define outside document ready
    window.showModal = function() {
        console.log('Showing modal...');
        const modal = document.getElementById('modelModal');
        if (!modal) {
            console.error('Modal element not found!');
            return;
        }
        console.log('Modal before:', {
            display: modal.style.display,
            classes: modal.className
        });
        modal.classList.remove('hide', 'hidden');
        modal.classList.add('show');
        modal.style.display = 'flex';
        modal.style.visibility = 'visible';
        modal.style.opacity = '1';
        modal.style.alignItems = 'center';
        modal.style.justifyContent = 'center';
        console.log('Modal after:', {
            display: modal.style.display,
            classes: modal.className
        });
    }

    window.hideModal = function() {
        console.log('Hiding modal...');
        const modal = document.getElementById('modelModal');
        if (!modal) {
            console.error('Modal element not found!');
            return;
        }
        console.log('Modal before hide:', {
            display: modal.style.display,
            classes: modal.className
        });
        modal.classList.remove('show');
        modal.classList.add('hide', 'hidden');
        modal.style.display = 'none';
        modal.style.visibility = 'hidden';
        modal.style.opacity = '0';
        console.log('Modal after hide:', {
            display: modal.style.display,
            classes: modal.className
        });
    }

    window.handleCreateNewModel = function() {
        console.log('Global create handler called');
        try {
            // Direct DOM manipulation
            const modal = document.getElementById('modelModal');
            const title = document.getElementById('modalTitle');
            const form = document.getElementById('modelForm');
            const idField = document.getElementById('modelId');
            
            console.log('Elements found:', {
                modal: !!modal,
                title: !!title,
                form: !!form,
                idField: !!idField
            });
            
            if (title) title.textContent = 'Tambah Model';
            if (form) form.reset();
            if (idField) idField.value = '';
            
            // Force show modal with multiple methods
            if (modal) {
                modal.style.display = 'flex';
                modal.style.visibility = 'visible';
                modal.style.opacity = '1';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                modal.classList.remove('hide', 'hidden');
                modal.classList.add('show');
                
                // Fallback: try jQuery
                $(modal).show();
            }
            
            console.log('Modal should be visible now');
        } catch (error) {
            console.error('Error in create handler:', error);
            // Ultimate fallback jQuery method
            $('#modalTitle').text('Tambah Model');
            $('#modelForm')[0].reset();
            $('#modelId').val('');
            $('#modelModal').show().removeClass('hide hidden').addClass('show');
        }
    }

    window.handleInlineSubmit = function() {
        console.log('Inline submit handler called');
        
        const form = document.getElementById('modelForm');
        const formData = new FormData(form);
        const id = document.getElementById('modelId').value;
        let url = storeUrl;
        let method = 'POST';
        let successMessage = 'Data berhasil disimpan';
 
        if (id) {
            url = updateUrlTemplate.replace(':id', id);
            method = 'PUT';
            successMessage = 'Data berhasil diupdate';
        }

        // Add method override for PUT requests
        if (method === 'PUT') {
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: 'POST', // Always use POST with _method override
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            hideModal();
            $('#modelsTable').DataTable().ajax.reload();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: successMessage,
                confirmButtonColor: '#009B77'
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat menyimpan data',
                confirmButtonColor: '#dc2626'
            });
        });
    }

    // Debug: Check if global functions are available
    console.log('Global functions available:', {
        showModal: typeof window.showModal,
        hideModal: typeof window.hideModal,
        handleCreateNewModel: typeof window.handleCreateNewModel,
        handleInlineSubmit: typeof window.handleInlineSubmit
    });

    $(document).ready(function() {
        console.log('Document ready, initializing modal handlers...');
        
        // Test if elements exist
        console.log('Create button exists:', !!document.getElementById('createNewModel'));
        console.log('Close button exists:', !!document.getElementById('closeModalBtn'));
        console.log('Cancel button exists:', !!document.getElementById('cancelBtn'));
        console.log('Submit button exists:', !!document.getElementById('submitModelBtn'));
        console.log('Modal exists:', !!document.getElementById('modelModal'));
        
        // Test button clickability
        setTimeout(function() {
            console.log('Testing button clickability...');
            const buttons = ['createNewModel', 'closeModalBtn', 'cancelBtn', 'submitModelBtn'];
            buttons.forEach(function(id) {
                const btn = document.getElementById(id);
                if (btn) {
                    console.log(`${id} is clickable:`, !btn.disabled);
                    console.log(`${id} has pointer events:`, window.getComputedStyle(btn).pointerEvents);
                }
            });
        }, 1000);
        
        // Force remove hidden class for testing
        setTimeout(function() {
            console.log('Testing modal visibility...');
            const modal = document.getElementById('modelModal');
            if (modal.classList.contains('hidden')) {
                console.log('Modal is hidden, attempting to show...');
                modal.classList.remove('hidden');
                setTimeout(function() {
                    console.log('Modal should be visible now');
                    modal.classList.add('hidden');
                }, 1000);
            }
        }, 2000);
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
                    targets: [2, 3, 4, 5, 6], // Tipe, Material, Tanggal, Kondisi, Lokasi
                    className: 'text-sm text-gray-600'
                },
                {
                    targets: 7, // Aksi
                    className: 'text-center'
                }
            ],
            columns: [
                { data: 'model_code' },
                { data: 'name' },
                { data: 'type' },
                { data: 'material_name' },
                { data: 'manufactured_date' },
                {
                    data: 'condition',
                    render: function(data, type, row) {
                        let badgeClass = 'bg-green-50 text-green-700 px-2 py-1 rounded-full text-xs font-medium';
                        if (data === 'Repair') {
                            badgeClass = 'bg-yellow-50 text-yellow-700 px-2 py-1 rounded-full text-xs font-medium';
                        } else if (data === 'Damaged') {
                            badgeClass = 'bg-red-50 text-red-700 px-2 py-1 rounded-full text-xs font-medium';
                        }
                        return '<span class="' + badgeClass + '">' + (data === 'Good' ? 'Baik' : (data === 'Repair' ? 'Perbaikan' : 'Rusak')) + '</span>';
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
 

        // Create button handler (jQuery)
        $('#createNewModel').on('click', function() {
            console.log('Create button clicked (jQuery)'); // Debug log
            handleCreateNewModel();
        });

        // Fallback vanilla JS handler
        document.getElementById('createNewModel').addEventListener('click', function(e) {
            console.log('Create button clicked (vanilla JS)'); // Debug log
            e.preventDefault();
            e.stopPropagation();
            handleCreateNewModel();
        });
 
        // Close button handlers
        $('#closeModalBtn').on('click', function() {
            console.log('Close button clicked (jQuery)'); // Debug log
            hideModal();
        });

        $('#cancelBtn').on('click', function() {
            console.log('Cancel button clicked (jQuery)'); // Debug log
            hideModal();
        });

        // Fallback close handlers (vanilla JS)
        document.getElementById('closeModalBtn').addEventListener('click', function(e) {
            console.log('Close button clicked (vanilla JS)'); // Debug log
            e.preventDefault();
            e.stopPropagation();
            hideModal();
        });

        document.getElementById('cancelBtn').addEventListener('click', function(e) {
            console.log('Cancel button clicked (vanilla JS)'); // Debug log
            e.preventDefault();
            e.stopPropagation();
            hideModal();
        });

        // Submit button handler
        document.getElementById('submitModelBtn').addEventListener('click', function(e) {
            console.log('Submit button clicked (vanilla JS)'); // Debug log
            e.preventDefault();
            e.stopPropagation();
            
            const form = document.getElementById('modelForm');
            const formData = new FormData(form);
            const id = document.getElementById('modelId').value;
            let url = storeUrl;
            let method = 'POST';
            let successMessage = 'Data berhasil disimpan';
 
            if (id) {
                url = updateUrlTemplate.replace(':id', id);
                method = 'PUT';
                successMessage = 'Data berhasil diupdate';
            }

            // Add method override for PUT requests
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: 'POST', // Always use POST with _method override
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                hideModal();
                $('#modelsTable').DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: successMessage,
                    confirmButtonColor: '#009B77'
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menyimpan data',
                    confirmButtonColor: '#dc2626'
                });
            });
        });

        // Close modal when clicking outside
        document.getElementById('modelModal').addEventListener('click', function(e) {
            if (e.target === this) {
                console.log('Clicked outside modal, closing...'); // Debug log
                hideModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('modelModal');
                if (modal.classList.contains('show')) {
                    console.log('ESC key pressed, closing modal...'); // Debug log
                    hideModal();
                }
            }
        });

        // Simple button testing
        setTimeout(function() {
            console.log('Testing modal functionality...');
            
            // Test modal visibility
            const modal = document.getElementById('modelModal');
            console.log('Modal element:', modal);
            console.log('Modal classes:', modal.className);
            console.log('Modal style:', modal.style.cssText);
            
            // Test buttons exist
            const buttons = {
                create: document.getElementById('createNewModel'),
                close: document.getElementById('closeModalBtn'),
                cancel: document.getElementById('cancelBtn'),
                submit: document.getElementById('submitModelBtn')
            };
            
            Object.keys(buttons).forEach(key => {
                const btn = buttons[key];
                console.log(`${key} button:`, {
                    exists: !!btn,
                    disabled: btn ? btn.disabled : 'N/A',
                    onclick: btn ? (btn.onclick ? 'has onclick' : 'no onclick') : 'N/A',
                    rect: btn ? btn.getBoundingClientRect() : 'N/A'
                });
            });
            
            // Test create button specifically
            const createBtn = document.getElementById('createNewModel');
            if (createBtn) {
                console.log('Testing create button click...');
                createBtn.click();
            }
            
            // Test manual modal show/hide
            setTimeout(function() {
                console.log('Testing manual modal show...');
                showModal();
                setTimeout(function() {
                    console.log('Testing manual modal hide...');
                    hideModal();
                }, 2000);
            }, 1000);
        }, 2000);

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
                    showModal();
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
            success: function(response) {
                if (response.success) {
                    $('#qrCodeContainer').html(response.qrCode);
                    $('#qrCodeText').text('QR Code untuk: ' + response.modelCode);
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

    // QR Modal close handlers
    $('#closeQrModalBtn').on('click', function() {
        $('#qrModal').addClass('hidden');
    });

    // Close modal when clicking outside
    $('#qrModal').on('click', function(e) {
        if (e.target === this) {
            $('#qrModal').addClass('hidden');
        }
    });

    // Close modal with ESC key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#qrModal').addClass('hidden');
        }
    });
    </script>
    @endpush
</x-app-layout>
