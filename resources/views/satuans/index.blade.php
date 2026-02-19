<x-app-layout>
    <x-slot name="title">{{ __('modules.satuans.title') }}</x-slot>
    
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('modules.satuans.title') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('modules.satuans.subtitle') }}</p>
            </div>
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <button type="button" id="createNewSatuan" class="w-full md:w-auto bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2.5 px-4 rounded-xl inline-flex items-center justify-center gap-2 transition-colors">
                    <i class="ph ph-plus text-lg"></i>
                    <span>{{ __('modules.common.add_data') }}</span>
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Controls Header -->
            <div class="p-5 border-b border-gray-100 bg-white flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="{{ __('modules.satuans.search_placeholder') }}" class="pl-10 pr-4 py-2.5 bg-gray-50 border-transparent focus:bg-white focus:border-ebara-500 focus:ring-0 rounded-xl text-sm w-full md:w-72 transition-all">
                </div>
            </div>
            <table id="satuansTable" class="w-full" width="100%">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.no') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.code') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.satuans.unit_name') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.created_at') }}</th>
                        <th class="px-6 py-4 text-left font-semibold whitespace-nowrap">{{ __('modules.common.updated_at') }}</th>
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
                <p class="text-sm text-gray-500">{{ __('modules.satuans.empty_state') }}</p>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="satuanModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden" style="display: none;">
        <div class="flex items-center justify-center min-h-screen w-full p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95 opacity-0 modal-content">
                <!-- Modal Header with Gradient -->
                <div class="relative bg-gradient-to-r from-ebara-600 to-ebara-700 rounded-t-2xl p-6 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="ph ph-ruler text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold" id="modalTitle">{{ __('modules.satuans.add_title') }}</h3>
                                <p class="text-ebara-100 text-sm" id="modalSubtitle">{{ __('modules.satuans.add_subtitle') }}</p>
                            </div>
                        </div>
                        <button type="button" id="closeModalBtn" class="text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200 p-2 rounded-lg" tabindex="0" role="button" aria-label="{{ __('modules.common.close_modal') }}">
                            <i class="ph ph-x text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <form id="satuanForm" class="p-6 space-y-5" action="{{ route('master-data.satuans.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="satuanId" name="id">

                    <!-- Nama Satuan Field -->
                    <div class="space-y-2">
                        <label for="nama" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-text-aa text-ebara-600 mr-2"></i>
                            {{ __('modules.satuans.unit_name') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="nama"
                                   name="nama"
                                   required
                                   class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400"
                                   placeholder="{{ __('modules.satuans.name_placeholder') }}">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-text-aa text-gray-400 text-lg"></i>
                            </div>
                        </div>
                        @error('nama')
                            <p class="mt-1 text-xs text-red-500 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Kode Field -->
                    <div class="space-y-2">
                        <label for="kode" class="flex items-center text-sm font-semibold text-gray-700">
                            <i class="ph ph-hash text-ebara-600 mr-2"></i>
                            {{ __('modules.common.code') }}
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="kode"
                                   name="kode"
                                   required
                                   class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400"
                                   placeholder="{{ __('modules.satuans.code_placeholder') }}">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="ph ph-hash text-gray-400 text-lg"></i>
                            </div>
                        </div>
                        @error('kode')
                            <p class="mt-1 text-xs text-red-500 flex items-center">
                                <i class="ph ph-warning-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-5 border-t border-gray-200">
                        <button type="button" id="cancelBtn" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all duration-200 text-sm">
                            {{ __('modules.common.cancel') }}
                        </button>
                        <button type="button" id="submitSatuanBtn" class="px-5 py-2.5 bg-gradient-to-r from-ebara-600 to-ebara-700 hover:from-ebara-700 hover:to-ebara-800 text-white font-medium rounded-xl shadow-lg shadow-ebara-500/25 hover:shadow-ebara-500/40 transition-all duration-200 text-sm flex items-center justify-center gap-2">
                            <i class="ph ph-floppy-disk text-lg"></i>
                            {{ __('modules.common.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        #satuanModal {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50 !important;
            padding: 0 !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }
        
        #satuanModal .flex {
            width: 100% !important;
            height: 100% !important;
            min-height: 100vh !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 1rem !important;
            box-sizing: border-box !important;
        }
        
        #satuanModal.show {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        #satuanModal.hide, #satuanModal.hidden {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
        }
        
        #satuanModal .modal-content {
            animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: auto !important;
            position: relative;
            margin: 0;
            max-width: 90vw;
            width: 100%;
        }
        
        #satuanModal.show .modal-content {
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
        #satuanModal button {
            pointer-events: auto !important;
            position: relative !important;
            z-index: 10001 !important;
            cursor: pointer !important;
            transition: all 0.2s ease;
        }
        
        /* Fix any overlay issues */
        #satuanModal {
            pointer-events: auto !important;
        }
        
        /* Ensure modal is on top */
        #satuanModal.show {
            z-index: 50 !important;
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
            #satuanModal .modal-content {
                max-width: 500px !important;
                width: 100% !important;
            }
        }
        
        @media (max-width: 640px) {
            #satuanModal .modal-content {
                width: 95vw !important;
                max-width: 95vw !important;
                margin: 0 1rem !important;
            }
        }
        
        @media (max-width: 480px) {
            #satuanModal .modal-content {
                width: 98vw !important;
                max-width: 98vw !important;
                margin: 0 0.5rem !important;
            }
        }
        
        /* Fix for iOS Safari */
        @supports (-webkit-touch-callout: none) {
            #satuanModal {
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
    var storeUrl = "{{ route('master-data.satuans.store') }}";
    var showUrlTemplate = "{{ route('master-data.satuans.show', ':id') }}";
    var updateUrlTemplate = "{{ route('master-data.satuans.update', ':id') }}";
    var destroyUrlTemplate = "{{ route('master-data.satuans.destroy', ':id') }}";
    var dataUrl = "{{ route('master-data.satuans.data') }}";

    // Global functions - define outside document ready
    window.showModal = function() {
        console.log('Showing modal...');
        const modal = document.getElementById('satuanModal');
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
        const modal = document.getElementById('satuanModal');
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

    window.handleCreateNewSatuan = function() {
        console.log('Create handler called');
        try {
            const modal = document.getElementById('satuanModal');
            const title = document.getElementById('modalTitle');
            const subtitle = document.getElementById('modalSubtitle');
            const form = document.getElementById('satuanForm');
            const idField = document.getElementById('satuanId');
            
            if (title) title.textContent = '{{ __('modules.satuans.add_title') }}';
            if (subtitle) subtitle.textContent = '{{ __('modules.satuans.add_subtitle') }}';
            if (form) form.reset();
            if (idField) idField.value = '';
            
            showModal();
        } catch (error) {
            console.error('Error in create handler:', error);
        }
    }

    $(document).ready(function() {
        // Check if DataTables is loaded
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTables is not loaded');
            return;
        }
        
        // Initialize DataTables
        let table = $('#satuansTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: dataUrl,
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
                        return `
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="editSatuan(${row.id})" class="text-gray-400 hover:text-ebara-600 hover:bg-ebara-50 p-2 rounded-lg transition" title="{{ __('modules.common.edit') }}">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button onclick="deleteSatuan(${row.id})" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="{{ __('modules.common.delete') }}">
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
            initComplete: function(settings, json) {
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
                // Show/hide empty state based on data count
                let api = this.api();
                let count = api.page.info().recordsTotal;
                if (count === 0) {
                    $('#emptyState').removeClass('hidden');
                } else {
                    $('#emptyState').addClass('hidden');
                }
            },
        });

        // Connect search input to DataTables
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Create button handler
        $('#createNewSatuan').on('click', function() {
            handleCreateNewSatuan();
        });

        // Close button handlers
        $('#closeModalBtn').on('click', function() {
            hideModal();
        });

        $('#cancelBtn').on('click', function() {
            hideModal();
        });

        $('#submitSatuanBtn').on('click', function(e) {
            e.preventDefault();
            console.log('SATUANS SUBMIT BUTTON: Click event fired!'); // DEBUG
            console.log('SATUANS SUBMIT BUTTON: Button disabled:', $(this).prop('disabled')); // DEBUG
            console.log('SATUANS SUBMIT BUTTON: Button visible:', $(this).is(':visible')); // DEBUG
            console.log('SATUANS SUBMIT BUTTON: Form data before submission:', $('#satuanForm').serialize()); // DEBUG
            
            // Fix aria-hidden conflict by removing focus before SweetAlert
            $(this).blur();
            
            let id = $('#satuanId').val();
            let isEdit = id !== '';
            
            console.log('SATUANS SUBMIT BUTTON: Form ID:', id, 'Is Edit:', isEdit); // DEBUG
            console.log('SATUANS SUBMIT BUTTON: CSRF Token:', $('meta[name="csrf-token"]').attr('content')); // DEBUG
            
            // Show confirmation dialog for both create and edit
            let confirmTitle = isEdit ? '{{ __('modules.swal.confirm_title') }}' : '{{ __('modules.swal.confirm_title') }}';
            let confirmText = isEdit ? '{{ __('modules.swal.update_warning') }}' : '{{ __('modules.swal.save_warning') }}';
            
            // Store reference to button for later focus restoration
            var submitBtn = this;
            
            Swal.fire({
                title: confirmTitle,
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#009B77',
                cancelButtonColor: '#6b7280',
                confirmButtonText: isEdit ? '{{ __('modules.swal.yes_save') }}' : '{{ __('modules.swal.yes_save') }}',
                cancelButtonText: '{{ __('modules.swal.cancel') }}',
                // Fix for production timing issues
                didOpen: function() {
                    // Ensure proper focus management in SweetAlert
                    console.log('SATUANS SWAL: SweetAlert opened, fixing focus management');
                    // Remove any aria-hidden conflicts
                    $('.flex.h-screen').removeAttr('aria-hidden');
                },
                didClose: function() {
                    // Restore focus after SweetAlert closes
                    console.log('SATUANS SWAL: SweetAlert closed, restoring focus');
                    setTimeout(function() {
                        if (submitBtn && $(submitBtn).is(':visible')) {
                            submitBtn.focus();
                        }
                    }, 100);
                }
            }).then((result) => {
                console.log('SATUANS SUBMIT BUTTON: Swal result:', result); // DEBUG
                if (result.isConfirmed) {
                    console.log('SATUANS SUBMIT BUTTON: User confirmed, triggering form submission'); // DEBUG
                    // Trigger form submission manually instead of using submit()
                    $('#satuanForm').trigger('submit');
                } else {
                    console.log('SATUANS SUBMIT BUTTON: User cancelled submission'); // DEBUG
                }
            });
        });

        // Close modal when clicking outside
        $('#satuanModal').on('click', function(e) {
            if (e.target === this) {
                hideModal();
            }
        });

        // Close modal with ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = $('#satuanModal');
                if (modal.hasClass('show')) {
                    hideModal();
                }
            }
        });

        // Form submit handler
        $('#satuanForm').on('submit', function(e) {
            e.preventDefault();
            
            let id = $('#satuanId').val();
            let isEdit = id !== '';
            
            // Confirm before saving
            let confirmTitle = '{{ __('modules.swal.confirm_title') }}';
            let confirmText = isEdit ? '{{ __('modules.swal.update_warning') }}' : '{{ __('modules.swal.save_warning') }}';
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
                    let formData = $(this).serialize();
                    let url = storeUrl;
                    let method = 'POST';

                    if (id) {
                        url = updateUrlTemplate.replace(':id', id);
                        formData += '&_method=PUT';
                        // method remains POST because of _method spoofing
                    }

                    $.ajax({
                        url: url,
                        method: method,
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            hideModal();
                            $('#satuansTable').DataTable().ajax.reload();
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
                            let errorMessage = '{{ __('modules.swal.generic_error') }}';
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
                }
            });
        });
        } catch (error) {
            console.error('SATUANS FORM SUBMIT: Exception caught:', error); // DEBUG
            console.error('SATUANS FORM SUBMIT: Error stack:', error.stack); // DEBUG
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem: ' + error.message,
                confirmButtonColor: '#dc2626'
            });
            // Re-enable button on error
            $('#submitSatuanBtn').prop('disabled', false).html('<i class="ph ph-floppy-disk text-lg"></i> {{ __('modules.common.save') }}');
        }
    });

    function editSatuan(id) {
        console.log('Editing satuan with ID:', id);
        
        $.ajax({
            url: showUrlTemplate.replace(':id', id),
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            beforeSend: function() {
                // Show loading
                Swal.fire({
                    title: 'Memuat Data...',
                    text: 'Sedang mengambil data satuan.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                Swal.close();
                
                if (response.success && response.data) {
                    // Update modal title
                    const title = document.getElementById('modalTitle');
                    const subtitle = document.getElementById('modalSubtitle');
                    if (title) title.textContent = '{{ __('modules.satuans.edit_title') }}';
                    if (subtitle) subtitle.textContent = '{{ __('modules.satuans.edit_subtitle') }}';
                    
                    // Fill form with data
                    $('#satuanId').val(response.data.id);
                    $('#kode').val(response.data.kode);
                    $('#nama').val(response.data.nama);
                    
                    // Show modal
                    showModal();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Gagal memuat data satuan',
                        confirmButtonColor: '#dc2626'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                const errorMessage = handleAjaxError(xhr, '{{ __('modules.satuans.fetch_error') }}');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonColor: '#dc2626'
                });
            }
        });
    }

    function deleteSatuan(id) {
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
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus data satuan.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: destroyUrlTemplate.replace(':id', id),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.close();
                        handleAjaxSuccess(response, 'satuansTable', '{{ __('modules.swal.data_deleted') }}');
                    },
                    error: function(xhr) {
                        Swal.close();
                        const errorMessage = handleAjaxError(xhr, '{{ __('modules.swal.delete_error') }}');
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
    
    // Enhanced error handling for all AJAX requests
    function handleAjaxError(xhr, defaultMessage) {
        let errorMessage = defaultMessage;
        
        if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
            let errors = xhr.responseJSON.errors;
            errorMessage = '';
            for (let key in errors) {
                errorMessage += errors[key][0] + '\n';
            }
        } else if (xhr.statusText) {
            errorMessage = xhr.statusText;
        }
        
        return errorMessage;
    }
    
    // Enhanced success handling with auto reload
    function handleAjaxSuccess(response, tableId, successMessage, reloadTable = true) {
        if (response.success) {
            if (reloadTable) {
                $('#' + tableId).DataTable().ajax.reload();
            }
            Swal.fire({
                icon: 'success',
                title: '{{ __('modules.swal.success') }}',
                text: response.message || successMessage,
                confirmButtonColor: '#009B77',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Terjadi kesalahan',
                confirmButtonColor: '#dc2626'
            });
        }
    }
    </script>
    @endpush
</x-app-layout>