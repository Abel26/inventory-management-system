<x-app-layout>
    <x-slot name="title">{{ __('modules.gedungs.title') }}</x-slot>
    
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('modules.gedungs.title') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('modules.gedungs.subtitle') }}</p>
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
                        <a href="{{ route('master-data.gedungs.export') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition" onclick="handleExport(event, 'excel')">
                            <i class="ph ph-microsoft-excel-logo text-lg text-green-600"></i>
                            <span class="text-sm font-medium">{{ __('modules.common.export_excel') }}</span>
                        </a>
                        <a href="{{ route('master-data.gedungs.export-pdf') }}" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-100 transition border-t border-gray-100" onclick="handleExport(event, 'pdf')">
                            <i class="ph ph-file-pdf text-lg text-red-600"></i>
                            <span class="text-sm font-medium">{{ __('modules.common.export_pdf') }}</span>
                        </a>
                    </div>
                </div>
                <button type="button" id="createNewGedung" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
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
                        <p class="text-sm text-gray-500">{{ __('modules.gedungs.total_buildings') }}</p>
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
                        <p class="text-sm text-gray-500">{{ __('modules.gedungs.total_assets_in_building') }}</p>
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
                    <input type="text" id="searchInput" placeholder="{{ __('modules.gedungs.search_placeholder') }}" class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="gedungsTable" class="w-full" width="100%">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.gedungs.building_code') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.gedungs.building_name') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.gedungs.total_models') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.gedungs.total_materials') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.gedungs.total_tools') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.gedungs.total_assets') }}</th>
                        <th class="px-6 py-4 text-center font-semibold whitespace-nowrap">{{ __('modules.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Data will be loaded by DataTables -->
                </tbody>
            </table>
            
            <!-- Empty State -->
            <div id="emptyState" class="p-12 text-center flex flex-col items-center justify-center hidden">
                <i class="ph ph-buildings text-5xl mb-4 text-gray-300"></i>
                <p class="text-sm text-gray-500">{{ __('modules.gedungs.empty_state') }}</p>
            </div>
        </div>
    </div>
  
    <!-- Add/Edit Modal -->
    <div id="gedungModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden opacity-0" style="display: none;">
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
                                <h3 class="text-xl font-bold" id="modalTitle">{{ __('modules.gedungs.add_title') }}</h3>
                                <p class="text-ebara-100 text-sm">{{ __('modules.gedungs.add_subtitle') }}</p>
                            </div>
                        </div>
                        <button type="button" id="closeModalBtn" class="text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200 p-2 rounded-lg" tabindex="0" role="button" aria-label="{{ __('modules.common.close_modal') }}">
                            <i class="ph ph-x text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Modal Body -->
                <form id="gedungForm" class="p-6 space-y-5" action="{{ route('master-data.gedungs.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="gedungId" name="id">
                    <input type="hidden" name="_method" value="POST">
                    
                    <!-- Kode Gedung Field -->
                    <div class="space-y-2">
                        <label for="gedung_id_field" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-hash text-ebara-600 mr-2"></i>
                            {{ __('modules.gedungs.building_code') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="gedung_id_field"
                                   name="gedung_id"
                                   required
                                   class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400"
                                   placeholder="{{ __('modules.gedungs.code_placeholder') }}"
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
                            {{ __('modules.gedungs.building_name') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="nama"
                                   name="nama"
                                   required
                                   class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400"
                                   placeholder="{{ __('modules.gedungs.name_placeholder') }}"
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
                                <h4 class="text-sm font-semibold text-ebara-900">{{ __('modules.gedungs.info_title') }}</h4>
                                <p class="text-xs text-ebara-700 mt-1">
                                    {{ __('modules.gedungs.info_description') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
                
                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-2xl flex flex-col sm:flex-row justify-end gap-3">
                    <button type="button" id="cancelBtn" class="order-2 sm:order-1 w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-xl border border-gray-300 transition-all duration-200 flex items-center justify-center">
                        <i class="ph ph-x mr-2"></i>
                        {{ __('modules.common.cancel') }}
                    </button>
                    <button type="submit" form="gedungForm" class="order-1 sm:order-2 w-full sm:w-auto bg-gradient-to-r from-ebara-600 to-ebara-700 hover:from-ebara-700 hover:to-ebara-800 text-white font-medium py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="ph ph-check-circle mr-2"></i>
                        {{ __('modules.gedungs.save_building') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
  
    @push('styles')
    <style>
        #gedungModal {
            transition: opacity 0.3s ease !important;
        }
        
        #gedungModal.opacity-0 {
            opacity: 0 !important;
        }
        
        #gedungModal.opacity-100 {
            opacity: 1 !important;
        }
        
        #gedungModal .modal-content {
            transition: all 0.3s ease !important;
        }
        
        #gedungModal .modal-content.scale-95 {
            transform: scale(0.95) !important;
            opacity: 0 !important;
        }
        
        #gedungModal .modal-content.scale-100 {
            transform: scale(1) !important;
            opacity: 1 !important;
        }
        
        #gedungModal .modal-content.opacity-0 {
            opacity: 0 !important;
        }
        
        #gedungModal .modal-content.opacity-100 {
            opacity: 1 !important;
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
        console.log('DEBUG GEDUNG: Opening modal'); // DEBUG
        var modal = document.getElementById('gedungModal');
        
        if (!modal) {
            console.error('DEBUG GEDUNG: Modal element not found!'); // DEBUG
            return;
        }
        
        // Force inline styles to override everything
        modal.classList.remove('hidden');
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');
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
            var modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
                modalContent.style.setProperty('opacity', '1', 'important');
                modalContent.style.setProperty('transform', 'scale(1)', 'important');
                modalContent.style.setProperty('visibility', 'visible', 'important');
                console.log('DEBUG GEDUNG: Modal content inline styles set'); // DEBUG
            }
            
            console.log('DEBUG GEDUNG: Modal inline styles set'); // DEBUG
            console.log('DEBUG GEDUNG: Modal opened, checking buttons'); // DEBUG
            
            // Ensure buttons are enabled and visible
            setTimeout(function() {
                var submitBtn = document.querySelector('#gedungForm button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.style.display = 'flex';
                    console.log('DEBUG GEDUNG: Submit button enabled and visible'); // DEBUG
                }
            }, 100);
        });
    }
 
    window.hideModal = function() {
        console.log('DEBUG GEDUNG: Closing modal'); // DEBUG
        var modal = document.getElementById('gedungModal');
        
        if (!modal) {
            return;
        }
        
        var modalContent = modal.querySelector('.modal-content');
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
 
    window.handleCreateNewGedung = function() {
        console.log('DEBUG GEDUNG: Create handler called'); // DEBUG
        try {
            const title = document.getElementById('modalTitle');
            const form = document.getElementById('gedungForm');
            const idField = document.getElementById('gedungId');
            
            if (title) {
                title.textContent = '{{ __('modules.gedungs.add_title') }}';
                const subtitle = title.nextElementSibling;
                if (subtitle) subtitle.textContent = '{{ __('modules.gedungs.add_subtitle') }}';
            }
            if (form) form.reset();
            if (idField) idField.value = '';
            
            showModal();
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
                                <button onclick="editGedung(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="{{ __('modules.common.edit') }}">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteGedung(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="{{ __('modules.common.delete') }}">
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
                $('#totalGedungs').text(json.recordsTotal || 0);
                $('#totalAssets').text(json.data.reduce((sum, row) => sum + (row.total_assets || 0), 0));
            }
        });
 
        // Create button handler
        $('#createNewGedung').on('click', function() {
            console.log('DEBUG GEDUNG: Create button clicked'); // DEBUG
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
            const id = $('#gedungId').val();
            let isEdit = id !== '';
            
            // Confirm before saving
            let confirmTitle = '{{ __('modules.swal.confirm_title') }}';
            let confirmText = isEdit ? '{{ __('modules.gedungs.update_confirm') }}' : '{{ __('modules.gedungs.create_confirm') }}';
            let successMessage = isEdit ? '{{ __('modules.swal.data_updated') }}' : '{{ __('modules.swal.data_saved') }}';

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
                if (result.isConfirmed) {
                    const formData = new FormData(form[0]);
                    let url = storeUrl;
                    let method = 'POST';

                    if (id) {
                        url = updateUrlTemplate.replace(':id', id);
                        method = 'PUT';
                        // Update method override for PUT requests
                        formData.set('_method', 'PUT');
                    } else {
                        formData.set('_method', 'POST');
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
                                title: '{{ __('modules.swal.success') }}',
                                text: successMessage,
                                confirmButtonColor: '#009B77',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr);
                            let errorMessage = '{{ __('modules.gedungs.save_error') }}';
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
                if (!modal.hasClass('hidden')) {
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
                    const title = document.getElementById('modalTitle');
                    
                    // Update title and subtitle
                    if (title) {
                        title.textContent = '{{ __('modules.gedungs.edit_title') }}';
                        const subtitle = title.nextElementSibling;
                        if (subtitle) subtitle.textContent = '{{ __('modules.gedungs.update_subtitle') }}';
                    }
                    
                    // Fill form data
                    $('#gedungId').val(data.id);
                    $('#gedung_id_field').val(data.gedung_id);
                    $('#nama').val(data.nama);
                    
                    // Show modal using simplified function
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
                    text: '{{ __('modules.gedungs.fetch_error') }}',
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }
 
    function deleteGedung(id) {
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
            if (result.isConfirmed) {
                $.ajax({
                    url: destroyUrlTemplate.replace(':id', id),
                    type: 'POST',
                    data: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#gedungsTable').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __('modules.swal.success') }}',
                            text: '{{ __('modules.swal.data_deleted') }}',
                            confirmButtonColor: '#009B77'
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = '{{ __('modules.swal.delete_error') }}';
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

    // Export handler function
    window.handleExport = function(event, type) {
        event.preventDefault();
        
        const url = event.target.closest('a').href;
        const exportType = type === 'excel' ? 'Excel' : 'PDF';
        
        // Show loading with timeout warning
        Swal.fire({
            title: 'Mengekspor Data...',
            text: `Sedang membuat file ${exportType}, mohon tunggu.`,
            icon: 'info',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                
                // Show timeout warning after 20 seconds
                setTimeout(() => {
                    Swal.update({
                        title: 'Proses Memakan Waktu...',
                        text: `Export ${exportType} sedang diproses. File besar membutuhkan waktu lebih lama.`,
                        icon: 'warning'
                    });
                }, 20000);
            }
        });
        
        // For Excel, use direct download to avoid timeout issues
        if (type === 'excel') {
            const link = document.createElement('a');
            link.href = url;
            link.download = ''; // Let server set filename
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Check if download completed after delay
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Export Dimulai!',
                    text: `File ${exportType} sedang diunduh. Periksa folder download Anda.`,
                    timer: 3000,
                    showConfirmButton: false
                });
            }, 3000);
            
            return;
        }
        
        // For PDF, use fetch method
        fetch(url, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.blob();
            } else {
                throw new Error('Export failed');
            }
        })
        .then(blob => {
            const downloadUrl = window.URL.createObjectURL(blob);
            const downloadLink = document.createElement('a');
            downloadLink.href = downloadUrl;
            
            const contentDisposition = response.headers.get('content-disposition');
            let filename = `gedungs-${type}-${new Date().toISOString().split('T')[0]}.${type === 'excel' ? 'xlsx' : 'pdf'}`;
            
            if (contentDisposition) {
                const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                if (filenameMatch) {
                    filename = filenameMatch[1];
                }
            }
            
            downloadLink.download = filename;
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
            window.URL.revokeObjectURL(downloadUrl);
            
            Swal.fire({
                icon: 'success',
                title: 'Export Berhasil!',
                text: `File ${exportType} telah berhasil diunduh.`,
                timer: 2000,
                showConfirmButton: false
            });
        })
        .catch(error => {
            console.error('Export error:', error);
            
            // Fallback to direct link
            window.location.href = url;
            
            Swal.fire({
                icon: 'warning',
                title: 'Redirecting...',
                text: 'Mengunduh file menggunakan metode alternatif.',
                timer: 1500,
                showConfirmButton: false
            });
        });
    }
    </script>
    @endpush
</x-app-layout>