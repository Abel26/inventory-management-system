// Alpine.js Mold Modification Modal Component
document.addEventListener('alpine:init', () => {
    Alpine.data('moldModificationModal', () => ({
        showModal: false,
        loading: false,
        assetModels: [],
        formData: {
            asset_model_id: '',
            model_name: '',
            spec_before: '',
            spec_after: '',
            production_date: '',
            description: ''
        },
        minDate: new Date().toISOString().split('T')[0],
        
        init() {
            // Load asset models when component initializes
            this.loadAssetModels();
            
            // Set minimum date for production date
            const today = new Date();
            today.setDate(today.getDate() + 1);
            this.minDate = today.toISOString().split('T')[0];
        },
        
        async loadAssetModels() {
            try {
                const response = await fetch('/mold-modifications/asset-models', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    this.assetModels = data.data;
                }
            } catch (error) {
                console.error('Error loading asset models:', error);
            }
        },
        
        updateModelName() {
            const selectedModel = this.assetModels.find(model => model.id == this.formData.asset_model_id);
            if (selectedModel && !this.formData.model_name) {
                this.formData.model_name = selectedModel.name;
            }
        },
        
        resetForm() {
            this.formData = {
                asset_model_id: '',
                model_name: '',
                spec_before: '',
                spec_after: '',
                production_date: '',
                description: ''
            };
        },
        
        closeModal() {
            this.showModal = false;
            this.resetForm();
        },
        
        async submitForm() {
            this.loading = true;
            
            try {
                const response = await fetch('/mold-modifications', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(this.formData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.closeModal();
                    // Show success notification
                    this.showNotification('Jadwal modifikasi berhasil ditambahkan', 'success');
                    // Emit event to refresh data instead of reload
                    this.$dispatch('mold-modification-updated', {
                        action: 'created',
                        data: result.data
                    });
                } else {
                    this.showNotification(result.message || 'Gagal menambahkan jadwal', 'error');
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                this.showNotification('Terjadi kesalahan saat menyimpan data', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        async markAsDone(id) {
            if (!confirm('Apakah Anda yakin ingin menandai ini sebagai selesai?')) {
                return;
            }
            
            try {
                const response = await fetch(`/mold-modifications/${id}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ status: 'done' })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.showNotification('Status berhasil diperbarui', 'success');
                    // Emit event to refresh data instead of reload
                    this.$dispatch('mold-modification-updated', {
                        action: 'status-updated',
                        id: id,
                        data: result.data
                    });
                } else {
                    this.showNotification(result.message || 'Gagal memperbarui status', 'error');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                this.showNotification('Terjadi kesalahan saat memperbarui status', 'error');
            }
        },
        
        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            
            // Set color based on type
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                warning: 'bg-yellow-500 text-white',
                info: 'bg-blue-500 text-white'
            };
            
            notification.className += ' ' + (colors[type] || colors.info);
            notification.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="ph ${type === 'success' ? 'ph-check-circle' : type === 'error' ? 'ph-warning-circle' : 'ph-info'} text-xl"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    }));
});

// Make data available globally for dashboard
window.moldModalData = {
    showModal: false,
    markAsDone: function(id) {
        // Find the Alpine component and call the method
        const component = Alpine.$data(document.querySelector('[x-data*="moldModificationModal"]'));
        if (component && component.markAsDone) {
            component.markAsDone(id);
        }
    }
};