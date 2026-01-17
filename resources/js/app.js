import './bootstrap';

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
window.handleAjaxForm = function(formId, tableId, modalId, confirmTitle = 'Konfirmasi Simpan', confirmText = 'Apakah data yang dimasukkan sudah benar?') {
    // Prevent default form submission
    const form = document.getElementById(formId);
    if (!form) return;

    // Get form action and method
    const formAction = form.action;
    const formMethod = form.method.toUpperCase();

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
        reverseButtons: true
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

                    // Hide modal
                    if (modalId) {
                        const modal = document.getElementById(modalId);
                        if (modal) modal.classList.add('hidden');
                    }

                    // Reload DataTable
                    if (tableId) {
                        const table = $(tableId).DataTable();
                        table.ajax.reload(null, false);
                    }

                    // Reset form
                    form.reset();
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
