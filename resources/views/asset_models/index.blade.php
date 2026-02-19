<x-app-layout>
    <x-slot name="title">{{ __('modules.asset_models.title') }}</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('modules.asset_models.title') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('modules.asset_models.subtitle') }}</p>
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
                        <a href="{{ route('assets.models.export') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition">
                            <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                            <span class="text-sm font-medium">{{ __('modules.common.export_excel') }}</span>
                        </a>
                        <a href="{{ route('assets.models.export-pdf') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition border-t border-gray-100">
                            <i class="ph ph-file-pdf text-lg text-red-600"></i>
                            <span class="text-sm font-medium">{{ __('modules.common.export_pdf') }}</span>
                        </a>
                    </div>
                </div>
                <button type="button" id="createNewModel" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
                    <i class="ph ph-plus text-lg"></i>
                    <span>{{ __('modules.common.add_data') }}</span>
                </button>
            </div>
        </div>
 
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('modules.asset_models.total_models') }}</p>
                        <h5 class="text-xl lg:text-2xl font-bold text-gray-900 mt-1" id="totalModels">0</h5>
                    </div>
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-ebara-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-cube text-xl lg:text-2xl text-ebara-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('modules.asset_models.active') }}</p>
                        <h5 class="text-xl lg:text-2xl font-bold text-green-600 mt-1" id="activeModels">0</h5>
                    </div>
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-check-circle text-xl lg:text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('modules.asset_models.inactive') }}</p>
                        <h5 class="text-xl lg:text-2xl font-bold text-yellow-600 mt-1" id="inactiveModels">0</h5>
                    </div>
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-clock text-xl lg:text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 lg:p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('modules.asset_models.total_assets') }}</p>
                        <h5 class="text-xl lg:text-2xl font-bold text-gray-900 mt-1" id="totalAssets">0</h5>
                    </div>
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="ph ph-package text-xl lg:text-2xl text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Controls Header -->
            <div class="p-5 border-b border-gray-100 bg-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="{{ __('modules.asset_models.search_placeholder') }}" class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="modelsTable" class="w-full" width="100%">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.code') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.asset_models.model_name') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.type') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.asset_models.material') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.asset_models.manufacture_date') }}</th>
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
                <p class="text-sm text-gray-500">{{ __('modules.asset_models.empty_state') }}</p>
            </div>
        </div>
 
    </div>
 
    <!-- Add/Edit Modal -->
    <div id="modelModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden opacity-0" style="display: none;">
        <div class="flex items-center justify-center min-h-screen w-full p-4">
            <div id="modelModalContent" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform transition-all duration-300 scale-95 opacity-0 modal-content flex flex-col max-h-[90vh]">
                <!-- Modal Header with Gradient -->
                <div class="relative bg-gradient-to-r from-ebara-600 to-ebara-700 rounded-t-2xl p-6 text-white flex-shrink-0">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="ph ph-cube text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold" id="modalTitle">{{ __('modules.asset_models.add_title') }}</h3>
                                <p class="text-ebara-100 text-sm" id="modalSubtitle">{{ __('modules.asset_models.add_subtitle') }}</p>
                            </div>
                        </div>
                        <button type="button" id="closeModalBtn" class="text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200 p-2 rounded-lg" tabindex="0" role="button" aria-label="{{ __('modules.common.close_modal') }}">
                            <i class="ph ph-x text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form id="modelForm" class="p-6 space-y-5 overflow-y-auto flex-1" action="{{ route('assets.models.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="modelId" name="id">
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <!-- Nama Model -->
                    <div class="space-y-2">
                        <label for="name" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-cube text-ebara-600 mr-2"></i>
                            {{ __('modules.asset_models.model_name') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipe & Material -->
                    <x-ui.form-grid columns="2">
                        <div class="space-y-2">
                            <label for="type" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-tag text-ebara-600 mr-2"></i>
                                {{ __('modules.common.type') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" id="type" name="type" required value="{{ old('type') }}" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400" placeholder="{{ __('modules.asset_models.type_placeholder') }}">
                            @error('type')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="material_id" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-package text-ebara-600 mr-2"></i>
                                {{ __('modules.asset_models.material') }}
                            </label>
                            <select id="material_id" name="material_id" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                                <option value="">{{ __('modules.common.select_material') }}</option>
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
                    </x-ui.form-grid>

                    <!-- Tanggal & Kondisi -->
                    <x-ui.form-grid columns="2">
                        <div class="space-y-2">
                            <label for="manufacture_date" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-calendar text-ebara-600 mr-2"></i>
                                {{ __('modules.asset_models.manufacture_date') }}
                            </label>
                            <input type="date" id="manufacture_date" name="manufacture_date" value="{{ old('manufacture_date') }}" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                            @error('manufacture_date')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="condition" class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-heartbeat text-ebara-600 mr-2"></i>
                                {{ __('modules.common.condition') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select id="condition" name="condition" required class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                                <option value="">{{ __('modules.common.select_condition') }}</option>
                                <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>{{ __('modules.common.condition_good') }}</option>
                                <option value="Repair" {{ old('condition') == 'Repair' ? 'selected' : '' }}>{{ __('modules.common.condition_repair') }}</option>
                                <option value="Damaged" {{ old('condition') == 'Damaged' ? 'selected' : '' }}>{{ __('modules.common.condition_damaged') }}</option>
                            </select>
                            @error('condition')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
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
                        <textarea id="description" name="description" rows="3" class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-5 border-t border-gray-200">
                        <button type="button" id="cancelBtn" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all duration-200 text-sm">
                            {{ __('modules.common.cancel') }}
                        </button>
                        <button type="submit" form="modelForm" class="px-5 py-2.5 bg-gradient-to-r from-ebara-600 to-ebara-700 hover:from-ebara-700 hover:to-ebara-800 text-white font-medium rounded-xl shadow-lg shadow-ebara-500/25 hover:shadow-ebara-500/40 transition-all duration-200 text-sm flex items-center justify-center gap-2">
                            <i class="ph ph-floppy-disk text-lg"></i>
                            {{ __('modules.common.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div id="qrModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden" style="display: none;">
        <div class="flex items-center justify-center min-h-screen w-full p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0 modal-content flex flex-col">
                <div class="flex justify-between items-center p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900" id="qrModalTitle">QR Code</h3>
                    <button type="button" id="closeQrModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-lg hover:bg-gray-50">
                        <i class="ph ph-x text-xl"></i>
                    </button>
                </div>
                <div class="p-8 text-center">
                    <div id="qrCodeContainer" class="flex justify-center mb-6 bg-white p-4 rounded-xl border-2 border-dashed border-gray-200"></div>
                    <p id="qrCodeText" class="text-lg font-mono font-bold text-gray-700 mb-2"></p>
                    <p class="text-sm text-gray-500">{{ __('modules.common.scan_qr_detail') }}</p>
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100 rounded-b-2xl flex justify-center">
                    <button type="button" onclick="window.print()" class="px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-all duration-200 text-sm flex items-center gap-2 shadow-sm">
                        <i class="ph ph-printer text-lg"></i>
                        {{ __('modules.common.print_qr') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
      
    @push('styles')
    /* Modal animations fix - Simplified like asset_tools */
    #modelModal {
        transition: opacity 0.3s ease !important;
    }
    
    #modelModal.opacity-0 {
        opacity: 0 !important;
    }
    
    #modelModal.opacity-100 {
        opacity: 1 !important;
    }
    
    #modelModalContent {
        transition: all 0.3s ease !important;
    }
    
    #modelModalContent.scale-95 {
        transform: scale(0.95) !important;
        opacity: 0 !important;
    }
    
    #modelModalContent.scale-100 {
        transform: scale(1) !important;
        opacity: 1 !important;
    }
    
    #modelModalContent.opacity-0 {
        opacity: 0 !important;
    }
    
    #modelModalContent.opacity-100 {
        opacity: 1 !important;
    }
    
    /* Fix for aria-hidden conflicts */
    .flex.h-screen[aria-hidden="true"] #modelForm button[type="submit"] {
        pointer-events: auto !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* Ensure SweetAlert doesn't hide our buttons */
    body.swal2-shown:not(.swal2-toast-shown) .flex.h-screen {
        aria-hidden: unset !important;
    }
    /* Export dropdown styles now handled by Alpine.js */
    @endpush
    
    @push('scripts')
    <script>
    var storeUrl = "{{ route('assets.models.store') }}";
    var showUrlTemplate = "{{ route('assets.models.show', ':id') }}";
    var updateUrlTemplate = "{{ route('assets.models.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('assets.models.destroy', ':id') }}";
    var qrCodeUrlTemplate = "{{ route('assets.models.qr-code', ':id') }}";
    var dataUrl = "{{ route('assets.models.data') }}";

    // Export functionality now handled by Alpine.js - no need for vanilla JS

    // Global helper function untuk format tanggal
    window.formatDateForInput = function(dateString) {
        console.log('MODELS FORMAT DATE: Input:', dateString, 'Type:', typeof dateString); // DEBUG
        
        if (!dateString) {
            console.log('MODELS FORMAT DATE: Empty date, returning empty string'); // DEBUG
            return '';
        }
        
        // Jika sudah format Y-m-d, gunakan langsung
        if (dateString.match(/^\d{4}-\d{2}-\d{2}$/)) {
            console.log('MODELS FORMAT DATE: Already in Y-m-d format:', dateString); // DEBUG
            return dateString;
        }
        
        // Handle format datetime dari Laravel (Y-m-d H:i:s)
        if (dateString.match(/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/)) {
            const dateOnly = dateString.split(' ')[0];
            console.log('MODELS FORMAT DATE: Laravel datetime format, extracting date:', dateOnly); // DEBUG
            return dateOnly;
        }
        
        // Konversi dari format lain ke Y-m-d
        try {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                console.log('MODELS FORMAT DATE: Invalid date, returning empty string'); // DEBUG
                return '';
            }
            
            // Konversi ke timezone lokal
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            
            const result = `${year}-${month}-${day}`;
            console.log('MODELS FORMAT DATE: Converted to:', result); // DEBUG
            return result;
        } catch (e) {
            console.error('MODELS FORMAT DATE: Error parsing date:', dateString, e);
            return '';
        }
    };

    // Global Modal Helpers - Fixed for Tailwind transition sync
    window.openModelModal = function() {
        console.log('DEBUG MODAL: Opening modal'); // DEBUG
        var modal = document.getElementById('modelModal');
        
        if (!modal) {
            console.error('DEBUG MODAL: Modal element not found!'); // DEBUG
            return;
        }
        
        // Force inline styles to override everything
        modal.classList.remove('hidden');
        modal.style.setProperty('display', 'flex', 'important');
        modal.style.setProperty('opacity', '1', 'important');
        modal.style.setProperty('visibility', 'visible', 'important');
        modal.style.setProperty('position', 'fixed', 'important');
        modal.style.setProperty('top', '0', 'important');
        modal.style.setProperty('left', '0', 'important');
        modal.style.setProperty('right', '0', 'important');
        modal.style.setProperty('bottom', '0', 'important');
        modal.style.setProperty('z-index', '9999', 'important');
        
        // Force reflow to ensure transition works
        modal.offsetHeight;
        
        requestAnimationFrame(function() {
            var modalContent = document.getElementById('modelModalContent');
            if (modalContent) {
                modalContent.style.setProperty('opacity', '1', 'important');
                modalContent.style.setProperty('transform', 'scale(1)', 'important');
                modalContent.style.setProperty('visibility', 'visible', 'important');
                console.log('DEBUG MODAL: Modal content inline styles set'); // DEBUG
            }
            
            console.log('DEBUG MODAL: Modal inline styles set'); // DEBUG
            console.log('DEBUG MODAL: Modal opened, checking buttons'); // DEBUG
            
            // Ensure buttons are enabled and visible
            setTimeout(function() {
                var submitBtn = document.querySelector('#modelForm button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.style.display = 'flex';
                    console.log('DEBUG MODAL: Submit button enabled and visible'); // DEBUG
                }
            }, 100);
        });
    }
    
    window.closeModelModal = function() {
        console.log('DEBUG MODAL: Closing modal'); // DEBUG
        var modal = document.getElementById('modelModal');
        
        if (!modal) {
            return;
        }
        
        var modalContent = document.getElementById('modelModalContent');
        if (modalContent) {
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
        }
        
        // Fade out modal
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        
        // Wait for content transition, then hide modal
        setTimeout(function() {
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }, 300);
    }

    window.openQrModal = function() {
        var modal = document.getElementById('qrModal');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        requestAnimationFrame(function() {
            modal.querySelector('.modal-content').classList.add('modal-active');
        });
    }

    window.closeQrModal = function() {
        var modal = document.getElementById('qrModal');
        var content = modal.querySelector('.modal-content');
        content.classList.remove('modal-active');
        setTimeout(function() {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }, 300);
    }

    // Global Action Functions
    window.editModel = function(id) {
        console.log('MODELS EDIT: Starting edit for ID:', id); // DEBUG
        
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                console.log('MODELS EDIT: Response from server:', response); // DEBUG
                if (response.success) {
                    const data = response.data;
                    console.log('MODELS EDIT: Model data:', data); // DEBUG
                    
                    // Debug tanggal dengan lebih detail
                    console.log('MODELS EDIT: Manufacture date raw:', data.manufacture_date, 'Type:', typeof data.manufacture_date);
                    
                    // Test fungsi formatDateForInput
                    console.log('MODELS EDIT: formatDateForInput(manufacture_date):', formatDateForInput(data.manufacture_date));
                    
                    $('#modalTitle').text('{{ __('modules.asset_models.edit_title') }}');
                    $('#modalSubtitle').text('{{ __('modules.asset_models.edit_subtitle') }}');
                    $('#modelId').val(data.id);
                    $('#name').val(data.name);
                    $('#type').val(data.type);
                    $('#material_id').val(data.material_id);
                    
                    // Perbaikan untuk tanggal - menggunakan fungsi helper global
                    const formattedManufactureDate = formatDateForInput(data.manufacture_date);
                    console.log('MODELS EDIT: Setting manufacture_date to:', formattedManufactureDate);
                    
                    // Force set tanggal dengan multiple approaches
                    $('#manufacture_date').val(formattedManufactureDate);
                    
                    // Additional force set untuk memastikan tanggal terisi
                    setTimeout(function() {
                        $('#manufacture_date').val(formattedManufactureDate).trigger('change');
                        console.log('MODELS EDIT: Forced manufacture_date value after timeout:', $('#manufacture_date').val());
                    }, 100);
                    
                    $('#condition').val(data.condition);
                    $('#location').val(data.location).trigger('change');
                    $('#description').val(data.description);
                    
                    // Debug final values
                    console.log('MODELS EDIT: Final manufacture_date value:', $('#manufacture_date').val());
                    
                    // Update form action for edit
                    $('#modelForm').attr('action', updateUrlTemplate.replace(':id', id));
                    $('#formMethod').val('PUT');
                    
                    console.log('MODELS EDIT: About to open modal');
                    openModelModal();
                } else {
                    console.error('MODELS EDIT: Server returned error:', response.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonColor: '#dc2626'
                    });
                }
            },
            error: function(xhr) {
                console.error('MODELS EDIT: Error fetching model:', xhr); // DEBUG
                console.error('MODELS EDIT: Response text:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ __('modules.asset_models.fetch_error') }}',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    window.deleteModel = function(id) {
        Swal.fire({
            title: '{{ __('modules.swal.confirm_title') }}',
            text: '{{ __('modules.swal.delete_warning') }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#009B77',
            confirmButtonText: '{{ __('modules.swal.yes_delete') }}',
            cancelButtonText: '{{ __('modules.swal.cancel') }}',
            reverseButtons: true
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
                            title: '{{ __('modules.swal.success') }}',
                            text: '{{ __('modules.swal.data_deleted') }}',
                            confirmButtonColor: '#009B77'
                        });
                    }
                });
            }
        });
    }

    window.viewQrCode = function(id) {
        $.ajax({
            url: qrCodeUrlTemplate.replace(':id', id),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#qrCodeContainer').html(response.qrCode);
                    $('#qrCodeText').text(response.modelCode);
                    $('#qrModalTitle').text('QR Code: ' + response.modelCode);
                    openQrModal();
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

    $(document).ready(function() {
        // Fix for production aria-hidden conflicts
        if (typeof Swal !== 'undefined') {
            // Override SweetAlert's default behavior to prevent aria-hidden conflicts
            Swal.mixin({
                didOpen: function() {
                    // Remove aria-hidden from main container when SweetAlert opens
                    $('.flex.h-screen').removeAttr('aria-hidden');
                    console.log('MODELS PRODUCTION FIX: Removed aria-hidden from main container');
                }
            });
        }
        
        // Global fix for any dynamically added aria-hidden
        setInterval(function() {
            if ($('#modelForm button[type="submit"]').is(':visible') && $('.flex.h-screen').attr('aria-hidden') === 'true') {
                $('.flex.h-screen').removeAttr('aria-hidden');
                console.log('MODELS PRODUCTION FIX: Auto-removed aria-hidden conflict');
            }
        }, 1000);
        
        // Initialize DataTable
        let table = $('#modelsTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: dataUrl,
            createdRow: function(row, data, dataIndex) {
                $(row).addClass('group hover:bg-gray-50 transition-colors duration-200');
            },
            columnDefs: [
                {
                    targets: 0, // Kode
                    className: 'text-sm',
                    render: function(data, type, row) {
                        return '<span class="bg-gray-100 text-gray-600 py-1 px-2 rounded-md font-mono text-xs">' + data + '</span>';
                    }
                },
                {
                    targets: 1, // Nama Model
                    className: 'text-sm font-bold text-gray-900'
                },
                {
                    targets: [2, 3, 4, 5, 6],
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
                        return '<span class="' + badgeClass + '">' + (data === 'Good' ? '{{ __('modules.common.condition_good') }}' : (data === 'Repair' ? '{{ __('modules.common.condition_repair') }}' : '{{ __('modules.common.condition_damaged') }}')) + '</span>';
                    }
                },
                { data: 'location' },
                {
                    data: 'actions',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="window.viewQrCode(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="QR Code">
                                    <i class="ph ph-qr-code text-xl"></i>
                                </button>
                                <button onclick="window.editModel(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="Edit">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="window.deleteModel(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="{{ __('modules.common.delete') }}">
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
                }
            },
            dom: '<"flex flex-col sm:flex-row justify-between items-center gap-4 mb-4"lf>rt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-4"ip">',
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            initComplete: function() {
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

        // Create button handler - Simplified like asset_tools
        $('#createNewModel').on('click', function() {
            $('#modalTitle').text('{{ __('modules.asset_models.add_title') }}');
            $('#modalSubtitle').text('{{ __('modules.asset_models.add_subtitle') }}');
            $('#modelForm')[0].reset();
            $('#modelId').val('');
            $('#modelForm').attr('action', storeUrl);
            $('#formMethod').val('POST');
            openModelModal();
        });

        // Close button handlers
        $('#closeModalBtn').on('click', function() {
            closeModelModal();
        });

        $('#cancelBtn').on('click', function() {
            closeModelModal();
        });

        // Close modal when clicking outside
        $('#modelModal').on('click', function(e) {
            if (e.target === this) {
                closeModelModal();
            }
        });
        
        // Single click handler for submit button
        $('#modelForm button[type="submit"]').off('click').on('click', function(e) {
            e.preventDefault();
            $(this).blur();
            $('#modelForm').trigger('submit');
        });
        
        // QR Code Modal Handlers
        $('#closeQrModalBtn').on('click', function() {
            closeQrModal();
        });
        
        $('#qrModal').on('click', function(e) {
            if (e.target === this) {
                closeQrModal();
            }
        });

        // Form submit handler
        $('#modelForm').on('submit', function(e) {
            e.preventDefault();

            // HTML5 validation — browser highlights empty required fields inline
            if (!this.reportValidity()) {
                return;
            }

            const form = $(this);
            const id = $('#modelId').val();
            let url = storeUrl;
            let method = 'POST';
            
            console.log('MODELS FORM SUBMIT: Form ID:', id, 'Method:', method); // DEBUG
            
            if (id) {
                url = updateUrlTemplate.replace(':id', id);
                method = 'PUT';
            }

            // Use serialize instead of FormData for consistency
            const formData = form.serialize();
            if (method === 'PUT') {
                // Add _method field for Laravel method spoofing
                const formDataObj = new URLSearchParams(formData);
                formDataObj.append('_method', 'PUT');
                const finalFormData = formDataObj.toString();
                console.log('MODELS FORM SUBMIT: Final form data:', finalFormData); // DEBUG
                
                // Confirm before saving
                let confirmTitle = '{{ __('modules.swal.confirm_title') }}';
                let confirmText = id ? '{{ __('modules.asset_models.update_confirm') }}' : '{{ __('modules.asset_models.create_confirm') }}';
                let successMessage = id ? '{{ __('modules.swal.data_updated') }}' : '{{ __('modules.swal.data_saved') }}';

                // Fix aria-hidden conflict by removing focus before SweetAlert
                $('#modelForm button[type="submit"]').blur();
                
                // Store reference to button for later focus restoration
                var submitBtn = document.querySelector('#modelForm button[type="submit"]');
                
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
                        console.log('MODELS SWAL: SweetAlert opened, fixing focus management');
                        // Remove any aria-hidden conflicts
                        $('.flex.h-screen').removeAttr('aria-hidden');
                    },
                    didClose: function() {
                        // Restore focus after SweetAlert closes
                        console.log('MODELS SWAL: SweetAlert closed, restoring focus');
                        setTimeout(function() {
                            if (submitBtn && $(submitBtn).is(':visible')) {
                                submitBtn.focus();
                            }
                        }, 100);
                    }
                }).then((result) => {
                    console.log('MODELS FORM SUBMIT: Swal result:', result); // DEBUG
                    if (result.isConfirmed) {
                        // Disable submit button to prevent double submission
                        $('#modelForm button[type="submit"]').prop('disabled', true).html('<i class="ph ph-spinner-gap animate-spin"></i> {{ __('modules.common.saving') }}');
                        
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: finalFormData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                console.log('MODELS FORM SUBMIT: Update successful:', data);
                                closeModelModal();
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __('modules.swal.success') }}',
                                    text: successMessage,
                                    confirmButtonColor: '#009B77',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                // Reset form and re-enable button
                                $('#modelForm')[0].reset();
                                $('#modelForm button[type="submit"]').prop('disabled', false).html('<i class="ph ph-floppy-disk"></i> {{ __('modules.common.save') }}');
                            },
                            error: function(xhr) {
                                console.error('MODELS FORM SUBMIT: AJAX error:', xhr); // DEBUG
                                console.error('MODELS FORM SUBMIT: Response text:', xhr.responseText);
                                let errorMessage = '{{ __('modules.asset_models.save_error') }}';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMessage,
                                    confirmButtonColor: '#dc2626'
                                });
                                // Re-enable button on error
                                $('#modelForm button[type="submit"]').prop('disabled', false).html('<i class="ph ph-floppy-disk"></i> {{ __('modules.common.save') }}');
                            }
                        });
                    }
                });
            } else {
                // For new model creation
                console.log('MODELS FORM SUBMIT: Creating new model'); // DEBUG
                
                // Confirm before saving
                let confirmTitle = '{{ __('modules.swal.confirm_title') }}';
                let confirmText = '{{ __('modules.asset_models.create_confirm') }}';
                let successMessage = '{{ __('modules.swal.data_saved') }}';

                Swal.fire({
                    title: confirmTitle,
                    text: confirmText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#009B77',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '{{ __('modules.swal.yes_save') }}',
                    cancelButtonText: '{{ __('modules.swal.cancel') }}'
                }).then((result) => {
                    console.log('MODELS FORM SUBMIT: Swal result for new model:', result); // DEBUG
                    if (result.isConfirmed) {
                        // Disable submit button to prevent double submission
                        $('#modelForm button[type="submit"]').prop('disabled', true).html('<i class="ph ph-spinner-gap animate-spin"></i> {{ __('modules.common.saving') }}');
                        
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                console.log('MODELS FORM SUBMIT: Create successful:', data);
                                closeModelModal();
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __('modules.swal.success') }}',
                                    text: successMessage,
                                    confirmButtonColor: '#009B77',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                // Reset form and re-enable button
                                $('#modelForm')[0].reset();
                                $('#modelForm button[type="submit"]').prop('disabled', false).html('<i class="ph ph-floppy-disk"></i> {{ __('modules.common.save') }}');
                            },
                            error: function(xhr) {
                                console.error('MODELS FORM SUBMIT: AJAX error for new model:', xhr); // DEBUG
                                console.error('MODELS FORM SUBMIT: Response text:', xhr.responseText);
                                let errorMessage = '{{ __('modules.asset_models.save_error') }}';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMessage,
                                    confirmButtonColor: '#dc2626'
                                });
                                // Re-enable button on error
                                $('#modelForm button[type="submit"]').prop('disabled', false).html('<i class="ph ph-floppy-disk"></i> {{ __('modules.common.save') }}');
                            }
                        });
                    }
                });
            }
        });

        // Close modal with ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModelModal();
                closeQrModal();
            }
        });
    });
    </script>
    @endpush
</x-app-layout>