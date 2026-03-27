import './bootstrap';

// Phosphor Icons CSS now loaded via app.css import

// NOTE: jQuery, DataTables, SweetAlert2, ApexCharts, html5-qrcode are loaded
// as synchronous <script> tags from public/vendor/ in app.blade.php.
// This ensures they're available before inline scripts in blade templates run.
// Vite modules (type="module") are deferred and would load AFTER inline scripts.

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;

Alpine.plugin(collapse);

Alpine.start();

/**
 * Universal AJAX Form Handler with SweetAlert2 Confirmation
 * @param {string} formId - ID of the form element
 * @param {string} tableId - ID of the DataTable element
 * @param {string} modalId - ID of the modal element
 * @param {string} confirmTitle - Custom confirmation title (optional)
 * @param {string} confirmText - Custom confirmation text (optional)
 */
import Flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';
// Import Indonesian Locale
import { Indonesian } from 'flatpickr/dist/l10n/id.js';

window.Flatpickr = Flatpickr;

// Initialize Flatpickr globally
document.addEventListener('DOMContentLoaded', function() {
    initDatePickers();
});

// Re-initialize on dynamic content (e.g., DataTables draw, Modal open)
window.initDatePickers = function() {
    Flatpickr('input[type="date"]', {
        locale: Indonesian,
        altInput: true,
        altFormat: "j F Y",
        dateFormat: "Y-m-d",
        allowInput: true,
        monthSelectorType: 'static',
        yearSelectorType: 'static',
        disableMobile: "true", // Force custom picker on mobile for consistency
        onOpen: function(selectedDates, dateStr, instance) {
            // Add custom class for styling if needed
            instance.calendarContainer.classList.add('ebara-theme');
        }
    });

    // Also support datetime-local if needed
    Flatpickr('input[type="datetime-local"]', {
        locale: Indonesian,
        enableTime: true,
        altInput: true,
        altFormat: "j F Y H:i",
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        disableMobile: "true"
    });
};

// Expose initDatePickers to global scope so it can be called after AJAX
window.initDatePickers = window.initDatePickers;

// Universal AJAX Form Handler with SweetAlert2 Confirmation
// ... (rest of the file)
window.handleAjaxForm = function(formId, tableId, modalId, confirmTitle = 'Konfirmasi Simpan', confirmText = 'Apakah data yang dimasukkan sudah benar?') {
    // Prevent default form submission
    const form = document.getElementById(formId);
    if (!form) return;

    // Get form action and method
    const formAction = form.action;
    const formMethod = form.method.toUpperCase();

    // Fix aria-hidden conflict by removing focus before SweetAlert
    if (form && form.querySelector) {
        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
        if (submitButton) {
            submitButton.blur();
        }
    }
    
    // Show SweetAlert2 confirmation
    Swal.fire({
        title: confirmTitle,
        text: confirmText,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669', // Ebara Green
        cancelButtonColor: '#6b7280', // Gray
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        // Fix for production timing issues
        didOpen: function() {
            // Ensure proper focus management in SweetAlert
            // Remove any aria-hidden conflicts
            const mainContainer = document.querySelector('.flex.h-screen');
            if (mainContainer) {
                mainContainer.removeAttribute('aria-hidden');
            }
        },
        didClose: function() {
            // Restore focus after SweetAlert closes
            setTimeout(function() {
                if (submitButton && submitButton.style.display !== 'none') {
                    submitButton.focus();
                }
            }, 100);
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Create FormData
            const formData = new FormData(form);
            
            // Send AJAX request
            fetch(formAction, {
                method: formMethod,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' || data.success === true) {
                    // Success: Show SweetAlert2 success
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Data berhasil disimpan',
                        confirmButtonColor: '#059669',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });

                    // Hide modal with animation
                    if (modalId) {
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            var content = modal.querySelector('.modal-content');
                            if (content) {
                                content.classList.remove('modal-active');
                                setTimeout(function() {
                                    modal.classList.add('hidden');
                                    modal.style.display = 'none';
                                }, 300);
                            } else {
                                modal.classList.add('hidden');
                            }
                        }
                    }

                    // Reload DataTable
                    if (tableId) {
                        const table = $(tableId).DataTable();
                        table.ajax.reload(null, false);
                    }

                    // Reset form
                    form.reset();
                    // Re-init datepickers on reset if needed (usually handled by flatpickr)
                } else {
                    // Error: Show SweetAlert2 error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Terjadi kesalahan',
                        confirmButtonColor: '#dc2626'
                    });
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan jaringan',
                    confirmButtonColor: '#dc2626'
                });
            });
        }
    });
};

