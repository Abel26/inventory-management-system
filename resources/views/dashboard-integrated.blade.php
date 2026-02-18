<x-app-layout>
    <x-slot name="header">
        Dashboard Eksekutif 2.0
    </x-slot>

    <x-slot name="description">
        Dashboard modern dengan Dynamic Greetings, Omni-Search, dan Premium Visualizations.
    </x-slot>

    <!-- Main Content Wrapper -->
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 bg-slate-50 -mx-4 sm:-mx-6 lg:-mx-8 py-8">
        
        <!-- Welcome Banner with Glassmorphism Effect -->
        <div class="mb-6 bg-gradient-to-br from-ebara-900 via-ebara-700 to-ebara-600 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute top-10 right-10 w-32 h-32 bg-white opacity-5 rounded-full blur-2xl"></div>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
                <div>
                    <h1 class="text-3xl font-bold mb-1 bg-gradient-to-r from-white to-ebara-100 bg-clip-text text-transparent">
                        {{ $greeting ?? 'Selamat Datang' }}, {{ \Illuminate\Support\Facades\Auth::user()?->name ?? 'Admin' }}! 👋
                    </h1>
                    <p class="text-ebara-100">
                        Hari ini ada <span class="font-bold text-white">{{ $stats['totalCriticalIssues'] ?? 0 }}</span> laporan kritis yang perlu ditinjau.
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('reports.create') }}" class="flex items-center gap-2 px-4 py-2 bg-white text-ebara-600 rounded-xl hover:bg-ebara-50 transition-all font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="ph ph-plus-circle text-xl"></i>
                        <span>Lapor Masalah</span>
                    </a>
                    <a href="{{ route('dashboard.export-excel') }}" class="flex items-center gap-2 px-4 py-2 bg-ebara-800 bg-opacity-30 text-white rounded-xl hover:bg-opacity-40 transition-all font-medium shadow-lg hover:shadow-xl backdrop-blur-sm border border-white/20">
                        <i class="ph ph-download-simple text-xl"></i>
                        <span>Download Laporan</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Detail Modal UI -->
        <div
            x-data="{
                showModal: false,
                activeTab: 'history',
                selectedAsset: null,
                loading: false,
                closeModal() {
                    this.showModal = false;
                    this.selectedAsset = null;
                },
                async openModal(type, id) {
                    this.loading = true;
                    this.activeTab = 'history';
                    try {
                        const response = await fetch('{{ route('api.asset-detail') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                            },
                            body: JSON.stringify({ type, id }),
                        });
                        const result = await response.json();
                        if (result.success) {
                            this.selectedAsset = this.mapAssetData(result.data);
                            this.showModal = true;
                        }
                    } catch (error) {
                        console.error('Error fetching details:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                mapAssetData(data) {
                    // Map API response to UI expected format
                    return {
                        ...data,
                        status: data.status_label,
                        status_color: this.getStatusColor(data.status),
                        stock: data.quantity + ' ' + (data.unit || 'Unit'),
                        price: data.unit_price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(data.unit_price) : 
                                (data.purchase_price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(data.purchase_price) : '-'),
                        history: (data.reports || []).map(r => ({
                            issue: r.description,
                            date: r.created_at,
                            status: r.status,
                            user: r.user?.name || 'Unknown'
                        })),
                        show_url: data.detail_url,
                        image_url: null, // Placeholder or loop back if API supports it
                        category: data.category || data.type_name || '-',
                         // Ensure other fields exist
                        brand: data.brand || '-',
                        supplier: data.supplier || '-',
                        material: data.type === 'material' ? data.name : null
                    };
                },
                getStatusColor(status) {
                    if (['in_stock', 'Good', 'good'].includes(status)) return 'green';
                    if (['low_stock', 'Maintenance', 'Repair', 'maintenance', 'repair'].includes(status)) return 'yellow';
                    if (['low_stock', 'Maintenance', 'Repair', 'maintenance', 'repair'].includes(status)) return 'yellow';
                    return 'red';
                }
            }"
            x-show="showModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/50 z-[9999] flex items-center justify-center"
            style="display: none;"
            @click.away="showModal = false"
            @click.escape="closeModal()"
            @keydown.escape.window="closeModal()"
        >
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-hidden" @click.stop>
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
                                <span x-text="loading ? 'Menyimpan...' : 'Simpan'"></span>
                            </template>
                            <template x-if="loading">
                                <i class="ph ph-spinner-gap animate-spin"></i>
                                <span x-text="loading ? 'Menyimpan...' : 'Simpan'"></span>
                            </template>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>