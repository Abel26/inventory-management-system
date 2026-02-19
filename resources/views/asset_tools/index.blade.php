<x-app-layout>
    <x-slot name="title">{{ __('modules.asset_tools.title') }}</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('modules.asset_tools.title') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('modules.asset_tools.subtitle') }}</p>
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
                        <a href="{{ route('assets.tools.export') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition">
                            <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                            <span class="text-sm font-medium">{{ __('modules.common.export_excel') }}</span>
                        </a>
                        <a href="{{ route('assets.tools.export-pdf') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition border-t border-gray-100">
                            <i class="ph ph-file-pdf text-lg text-red-600"></i>
                            <span class="text-sm font-medium">{{ __('modules.common.export_pdf') }}</span>
                        </a>
                    </div>
                </div>
                <button type="button" id="createNewTool" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
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
                        <p class="text-sm text-gray-500">{{ __('modules.asset_tools.total_tools') }}</p>
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
                        <p class="text-sm text-gray-500">{{ __('modules.asset_tools.good_condition') }}</p>
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
                        <p class="text-sm text-gray-500">{{ __('modules.asset_tools.repair') }}</p>
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
                        <p class="text-sm text-gray-500">{{ __('modules.asset_tools.damaged') }}</p>
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
                    <input type="text" id="searchInput" placeholder="{{ __('modules.asset_tools.search_placeholder') }}" class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="toolsTable" class="w-full" width="100%">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.code') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.name') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.category') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.brand') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.type') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.year') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.quantity') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.condition') }}</th>
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
                <p class="text-sm text-gray-500">{{ __('modules.asset_tools.empty_state') }}</p>
            </div>
        </div>
 
    </div>
 
    <!-- Add/Edit Modal -->
    <div id="toolModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden" style="display: none;">
        <div class="flex items-center justify-center min-h-screen w-full p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0 modal-content flex flex-col max-h-[90vh]">
                <!-- Modal Header with Gradient -->
                <div class="relative bg-gradient-to-r from-ebara-600 to-ebara-700 rounded-t-2xl p-6 text-white flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="ph ph-wrench text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold" id="modalTitle">{{ __('modules.asset_tools.add_title') }}</h3>
                                <p class="text-ebara-100 text-sm" id="modalSubtitle">{{ __('modules.asset_tools.add_subtitle') }}</p>
                            </div>
                        </div>
                        <button type="button" id="closeModalBtn" class="text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200 p-2 rounded-lg" tabindex="0" role="button" aria-label="{{ __('modules.common.close_modal') }}">
                            <i class="ph ph-x text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form id="toolForm" class="p-6 space-y-5 overflow-y-auto flex-1" novalidate>
                    <input type="hidden" id="toolId" name="id">

                    <!-- Kode Alat -->
                    <div class="space-y-2">
                        <label for="tool_code" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-barcode text-ebara-600 mr-2"></i>
                            {{ __('modules.asset_tools.tool_code') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" id="tool_code" name="tool_code" required class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400" placeholder="{{ __('modules.asset_tools.code_placeholder') }}">
                    </div>

                    <!-- Nama Alat -->
                    <div class="space-y-2">
                        <label for="name" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-wrench text-ebara-600 mr-2"></i>
                            {{ __('modules.asset_tools.tool_name') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" id="name" name="name" required class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400">
                    </div>

                    <!-- Kategori -->
                    <div class="space-y-2">
                        <label for="category" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-tag text-ebara-600 mr-2"></i>
                            {{ __('modules.common.category') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select id="category" name="category" required class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                            <option value="">{{ __('modules.asset_tools.select_category') }}</option>
                            <option value="hand_tools">{{ __('modules.asset_tools.category_hand_tools') }}</option>
                            <option value="power_tools">{{ __('modules.asset_tools.category_power_tools') }}</option>
                            <option value="measuring">{{ __('modules.asset_tools.category_measuring') }}</option>
                            <option value="other">{{ __('modules.asset_tools.category_other') }}</option>
                        </select>
                    </div>

                    <!-- Merk & Tipe -->
                    <x-ui.form-grid columns="2">
                        <div class="space-y-2">
                            <label for="brand" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-trademark-registered text-ebara-600 mr-2"></i>
                                {{ __('modules.common.brand') }}
                            </label>
                            <input type="text" id="brand" name="brand" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400" placeholder="{{ __('modules.asset_tools.brand_placeholder') }}">
                        </div>
                        <div class="space-y-2">
                            <label for="type" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-cube text-ebara-600 mr-2"></i>
                                {{ __('modules.common.type') }}
                            </label>
                            <input type="text" id="type" name="type" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400" placeholder="{{ __('modules.asset_tools.type_placeholder') }}">
                        </div>
                    </x-ui.form-grid>

                    <!-- Tanggal Pembelian & Harga -->
                    <x-ui.form-grid columns="2">
                        <div class="space-y-2">
                            <label for="purchase_date" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-calendar text-ebara-600 mr-2"></i>
                                {{ __('modules.asset_tools.purchase_date') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="date" id="purchase_date" name="purchase_date" required class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                        </div>
                        <div class="space-y-2">
                            <label for="purchase_price" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-currency-circle-dollar text-ebara-600 mr-2"></i>
                                {{ __('modules.asset_tools.purchase_price') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" id="purchase_price" name="purchase_price" required min="0" step="0.01" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400" placeholder="{{ __('modules.asset_tools.price_placeholder') }}">
                        </div>
                    </x-ui.form-grid>

                    <!-- Lokasi & Jumlah -->
                    <x-ui.form-grid columns="2">
                        <x-ui.lokasi-gedung-select label="Lokasi" name="location" id="location" :gedungs="$gedungs" />
                        <div class="space-y-2">
                            <label for="quantity" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-stack text-ebara-600 mr-2"></i>
                                {{ __('modules.common.quantity') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="number" id="quantity" name="quantity" required min="1" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400" placeholder="{{ __('modules.asset_tools.quantity_placeholder') }}">
                        </div>
                    </x-ui.form-grid>

                    <!-- Kondisi -->
                    <div class="space-y-2">
                        <label for="condition" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-heart-half text-ebara-600 mr-2"></i>
                            {{ __('modules.common.condition') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select id="condition" name="condition" required class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                            <option value="">{{ __('modules.common.select_condition') }}</option>
                            <option value="Good">{{ __('modules.common.condition_good') }}</option>
                            <option value="Repair">{{ __('modules.common.condition_repair') }}</option>
                            <option value="Damaged">{{ __('modules.common.condition_damaged') }}</option>
                            <option value="Disposed">{{ __('modules.common.condition_disposed') }}</option>
                        </select>
                    </div>

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
                        <button type="button" id="cancelBtn" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-all duration-200 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200">
                            {{ __('modules.common.cancel') }}
                        </button>
                        <button type="button" id="submitToolBtn" class="px-5 py-2.5 bg-gradient-to-r from-ebara-600 to-ebara-700 hover:from-ebara-700 hover:to-ebara-800 text-white font-medium rounded-xl shadow-lg shadow-ebara-500/25 hover:shadow-ebara-500/40 transition-all duration-200 text-sm flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-ebara-500" style="pointer-events: auto !important; cursor: pointer !important;">
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
    #submitToolBtn {
        pointer-events: auto !important;
        cursor: pointer !important;
    }
    
    #submitToolBtn:disabled {
        pointer-events: none !important;
        cursor: not-allowed !important;
    }
    
    /* Fix for aria-hidden conflicts */
    .flex.h-screen[aria-hidden="true"] #submitToolBtn {
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
    var storeUrl = "{{ route('assets.tools.store') }}";
    var showUrlTemplate = "{{ route('assets.tools.show', ':id') }}";
    var updateUrlTemplate = "{{ route('assets.tools.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('assets.tools.destroy', ':id') }}";
    var qrCodeUrlTemplate = "{{ route('assets.tools.qr-code', ':id') }}";

    // Global helper function untuk format tanggal
    window.formatDateForInput = function(dateString) {
        console.log('TOOLS FORMAT DATE: Input:', dateString, 'Type:', typeof dateString); // DEBUG
        
        if (!dateString) {
            console.log('TOOLS FORMAT DATE: Empty date, returning empty string'); // DEBUG
            return '';
        }
        
        // Jika sudah format Y-m-d, gunakan langsung
        if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
            console.log('TOOLS FORMAT DATE: Already in Y-m-d format:', dateString); // DEBUG
            return dateString;
        }
        
        // Handle format datetime dari Laravel (Y-m-d H:i:s)
        if (dateString.match(/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/)) {
            const dateOnly = dateString.split(' ')[0];
            console.log('TOOLS FORMAT DATE: Laravel datetime format, extracting date:', dateOnly); // DEBUG
            return dateOnly;
        }
        
        // Konversi dari format lain ke Y-m-d
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                console.log('TOOLS FORMAT DATE: Invalid date, returning empty string'); // DEBUG
                return '';
            }
            
            // Konversi ke timezone lokal
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            
            const result = `${year}-${month}-${day}`;
            console.log('TOOLS FORMAT DATE: Converted to:', result); // DEBUG
            return result;
        } catch (e) {
            console.error('TOOLS FORMAT DATE: Error parsing date:', dateString, e);
            return '';
        }
    };
 
    $(document).ready(function() {
        console.log('TOOLS DOCUMENT READY: Asset tools page loaded'); // DEBUG
        console.log('TOOLS DOCUMENT READY: jQuery version:', $.fn.jquery); // DEBUG
        console.log('TOOLS DOCUMENT READY: DataTable available:', typeof $.fn.DataTable !== 'undefined'); // DEBUG
        console.log('TOOLS DOCUMENT READY: Swal available:', typeof Swal !== 'undefined'); // DEBUG
        
        // Check if buttons exist on page load
        console.log('TOOLS DOCUMENT READY: Submit button exists:', $('#submitToolBtn').length > 0);
        console.log('TOOLS DOCUMENT READY: Submit button events on load:', $._data($('#submitToolBtn')[0], 'events'));
        
        // Fix for production aria-hidden conflicts
        if (typeof Swal !== 'undefined') {
            // Override SweetAlert's default behavior to prevent aria-hidden conflicts
            Swal.mixin({
                didOpen: function() {
                    // Remove aria-hidden from main container when SweetAlert opens
                    $('.flex.h-screen').removeAttr('aria-hidden');
                    console.log('TOOLS PRODUCTION FIX: Removed aria-hidden from main container');
                }
            });
        }
        
        // Global fix for any dynamically added aria-hidden
        setInterval(function() {
            if ($('#submitToolBtn').is(':visible') && $('.flex.h-screen').attr('aria-hidden') === 'true') {
                $('.flex.h-screen').removeAttr('aria-hidden');
                console.log('TOOLS PRODUCTION FIX: Auto-removed aria-hidden conflict');
            }
        }, 1000);
        
        // Add global error handler
        window.addEventListener('error', function(e) {
            console.error('TOOLS GLOBAL ERROR:', e.error);
        });
        
        // Add jQuery AJAX error handler
        $(document).ajaxError(function(event, xhr, settings, error) {
            console.error('TOOLS AJAX ERROR:', {
                url: settings.url,
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText
            });
        });
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
                                <button onclick="viewQrCode(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="QR Code" style="pointer-events: auto !important;">
                                    <i class="ph ph-qr-code text-xl"></i>
                                </button>
                                <button onclick="editTool(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="Edit" style="pointer-events: auto !important;">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteTool(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus" style="pointer-events: auto !important;">
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
                $('#totalTools').text(json.stats.total || 0);
                $('#goodCondition').text(json.stats.good || 0);
                $('#repairCondition').text(json.stats.repair || 0);
                $('#damagedCondition').text(json.stats.damaged || 0);
            }
        });
 
    // Global Modal helpers
    window.openToolModal = function() {
        console.log('TOOLS MODAL: Opening modal'); // DEBUG
        var modal = document.getElementById('toolModal');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        
        // Force reflow to ensure transition works
        modal.offsetHeight;
        
        requestAnimationFrame(function() {
            var content = modal.querySelector('.modal-content');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
            
            console.log('TOOLS MODAL: Modal opened, checking buttons'); // DEBUG
            
            // Ensure buttons are enabled and visible
            setTimeout(function() {
                var submitBtn = document.getElementById('submitToolBtn');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.style.display = 'flex';
                    console.log('TOOLS MODAL: Submit button enabled and visible'); // DEBUG
                }
            }, 100);
        });
    }
    
    window.closeToolModal = function() {
        console.log('TOOLS MODAL: Closing modal'); // DEBUG
        var modal = document.getElementById('toolModal');
        var content = modal.querySelector('.modal-content');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(function() {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }, 300);
    }


        $('#createNewTool').on('click', function() {
            $('#modalTitle').text('{{ __('modules.asset_tools.add_title') }}');
            $('#modalSubtitle').text('{{ __('modules.asset_tools.add_subtitle') }}');
            $('#toolForm')[0].reset();
            $('#toolId').val('');
            openToolModal();
        });
 
        $('#closeModalBtn').on('click', function() {
            closeToolModal();
        });
 
        $('#cancelBtn').on('click', function() {
            closeToolModal();
        });
 
        $('#closeQrModalBtn').on('click', function() {
            $('#qrModal').addClass('hidden');
        });
    });
 
    function editTool(id) {
        console.log('TOOLS EDIT: Starting edit for ID:', id); // DEBUG
        
        // Check if submit button exists and has event handler
        console.log('TOOLS EDIT: Submit button exists:', $('#submitToolBtn').length > 0);
        console.log('TOOLS EDIT: Submit button events:', $._data($('#submitToolBtn')[0], 'events'));
        
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                console.log('TOOLS EDIT: Response from server:', response); // DEBUG
                if (response.success) {
                    const data = response.data;
                    console.log('TOOLS EDIT: Tool data:', data); // DEBUG
                    
                    // Debug tanggal dengan lebih detail
                    console.log('TOOLS EDIT: Purchase date raw:', data.purchase_date, 'Type:', typeof data.purchase_date);
                    
                    // Test fungsi formatDateForInput
                    console.log('TOOLS EDIT: formatDateForInput(purchase_date):', formatDateForInput(data.purchase_date));
                    
                    $('#modalTitle').text('{{ __('modules.asset_tools.edit_title') }}');
                    $('#modalSubtitle').text('{{ __('modules.asset_tools.edit_subtitle') }}');
                    $('#toolId').val(data.id);
                    $('#tool_code').val(data.tool_code);
                    $('#name').val(data.name);
                    $('#category').val(data.category);
                    $('#brand').val(data.brand);
                    $('#type').val(data.type);
                    
                    // Perbaikan untuk tanggal - menggunakan fungsi helper global
                    const formattedPurchaseDate = formatDateForInput(data.purchase_date);
                    console.log('TOOLS EDIT: Setting purchase_date to:', formattedPurchaseDate);
                    
                    // Force set tanggal dengan multiple approaches
                    $('#purchase_date').val(formattedPurchaseDate);
                    
                    // Additional force set untuk memastikan tanggal terisi
                    setTimeout(function() {
                        $('#purchase_date').val(formattedPurchaseDate).trigger('change');
                        console.log('TOOLS EDIT: Forced purchase_date value after timeout:', $('#purchase_date').val());
                    }, 100);
                    
                    $('#purchase_price').val(data.purchase_price);
                    $('#quantity').val(data.quantity);
                    $('#location').val(data.location).trigger('change');
                    $('#condition').val(data.condition);
                    $('#description').val(data.description);
                    
                    // Debug final values
                    console.log('TOOLS EDIT: Final purchase_date value:', $('#purchase_date').val());
                    
                    // Check modal visibility and button states
                    console.log('TOOLS EDIT: About to open modal');
                    openToolModal();
                    

                    
                } else {
                    console.error('TOOLS EDIT: Server returned error:', response.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonColor: '#dc2626'
                    });
                }
            },
            error: function(xhr) {
                console.error('TOOLS EDIT: Error fetching tool:', xhr); // DEBUG
                console.error('TOOLS EDIT: Response text:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ __('modules.asset_tools.fetch_error') }}',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }
 
    // Handle form submission (works for both click and Enter key)
    $('#toolForm').on('submit', function(e) {
        e.preventDefault();
        console.log('Form submit triggered');
        
        // Basic HTML5 validation check
        if (!this.checkValidity()) {
            // If HTML5 validation fails, trigger browser validation UI and return
            // The browser will automatically show the validation messages
            return;
        }
        
        let id = $('#toolId').val();
        let isEdit = id !== '';
        
        // Confirm before saving
        let confirmTitle = '{{ __('modules.swal.confirm_title') }}';
        let confirmText = isEdit ? '{{ __('modules.asset_tools.update_confirm') }}' : '{{ __('modules.asset_tools.create_confirm') }}';
        let successMessage = isEdit ? '{{ __('modules.swal.data_updated') }}' : '{{ __('modules.swal.data_saved') }}';
        
        // Fix aria-hidden conflict by removing focus before SweetAlert
        $(this).blur();
        
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
                console.log('TOOLS SWAL: SweetAlert opened, fixing focus management');
                // Remove any aria-hidden conflicts
                $('.flex.h-screen').removeAttr('aria-hidden');
            },
            didClose: function() {
                // Restore focus after SweetAlert closes
                console.log('TOOLS SWAL: SweetAlert closed, restoring focus');
                setTimeout(function() {
                    if (submitBtn && $(submitBtn).is(':visible')) {
                        submitBtn.focus();
                    }
                }, 100);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitToolForm(successMessage);
            }
        });
    });

    // Click handler for submit button - delegates to form submit handler
    $('#submitToolBtn').on('click', function(e) {
        e.preventDefault();
        $(this).blur();
        $('#toolForm').trigger('submit');
    });



    function submitToolForm(successMessage = '{{ __('modules.swal.data_saved') }}') {
        console.log('TOOLS SUBMIT FORM: Starting form submission'); // DEBUG
        console.log('TOOLS SUBMIT FORM: Function exists, typeof submitToolForm:', typeof submitToolForm); // DEBUG
        
        try {
            let formData = $('#toolForm').serialize();
            let id = $('#toolId').val();
            let url = storeUrl;
            let method = 'POST';
            
            if (id) {
                url = updateUrlTemplate.replace(':id', id);
                // Add _method field for Laravel method spoofing
                formData += '&_method=PUT';
                method = 'POST'; // Always use POST with _method field
                successMessage = '{{ __('modules.swal.data_updated') }}';
            }

            console.log('TOOLS SUBMIT FORM: Preparing AJAX request'); // DEBUG
            console.log('TOOLS SUBMIT FORM: URL:', url); // DEBUG
            console.log('TOOLS SUBMIT FORM: Method:', method); // DEBUG
            console.log('TOOLS SUBMIT FORM: Form data:', formData); // DEBUG
            console.log('TOOLS SUBMIT FORM: CSRF Token:', '{{ csrf_token() }}'); // DEBUG

            // Disable submit button to prevent double submission
            $('#submitToolBtn').prop('disabled', true).html('<i class="ph ph-spinner-gap animate-spin text-lg"></i> {{ __('modules.common.saving') }}');
            console.log('TOOLS SUBMIT FORM: Button disabled, sending AJAX request'); // DEBUG

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
                    console.log('TOOLS SUBMIT FORM: AJAX beforeSend triggered'); // DEBUG
                },
            success: function(response) {
                console.log('TOOLS SUBMIT FORM: AJAX success callback triggered'); // DEBUG
                console.log('TOOLS SUBMIT FORM: Server response:', response); // DEBUG
                closeToolModal();
                
                // Reload DataTable with a small delay to ensure server has processed update
                setTimeout(function() {
                    $('#toolsTable').DataTable().ajax.reload(null, false); // false = keep current page
                    console.log('TOOLS SUBMIT FORM: DataTable reloaded');
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
                $('#toolForm')[0].reset();
                $('#submitToolBtn').prop('disabled', false).html('<i class="ph ph-floppy-disk text-lg"></i> {{ __('modules.common.save') }}');
            },
            error: function(xhr) {
                console.log('TOOLS SUBMIT FORM: AJAX error callback triggered'); // DEBUG
                console.error('TOOLS SUBMIT FORM: AJAX error:', xhr); // DEBUG
                console.error('TOOLS SUBMIT FORM: Status:', xhr.status); // DEBUG
                console.error('TOOLS SUBMIT FORM: Status text:', xhr.statusText); // DEBUG
                console.error('TOOLS SUBMIT FORM: Response text:', xhr.responseText); // DEBUG
                console.error('TOOLS SUBMIT FORM: Response JSON:', xhr.responseJSON); // DEBUG
                
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
                $('#submitToolBtn').prop('disabled', false).html('<i class="ph ph-floppy-disk text-lg"></i> {{ __('modules.common.save') }}');
            },
            complete: function(xhr) {
                console.log('TOOLS SUBMIT FORM: AJAX complete callback triggered'); // DEBUG
            }
        });
        } catch (error) {
            console.error('TOOLS SUBMIT FORM: Exception caught:', error); // DEBUG
            console.error('TOOLS SUBMIT FORM: Error stack:', error.stack); // DEBUG
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem: ' + error.message,
                confirmButtonColor: '#dc2626'
            });
            // Re-enable button on error
            $('#submitToolBtn').prop('disabled', false).html('<i class="ph ph-floppy-disk text-lg"></i> {{ __('modules.common.save') }}');
        }
    }
 
 
    function deleteTool(id) {
        console.log('TOOLS DELETE: Starting delete for ID:', id); // DEBUG
        
        // Check if delete buttons are working
        console.log('TOOLS DELETE: Swal available:', typeof Swal !== 'undefined');
        console.log('TOOLS DELETE: jQuery available:', typeof $ !== 'undefined');
        
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
            console.log('TOOLS DELETE: Swal result:', result); // DEBUG
            if (result.isConfirmed) {
                console.log('TOOLS DELETE: Confirmed, sending AJAX request'); // DEBUG
                $.ajax({
                    url: destroyUrlTemplate.replace(':id', id),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('TOOLS DELETE: Delete successful:', response); // DEBUG
                        $('#toolsTable').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __('modules.swal.success') }}',
                            text: '{{ __('modules.swal.data_deleted') }}',
                            confirmButtonColor: '#009B77'
                        });
                    },
                    error: function(xhr) {
                        console.error('TOOLS DELETE: Delete failed:', xhr); // DEBUG
                        console.error('TOOLS DELETE: Response text:', xhr.responseText);
                        let errorMessage = '{{ __('modules.swal.delete_error') }}';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            errorMessage = '{{ __('modules.swal.not_found') }}';
                        } else if (xhr.status === 403) {
                            errorMessage = '{{ __('modules.asset_tools.delete_permission_error') }}';
                        } else if (xhr.status === 500) {
                            errorMessage = '{{ __('modules.swal.server_error') }}';
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
                    $('#qrCodeText').text('{{ __('modules.swal.qr_code_for') }} ' + response.toolCode);
                    $('#qrModal').removeClass('hidden');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || '{{ __('modules.swal.qr_error') }}',
                        confirmButtonColor: '#dc2626'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = '{{ __('modules.swal.qr_load_error') }}';
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
