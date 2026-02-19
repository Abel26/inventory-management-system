<x-app-layout>
    <x-slot name="title">{{ __('modules.asset_materials.title') }}</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('modules.asset_materials.title') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('modules.asset_materials.subtitle') }}</p>
            </div>
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <!-- Export Dropdown -->
                <div x-data="{ open: false }" class="relative w-full md:w-auto">
                    <button @click="open = !open" @click.outside="open = false" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg inline-flex items-center justify-center gap-2 transition">
                        <i class="ph ph-download-simple text-lg"></i>
                        <span>{{ __('modules.common.export') }}</span>
                        <i class="ph ph-caret-down text-sm" x-show="open" x-transition></i>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                        <a href="{{ route('assets.materials.export') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition">
                            <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                            <span class="text-sm font-medium">{{ __('modules.common.export_excel') }}</span>
                        </a>
                        <a href="{{ route('assets.materials.export-pdf') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition border-t border-gray-100">
                            <i class="ph ph-file-pdf text-lg text-red-600"></i>
                            <span class="text-sm font-medium">{{ __('modules.common.export_pdf') }}</span>
                        </a>
                    </div>
                </div>
                <button type="button" id="createNewMaterial" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-lg inline-flex items-center justify-center gap-2 transition">
                    <i class="ph ph-plus text-lg"></i>
                    <span>{{ __('modules.common.add_data') }}</span>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('modules.asset_materials.total_materials') }}</p>
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
                        <p class="text-sm text-gray-500">{{ __('modules.asset_materials.low_stock') }}</p>
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
                        <p class="text-sm text-gray-500">{{ __('modules.asset_materials.out_of_stock') }}</p>
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
                        <p class="text-sm text-gray-500">{{ __('modules.asset_materials.total_value') }}</p>
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
                    <input type="text" id="searchInput" placeholder="{{ __('modules.asset_materials.search_placeholder') }}" class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="materialsTable" class="w-full" width="100%">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.code') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.asset_materials.asset_name') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.type') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.stock') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.supplier') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.location') }}</th>
                        <th class="px-6 py-4 text-center font-semibold whitespace-nowrap">{{ __('modules.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Data will be loaded by DataTables -->
                </tbody>
            </table>
            
            <!-- Empty State -->
            <div id="emptyState" class="p-12 text-center flex flex-col items-center justify-center hidden">
                <i class="ph ph-magnifying-glass text-5xl mb-4 text-gray-300"></i>
                <p class="text-sm text-gray-500">{{ __('modules.asset_materials.empty_state') }}</p>
            </div>
        </div>

    </div>

    <!-- Add/Edit Modal -->
    <div id="materialModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden" style="display: none;">
        <div class="flex items-center justify-center min-h-screen w-full p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0 modal-content flex flex-col max-h-[90vh]">
                <!-- Modal Header with Gradient -->
                <div class="relative bg-gradient-to-r from-ebara-600 to-ebara-700 rounded-t-2xl p-6 text-white flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="ph ph-package text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold" id="modalTitle">{{ __('modules.asset_materials.add_title') }}</h3>
                                <p class="text-ebara-100 text-sm" id="modalSubtitle">{{ __('modules.asset_materials.add_subtitle') }}</p>
                            </div>
                        </div>
                        <button type="button" id="closeModalBtn" class="text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200 p-2 rounded-lg" tabindex="0" role="button" aria-label="{{ __('modules.common.close_modal') }}">
                            <i class="ph ph-x text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form id="materialForm" class="p-6 space-y-5 overflow-y-auto flex-1">
                    <input type="hidden" id="materialId" name="id">

                    <!-- Kode Material -->
                    <div class="space-y-2">
                        <label for="material_code" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-barcode text-ebara-600 mr-2"></i>
                            {{ __('modules.asset_materials.material_code') }}
                        </label>
                        <input type="text" id="material_code" name="material_code" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400" placeholder="{{ __('modules.asset_materials.code_placeholder') }}">
                    </div>

                    <!-- Nama Material -->
                    <div class="space-y-2">
                        <label for="name" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-package text-ebara-600 mr-2"></i>
                            {{ __('modules.asset_materials.material_name') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" id="name" name="name" required class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400">
                    </div>

                    <!-- Tipe -->
                    <div class="space-y-2">
                        <label for="type" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-tag text-ebara-600 mr-2"></i>
                            {{ __('modules.common.type') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select id="type" name="type" required class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                            <option value="">{{ __('modules.asset_materials.select_type') }}</option>
                            <option value="bahan_baku">{{ __('modules.asset_materials.type_raw_material') }}</option>
                            <option value="komponen">{{ __('modules.asset_materials.type_component') }}</option>
                            <option value="aksesoris">{{ __('modules.asset_materials.type_accessory') }}</option>
                            <option value="lainnya">{{ __('modules.asset_materials.type_other') }}</option>
                        </select>
                    </div>

                    <!-- Jumlah & Satuan -->
                    <x-ui.form-grid columns="2">
                        <div class="space-y-2">
                            <label for="quantity" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-stack text-ebara-600 mr-2"></i>
                                {{ __('modules.common.quantity') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" id="quantity" name="quantity" required min="0" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400">
                        </div>
                        <div class="space-y-2">
                            <label for="unit_id" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-ruler text-ebara-600 mr-2"></i>
                                {{ __('modules.common.unit') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select id="unit_id" name="unit_id" required class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                                <option value="">{{ __('modules.asset_materials.select_unit') }}</option>
                                @foreach($satuans as $satuan)
                                    <option value="{{ $satuan->id }}">{{ $satuan->nama }} ({{ $satuan->kode }})</option>
                                @endforeach
                            </select>
                            <!-- Hidden field untuk backward compatibility -->
                            <input type="hidden" id="unit" name="unit" value="">
                        </div>
                    </x-ui.form-grid>

                    <!-- Min. Stok & Harga Satuan -->
                    <x-ui.form-grid columns="2">
                        <div class="space-y-2">
                            <label for="min_threshold" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-warning text-ebara-600 mr-2"></i>
                                {{ __('modules.asset_materials.min_stock') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" id="min_threshold" name="min_threshold" required min="0" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400">
                        </div>
                        <div class="space-y-2">
                            <label for="unit_price" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-currency-circle-dollar text-ebara-600 mr-2"></i>
                                {{ __('modules.asset_materials.unit_price') }}
                            </label>
                            <input type="number" id="unit_price" name="unit_price" min="0" step="0.01" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400" placeholder="{{ __('modules.common.optional') }}">
                        </div>
                    </x-ui.form-grid>

                    <!-- Supplier -->
                    <div class="space-y-2">
                        <label for="supplier" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-truck text-ebara-600 mr-2"></i>
                            {{ __('modules.common.supplier') }}
                        </label>
                        <input type="text" id="supplier" name="supplier" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400" placeholder="{{ __('modules.common.optional') }}">
                    </div>

                    <!-- Tanggal Masuk & Kadaluarsa -->
                    <x-ui.form-grid columns="2">
                        <div class="space-y-2">
                            <label for="entry_date" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-calendar text-ebara-600 mr-2"></i>
                                {{ __('modules.asset_materials.entry_date') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="date" id="entry_date" name="entry_date" required class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                        </div>
                        <div class="space-y-2">
                            <label for="expiry_date" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-calendar-x text-ebara-600 mr-2"></i>
                                {{ __('modules.asset_materials.expiry_date') }}
                            </label>
                            <input type="date" id="expiry_date" name="expiry_date" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                        </div>
                    </x-ui.form-grid>

                    <!-- Lokasi -->
                    <x-ui.lokasi-gedung-select label="Lokasi" name="location" id="location" :gedungs="$gedungs" />

                    <!-- Deskripsi -->
                    <div class="space-y-2">
                        <label for="description" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-note-pencil text-ebara-600 mr-2"></i>
                            {{ __('modules.common.description') }}
                        </label>
                        <textarea id="description" name="description" rows="3" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400"></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="sticky bottom-0 -mx-6 -mb-6 px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex flex-col sm:flex-row justify-end gap-3 z-10">
                        <button type="button" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-all duration-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200" onclick="closeMaterialModal()">
                            {{ __('modules.common.cancel') }}
                        </button>
                        <button type="button" id="submitMaterialBtn" class="px-5 py-2.5 bg-gradient-to-r from-ebara-600 to-ebara-700 hover:from-ebara-700 hover:to-ebara-800 text-white font-medium rounded-xl shadow-lg shadow-ebara-500/25 hover:shadow-ebara-500/40 transition-all duration-200 text-sm flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-ebara-500" style="pointer-events: auto !important; cursor: pointer !important;">
                            <i class="ph ph-floppy-disk text-lg"></i>
                            {{ __('modules.common.save') }}
                        </button>
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
    <style>
    /* Modal animations fix */
    .modal-content {
        transition: all 0.3s ease;
    }
    
    .modal-content.scale-95 {
        transform: scale(0.95);
        opacity: 0;
    }
    
    .modal-content.scale-100 {
        transform: scale(1);
        opacity: 1;
    }
    
    .modal-content.opacity-0 {
        opacity: 0;
    }
    
    .modal-content.opacity-1 {
        opacity: 1;
    }
    
    /* Ensure buttons are always clickable */
    #submitMaterialBtn {
        pointer-events: auto !important;
        cursor: pointer !important;
    }
    
    #submitMaterialBtn:disabled {
        pointer-events: none !important;
        cursor: not-allowed !important;
    }
    
    /* Fix for aria-hidden conflicts */
    .flex.h-screen[aria-hidden="true"] #submitMaterialBtn {
        pointer-events: auto !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* Ensure SweetAlert doesn't hide our buttons */
    body.swal2-shown:not(.swal2-toast-shown) .flex.h-screen {
        aria-hidden: unset !important;
    }
    </style>
    <script>
    var storeUrl = "{{ route('assets.materials.store') }}";
    var showUrlTemplate = "{{ route('assets.materials.show', ':id') }}";
    var updateUrlTemplate = "{{ route('assets.materials.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('assets.materials.destroy', ':id') }}";
    var qrCodeUrlTemplate = "{{ route('assets.materials.qr-code', ':id') }}";

    // Global helper function untuk format tanggal
    window.formatDateForInput = function(dateString) {
        console.log('FORMAT DATE: Input:', dateString, 'Type:', typeof dateString); // DEBUG
        
        if (!dateString) {
            console.log('FORMAT DATE: Empty date, returning empty string'); // DEBUG
            return '';
        }
        
        // Jika sudah format Y-m-d, gunakan langsung
        if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
            console.log('FORMAT DATE: Already in Y-m-d format:', dateString); // DEBUG
            return dateString;
        }
        
        // Handle format datetime dari Laravel (Y-m-d H:i:s)
        if (dateString.match(/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/)) {
            const dateOnly = dateString.split(' ')[0];
            console.log('FORMAT DATE: Laravel datetime format, extracting date:', dateOnly); // DEBUG
            return dateOnly;
        }
        
        // Konversi dari format lain ke Y-m-d
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                console.log('FORMAT DATE: Invalid date, returning empty string'); // DEBUG
                return '';
            }
            
            // Konversi ke timezone lokal
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            
            const result = `${year}-${month}-${day}`;
            console.log('FORMAT DATE: Converted to:', result); // DEBUG
            return result;
        } catch (e) {
            console.error('FORMAT DATE: Error parsing date:', dateString, e);
            return '';
        }
    };

    $(document).ready(function() {
        console.log('DOCUMENT READY: Asset materials page loaded'); // DEBUG
        console.log('DOCUMENT READY: jQuery version:', $.fn.jquery); // DEBUG
        console.log('DOCUMENT READY: DataTable available:', typeof $.fn.DataTable !== 'undefined'); // DEBUG
        console.log('DOCUMENT READY: Swal available:', typeof Swal !== 'undefined'); // DEBUG
        
        // Fix for production aria-hidden conflicts
        if (typeof Swal !== 'undefined') {
            // Override SweetAlert's default behavior to prevent aria-hidden conflicts
            Swal.mixin({
                didOpen: function() {
                    // Remove aria-hidden from main container when SweetAlert opens
                    $('.flex.h-screen').removeAttr('aria-hidden');
                    console.log('PRODUCTION FIX: Removed aria-hidden from main container');
                }
            });
        }
        
        // Global fix for any dynamically added aria-hidden
        setInterval(function() {
            if ($('#submitMaterialBtn').is(':visible') && $('.flex.h-screen').attr('aria-hidden') === 'true') {
                $('.flex.h-screen').removeAttr('aria-hidden');
                console.log('PRODUCTION FIX: Auto-removed aria-hidden conflict');
            }
        }, 1000);
        
        // Check if buttons exist on page load
        console.log('DOCUMENT READY: Submit button exists:', $('#submitMaterialBtn').length > 0);
        console.log('DOCUMENT READY: Submit button events on load:', $._data($('#submitMaterialBtn')[0], 'events'));
        
        // Add global error handler
        window.addEventListener('error', function(e) {
            console.error('GLOBAL ERROR:', e.error);
        });
        
        // Add jQuery AJAX error handler
        $(document).ajaxError(function(event, xhr, settings, error) {
            console.error('AJAX ERROR:', {
                url: settings.url,
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText
            });
        });
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
                        // Tampilkan nama satuan dari relasi, fallback ke unit lama
                        let unitName = row.satuan ? row.satuan.nama : row.unit;
                        return '<span class="' + badgeClass + '">' + data + ' ' + unitName + '</span>';
                    }
                },
                { data: 'supplier' },
                { data: 'location' },
                {
                    data: 'actions',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewQrCode(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="QR Code" style="pointer-events: auto !important;">
                                    <i class="ph ph-qr-code text-xl"></i>
                                </button>
                                <button onclick="editMaterial(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="Edit" style="pointer-events: auto !important;">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteMaterial(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus" style="pointer-events: auto !important;">
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

        table.on('xhr', function() {
            let json = table.ajax.json();
            if (json) {
                $('#totalMaterials').text(json.stats.total || 0);
                $('#lowStock').text(json.stats.lowStock || 0);
                $('#outOfStock').text(json.stats.outOfStock || 0);
                $('#totalValue').text('Rp ' + (json.stats.totalValue || 0).toLocaleString('id-ID'));
            }
        });

    // Global Modal helpers
    window.openMaterialModal = function() {
        var modal = document.getElementById('materialModal');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        
        // Force reflow to ensure transition works
        modal.offsetHeight;
        
        requestAnimationFrame(function() {
            var content = modal.querySelector('.modal-content');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }
    
    window.closeMaterialModal = function() {
        var modal = document.getElementById('materialModal');
        var content = modal.querySelector('.modal-content');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(function() {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            // Reset form when modal is fully closed
            document.getElementById('materialForm').reset();
            document.getElementById('materialId').value = '';
        }, 300);
    }


        $('#createNewMaterial').on('click', function() {
            $('#modalTitle').text('{{ __('modules.asset_materials.add_title') }}');
            $('#modalSubtitle').text('{{ __('modules.asset_materials.add_subtitle') }}');
            $('#materialForm')[0].reset();
            $('#materialId').val('');
            openMaterialModal();
        });

        $('#closeModalBtn').on('click', function() {
            closeMaterialModal();
        });

        $('#cancelBtn').on('click', function() {
            closeMaterialModal();
        });

        $('#closeQrModalBtn').on('click', function() {
            $('#qrModal').addClass('hidden');
        });

        // Sync dropdown satuan dengan hidden field unit
        $('#unit_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var unitText = selectedOption.text();
            // Extract nama satuan (sebelum kurung)
            var unitName = unitText.split(' (')[0];
            $('#unit').val(unitName);
        });
    });

    function editMaterial(id) {
        console.log('EDIT MATERIAL: Starting edit for ID:', id); // DEBUG
        
        // Check if submit button exists and has event handler
        console.log('EDIT MATERIAL: Submit button exists:', $('#submitMaterialBtn').length > 0);
        console.log('EDIT MATERIAL: Submit button events:', $._data($('#submitMaterialBtn')[0], 'events'));
        
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                console.log('EDIT MATERIAL: Response from server:', response); // Debug log
                if (response.success) {
                    const data = response.data;
                    console.log('EDIT MATERIAL: Material data:', data); // Debug log
                    
                    // Debug tanggal dengan lebih detail
                    console.log('EDIT MATERIAL: Entry date raw:', data.entry_date, 'Type:', typeof data.entry_date);
                    console.log('EDIT MATERIAL: Expiry date raw:', data.expiry_date, 'Type:', typeof data.expiry_date);
                    
                    // Test fungsi formatDateForInput
                    console.log('EDIT MATERIAL: formatDateForInput(entry_date):', formatDateForInput(data.entry_date));
                    console.log('EDIT MATERIAL: formatDateForInput(expiry_date):', formatDateForInput(data.expiry_date));
                    
                    $('#modalTitle').text('{{ __('modules.asset_materials.edit_title') }}');
                    $('#modalSubtitle').text('{{ __('modules.asset_materials.edit_subtitle') }}');
                    $('#materialId').val(data.id);
                    $('#material_code').val(data.material_code);
                    $('#name').val(data.name);
                    $('#type').val(data.type);
                    $('#quantity').val(data.quantity);
                    $('#unit_id').val(data.unit_id);
                    $('#unit').val(data.unit); // Untuk backward compatibility
                    $('#min_threshold').val(data.min_threshold);
                    $('#unit_price').val(data.unit_price);
                    $('#supplier').val(data.supplier);
                    
                    // Perbaikan untuk tanggal - menggunakan fungsi helper global
                    const formattedEntryDate = formatDateForInput(data.entry_date);
                    const formattedExpiryDate = formatDateForInput(data.expiry_date);
                    
                    console.log('EDIT MATERIAL: Setting entry_date to:', formattedEntryDate);
                    console.log('EDIT MATERIAL: Setting expiry_date to:', formattedExpiryDate);
                    
                    $('#entry_date').val(formattedEntryDate);
                    $('#expiry_date').val(formattedExpiryDate);
                    
                    $('#location').val(data.location).trigger('change');
                    $('#description').val(data.description);
                    
                    // Debug final values
                    console.log('EDIT MATERIAL: Final entry_date value:', $('#entry_date').val());
                    console.log('EDIT MATERIAL: Final expiry_date value:', $('#expiry_date').val());
                    
                    // Check modal visibility and button states
                    console.log('EDIT MATERIAL: About to open modal');
                    openMaterialModal();
                    
                    // Check if buttons are visible and enabled after modal opens
                    setTimeout(function() {
                        console.log('EDIT MATERIAL: Modal visible?', !$('#materialModal').hasClass('hidden'));
                        console.log('EDIT MATERIAL: Submit button visible?', $('#submitMaterialBtn').is(':visible'));
                        console.log('EDIT MATERIAL: Submit button enabled?', $('#submitMaterialBtn').is(':enabled'));
                        console.log('EDIT MATERIAL: Submit button disabled?', $('#submitMaterialBtn').prop('disabled'));
                        
                        // Test click event on submit button
                        $('#submitMaterialBtn').off('click.test').on('click.test', function() {
                            console.log('EDIT MATERIAL: Submit button click test - EVENT FIRED!');
                        });
                    }, 500);
                    
                } else {
                    console.error('EDIT MATERIAL: Server returned error:', response.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonColor: '#dc2626'
                    });
                }
            },
            error: function(xhr) {
                console.error('EDIT MATERIAL: Error fetching material:', xhr); // Debug log
                console.error('EDIT MATERIAL: Response text:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ __('modules.asset_materials.fetch_error') }}',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    // Form submission is handled by the click handler below


    // Also add click handler as backup with confirmation
    $('#submitMaterialBtn').on('click', function(e) {
        e.preventDefault();
        console.log('SUBMIT BUTTON: Click event fired!'); // DEBUG
        console.log('SUBMIT BUTTON: Button disabled:', $(this).prop('disabled')); // DEBUG
        console.log('SUBMIT BUTTON: Button visible:', $(this).is(':visible')); // DEBUG
        console.log('SUBMIT BUTTON: Form data before submission:', $('#materialForm').serialize()); // DEBUG
        
        // Fix aria-hidden conflict by removing focus before SweetAlert
        $(this).blur();
        
        let id = $('#materialId').val();
        let isEdit = id !== '';
        
        console.log('SUBMIT BUTTON: Form ID:', id, 'Is Edit:', isEdit); // DEBUG
        console.log('SUBMIT BUTTON: CSRF Token:', $('meta[name="csrf-token"]').attr('content')); // DEBUG
        
        // Show confirmation dialog for both create and edit
        let confirmTitle = isEdit ? '{{ __('modules.swal.confirm_title') }}' : '{{ __('modules.swal.confirm_title') }}';
        let confirmText = isEdit ? '{{ __('modules.swal.update_warning') }}' : '{{ __('modules.asset_materials.confirm_create') }}';
        
        // Store reference to button for later focus restoration
        var submitBtn = this;
        
        Swal.fire({
            title: confirmTitle,
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#009B77',
            cancelButtonColor: '#6b7280',
            confirmButtonText: isEdit ? '{{ __('modules.swal.yes_save') }}' : '{{ __('modules.asset_materials.yes_create') }}',
            cancelButtonText: '{{ __('modules.swal.cancel') }}',
            // Fix for production timing issues
            didOpen: function() {
                // Ensure proper focus management in SweetAlert
                console.log('SWAL: SweetAlert opened, fixing focus management');
                // Remove any aria-hidden conflicts
                $('.flex.h-screen').removeAttr('aria-hidden');
            },
            didClose: function() {
                // Restore focus after SweetAlert closes
                console.log('SWAL: SweetAlert closed, restoring focus');
                setTimeout(function() {
                    if (submitBtn && $(submitBtn).is(':visible')) {
                        submitBtn.focus();
                    }
                }, 100);
            }
        }).then((result) => {
            console.log('SUBMIT BUTTON: Swal result:', result); // DEBUG
            if (result.isConfirmed) {
                console.log('SUBMIT BUTTON: User confirmed, calling handleFormSubmission()'); // DEBUG
                // Trigger form submission manually instead of using submit()
                try {
                    handleFormSubmission();
                } catch (error) {
                    console.error('SUBMIT BUTTON: Error in handleFormSubmission():', error); // DEBUG
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memproses form: ' + error.message,
                        confirmButtonColor: '#dc2626'
                    });
                }
            } else {
                console.log('SUBMIT BUTTON: User cancelled submission'); // DEBUG
            }
        });
    });
    
    // Extract form submission logic to separate function
    function handleFormSubmission() {
        console.log('FORM SUBMISSION: Starting form submission'); // DEBUG
        console.log('FORM SUBMISSION: Function exists, typeof handleFormSubmission:', typeof handleFormSubmission); // DEBUG
        
        try {
            // Additional validation for expiry_date
            let entryDate = $('#entry_date').val();
            let expiryDate = $('#expiry_date').val();
            
            console.log('FORM SUBMISSION: Date validation - Entry:', entryDate, 'Expiry:', expiryDate); // DEBUG
            
            // Validate expiry date if provided
            if (expiryDate && expiryDate !== '') {
                if (entryDate && entryDate !== '') {
                    if (new Date(expiryDate) < new Date(entryDate)) {
                        console.log('FORM SUBMISSION: Date validation failed'); // DEBUG
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Tanggal kadaluarsa harus setelah atau sama dengan tanggal masuk',
                            confirmButtonColor: '#dc2626'
                        });
                        $('#expiry_date').addClass('border-red-500');
                        setTimeout(() => {
                            $('#expiry_date').removeClass('border-red-500');
                        }, 3000);
                        return false;
                    }
                }
            }
            
            // Enhanced form validation
            let isValid = true;
            let validationMessage = '';
            
            console.log('FORM SUBMISSION: Starting field validation'); // DEBUG
            
            // Check required fields
            if (!$('#name').val().trim()) {
                isValid = false;
                validationMessage = '{{ __('modules.asset_materials.name_required') }}';
                console.log('FORM SUBMISSION: Name validation failed'); // DEBUG
            } else if (!$('#type').val()) {
                isValid = false;
                validationMessage = '{{ __('modules.asset_materials.type_required') }}';
                console.log('FORM SUBMISSION: Type validation failed'); // DEBUG
            } else if (!$('#quantity').val() || $('#quantity').val() < 0) {
                isValid = false;
                validationMessage = '{{ __('modules.asset_materials.quantity_invalid') }}';
                console.log('FORM SUBMISSION: Quantity validation failed'); // DEBUG
            } else if (!$('#unit_id').val()) {
                isValid = false;
                validationMessage = '{{ __('modules.asset_materials.unit_required') }}';
                console.log('FORM SUBMISSION: Unit validation failed'); // DEBUG
            } else if (!$('#min_threshold').val() || $('#min_threshold').val() < 0) {
                isValid = false;
                validationMessage = '{{ __('modules.asset_materials.min_threshold_invalid') }}';
                console.log('FORM SUBMISSION: Min threshold validation failed'); // DEBUG
            } else if (!$('#entry_date').val()) {
                isValid = false;
                validationMessage = '{{ __('modules.asset_materials.entry_date_required') }}';
                console.log('FORM SUBMISSION: Entry date validation failed'); // DEBUG
            }
            
            console.log('FORM SUBMISSION: Validation result:', isValid, 'Message:', validationMessage); // DEBUG
            
            if (!isValid) {
                console.log('FORM SUBMISSION: Showing validation error'); // DEBUG
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('modules.asset_materials.validation_failed') }}',
                    text: validationMessage,
                    confirmButtonColor: '#dc2626'
                });
                return false;
            }
            
            // Basic HTML5 form validation as fallback
            if (!$('#materialForm')[0].checkValidity()) {
                console.log('FORM SUBMISSION: HTML5 validation failed'); // DEBUG
                // If HTML5 validation fails, trigger browser validation UI
                $('#materialForm')[0].reportValidity();
                return false;
            }
            
            let formData = $('#materialForm').serialize();
            let id = $('#materialId').val();
            let url = storeUrl;
            let method = 'POST';
            let successMessage = '{{ __('modules.swal.data_saved') }}';

            if (id) {
                url = updateUrlTemplate.replace(':id', id);
                // Add _method field for Laravel method spoofing
                formData += '&_method=PUT';
                method = 'POST'; // Always use POST with _method field
                successMessage = '{{ __('modules.swal.data_updated') }}';
            }

            console.log('FORM SUBMISSION: Preparing AJAX request'); // DEBUG
            console.log('FORM SUBMISSION: URL:', url); // DEBUG
            console.log('FORM SUBMISSION: Method:', method); // DEBUG
            console.log('FORM SUBMISSION: Form data:', formData); // DEBUG
            console.log('FORM SUBMISSION: CSRF Token:', '{{ csrf_token() }}'); // DEBUG

            // Disable submit button to prevent double submission
            $('#submitMaterialBtn').prop('disabled', true).html('<i class="ph ph-spinner-gap animate-spin text-lg"></i> {{ __('modules.common.saving') }}');
            
            console.log('FORM SUBMISSION: Button disabled, sending AJAX request'); // DEBUG

            $.ajax({
                url: url,
                method: method,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                timeout: 30000, // 30 seconds timeout
                beforeSend: function(xhr) {
                    console.log('FORM SUBMISSION: AJAX beforeSend triggered'); // DEBUG
                },
                success: function(response) {
                    console.log('FORM SUBMISSION: AJAX success callback triggered'); // DEBUG
                    console.log('FORM SUBMISSION: Server response:', response); // DEBUG
                    closeMaterialModal();
                    
                    // Reload DataTable with a small delay to ensure server has processed the update
                    setTimeout(function() {
                        $('#materialsTable').DataTable().ajax.reload(null, false); // false = keep current page
                        console.log('FORM SUBMISSION: DataTable reloaded');
                    }, 500);
                    
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __('modules.swal.success') }}',
                        text: successMessage,
                        confirmButtonColor: '#009B77',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                    // Reset form and re-enable button
                    $('#materialForm')[0].reset();
                    // Reset unit_id dropdown
                    $('#unit_id').val('');
                    $('#unit').val('');
                    $('#submitMaterialBtn').prop('disabled', false).html('<i class="ph ph-floppy-disk text-lg"></i> {{ __('modules.common.save') }}');
                },
                error: function(xhr) {
                    console.log('FORM SUBMISSION: AJAX error callback triggered'); // DEBUG
                    console.error('FORM SUBMISSION: AJAX error:', xhr); // DEBUG
                    console.error('FORM SUBMISSION: Status:', xhr.status); // DEBUG
                    console.error('FORM SUBMISSION: Status text:', xhr.statusText); // DEBUG
                    console.error('FORM SUBMISSION: Response text:', xhr.responseText); // DEBUG
                    console.error('FORM SUBMISSION: Response JSON:', xhr.responseJSON); // DEBUG
                    
                    let errors = xhr.responseJSON?.errors;
                    let errorMessage = '';
                    if (errors) {
                        for (let key in errors) {
                            errorMessage += errors[key][0] + '\n';
                        }
                    } else {
                        errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#dc2626'
                    });
                    // Re-enable button on error
                    $('#submitMaterialBtn').prop('disabled', false).html('<i class="ph ph-floppy-disk text-lg"></i> {{ __('modules.common.save') }}');
                },
                complete: function(xhr) {
                    console.log('FORM SUBMISSION: AJAX complete callback triggered'); // DEBUG
                }
            });
        } catch (error) {
            console.error('FORM SUBMISSION: Exception caught:', error); // DEBUG
            console.error('FORM SUBMISSION: Error stack:', error.stack); // DEBUG
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem: ' + error.message,
                confirmButtonColor: '#dc2626'
            });
            // Re-enable button on error
            $('#submitMaterialBtn').prop('disabled', false).html('<i class="ph ph-floppy-disk text-lg"></i> {{ __('modules.common.save') }}');
        }
    }

    function deleteMaterial(id) {
        console.log('DELETE MATERIAL: Starting delete for ID:', id); // DEBUG
        
        // Check if delete buttons are working
        console.log('DELETE MATERIAL: Swal available:', typeof Swal !== 'undefined');
        console.log('DELETE MATERIAL: jQuery available:', typeof $ !== 'undefined');
        
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
            console.log('DELETE MATERIAL: Swal result:', result); // DEBUG
            if (result.isConfirmed) {
                console.log('DELETE MATERIAL: Confirmed, sending AJAX request'); // DEBUG
                $.ajax({
                    url: destroyUrlTemplate.replace(':id', id),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('DELETE MATERIAL: Delete successful:', response); // DEBUG
                        $('#materialsTable').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __('modules.swal.success') }}',
                            text: '{{ __('modules.swal.data_deleted') }}',
                            confirmButtonColor: '#009B77'
                        });
                    },
                    error: function(xhr) {
                        console.error('DELETE MATERIAL: Delete failed:', xhr); // DEBUG
                        console.error('DELETE MATERIAL: Response text:', xhr.responseText);
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
