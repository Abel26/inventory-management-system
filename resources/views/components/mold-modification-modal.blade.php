<!-- Mold Modification Modal -->
<div 
    x-data="moldModificationModal"
    x-show="showModal"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 bg-gray-900/50 z-50 flex items-center justify-center"
    style="display: none;"
    x-cloak
>
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-hidden" @click.away="closeModal()">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-900">Input Jadwal Modifikasi Cetakan</h3>
            <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                <i class="ph ph-x text-2xl"></i>
            </button>
        </div>
        
        <!-- Form -->
        <form @submit.prevent="submitForm()" class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Model Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Model Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        x-model="formData.model_name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors"
                        placeholder="Contoh: Casing 150-315"
                        required
                    >
                </div>
                
                <!-- Asset Model -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Asset Model <span class="text-red-500">*</span>
                    </label>
                    <select 
                        x-model="formData.asset_model_id"
                        @change="updateModelName()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors"
                        required
                    >
                        <option value="">Pilih Asset Model</option>
                        <template x-for="model in assetModels" :key="model.id">
                            <option :value="model.id" x-text="model.display_name"></option>
                        </template>
                    </select>
                </div>
                
                <!-- Spec Before -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Spec Before <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        x-model="formData.spec_before"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors"
                        placeholder="Contoh: PN 16"
                        required
                    >
                </div>
                
                <!-- Spec After -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Spec After <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        x-model="formData.spec_after"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors"
                        placeholder="Contoh: JIS 20 K"
                        required
                    >
                </div>
                
                <!-- Production Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Production Date <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        x-model="formData.production_date"
                        :min="minDate"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors"
                        required
                    >
                </div>
                
                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea 
                        x-model="formData.description"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors resize-none"
                        placeholder="Catatan tambahan tentang modifikasi..."
                    ></textarea>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end gap-3 mt-6">
                <button 
                    type="button" 
                    @click="closeModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    :disabled="loading"
                    class="px-4 py-2 bg-ebara-600 text-white rounded-lg hover:bg-ebara-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                >
                    <template x-if="!loading">
                        <i class="ph ph-check-circle"></i>
                    </template>
                    <template x-if="loading">
                        <i class="ph ph-spinner-gap animate-spin"></i>
                    </template>
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan'"></span>
                </button>
            </div>
        </form>
    </div>
</div>