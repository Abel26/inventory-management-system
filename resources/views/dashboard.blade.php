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
                        {{ $greeting ?? 'Selamat Datang' }}, {{ auth()->user()->name ?? 'Admin' }}! 👋
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
                        status_color: this.getStatusColor(data.status), // using raw status for color logic
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
                    return 'red';
                }
            }"
            @open-asset-modal.window="openModal($event.detail.type, $event.detail.id)"
            x-show="showModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 bg-gray-900/50 z-[9999] flex items-center justify-center pointer-events-auto"
            style="display: none;"
            x-cloak
        >
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 flex flex-col max-h-[90vh] overflow-hidden" @click.away="closeModal()">
                <!-- Header -->
                <div class="flex justify-between items-center p-6 border-b border-gray-100">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900" x-text="selectedAsset?.name ?? 'Detail Aset'"></h3>
                        <span
                            class="ml-2 px-3 py-1 text-sm font-medium rounded-full"
                            :class="{
                                'bg-green-100 text-green-700': selectedAsset?.status_color === 'green',
                                'bg-yellow-100 text-yellow-700': selectedAsset?.status_color === 'yellow',
                                'bg-red-100 text-red-700': selectedAsset?.status_color === 'red',
                                'bg-gray-100 text-gray-700': !['green','yellow','red'].includes(selectedAsset?.status_color)
                            }"
                            x-text="selectedAsset?.status"
                        ></span>
                    </div>
                    <button type="button" @click="closeModal" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                </div>
                <!-- Body -->
                <div class="flex flex-col md:flex-row gap-8 p-6 overflow-y-auto flex-1">
                    <!-- Left: Image/Icon + QR -->
                    <div class="flex flex-col items-center justify-center gap-4 w-full md:w-1/3">
                        <div class="w-32 h-32 bg-gray-50 rounded-xl border flex items-center justify-center overflow-hidden">
                            <template x-if="selectedAsset?.image_url">
                                <img :src="selectedAsset.image_url" alt="Asset Image" class="object-cover w-full h-full rounded-xl" />
                            </template>
                            <template x-if="!selectedAsset?.image_url">
                                <i class="ph ph-cube text-6xl text-ebara-600" x-show="selectedAsset?.type === 'model'"></i>
                                <i class="ph ph-wrench text-6xl text-ebara-600" x-show="selectedAsset?.type === 'tool'"></i>
                                <i class="ph ph-package text-6xl text-ebara-600" x-show="selectedAsset?.type === 'material'"></i>
                                <i class="ph ph-question text-6xl text-gray-400" x-show="!['model','tool','material'].includes(selectedAsset?.type)"></i>
                            </template>
                        </div>
                        <template x-if="selectedAsset?.qr_code_path">
                            <!-- Assuming qr_code_path is relative, might need full url logic if not handled by accessor -->
                            <img :src="'/storage/' + selectedAsset.qr_code_path" alt="QR Code" class="w-24 h-24 rounded-lg border" />
                        </template>
                    </div>
                    <!-- Right: Specs Grid -->
                    <div class="flex-1 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Kode</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.code"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Kategori</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.category"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Lokasi</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.location"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Stok</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.stock"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Harga</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.price"></p>
                        </div>
                        <div x-show="selectedAsset?.supplier">
                            <p class="text-xs text-gray-500">Supplier</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.supplier"></p>
                        </div>
                        <div x-show="selectedAsset?.brand">
                            <p class="text-xs text-gray-500">Merk</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.brand"></p>
                        </div>
                        <div x-show="selectedAsset?.type">
                            <p class="text-xs text-gray-500">Tipe</p>
                            <p class="font-medium text-gray-900 capitalize" x-text="selectedAsset?.type"></p>
                        </div>
                    </div>
                </div>
                <!-- Tabs Section -->
                <div class="px-6 pb-6">
                    <div class="flex gap-4 border-b mb-4">
                        <button
                            class="py-2 px-4 font-medium text-sm border-b-2 transition-colors"
                            :class="activeTab === 'history' ? 'border-ebara-600 text-ebara-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            @click="activeTab = 'history'"
                        >Riwayat Laporan</button>
                        <button
                            class="py-2 px-4 font-medium text-sm border-b-2 transition-colors"
                            :class="activeTab === 'desc' ? 'border-ebara-600 text-ebara-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            @click="activeTab = 'desc'"
                        >Deskripsi</button>
                    </div>
                    <div x-show="activeTab === 'history'">
                        <template x-if="selectedAsset?.history?.length > 0">
                            <ul class="space-y-3">
                                <template x-for="item in selectedAsset.history" :key="item.date + item.issue">
                                    <li class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="mt-1">
                                            <i class="ph ph-warning-circle text-ebara-600 text-xl"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900" x-text="item.issue"></p>
                                            <div class="flex flex-wrap gap-2 text-xs text-gray-500 mt-1">
                                                <span x-text="item.date"></span> •
                                                <span class="font-medium" x-text="item.status"></span> •
                                                <span x-text="item.user"></span>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </template>
                        <template x-if="!selectedAsset?.history || selectedAsset?.history.length === 0">
                            <div class="text-center py-8 text-gray-400">
                                <i class="ph ph-check-circle text-3xl mb-2"></i>
                                <p class="text-sm">Belum ada riwayat laporan</p>
                            </div>
                        </template>
                    </div>
                    <div x-show="activeTab === 'desc'">
                        <div class="text-gray-700 text-sm whitespace-pre-line leading-relaxed" x-text="selectedAsset?.description || 'Tidak ada deskripsi'"/>
                    </div>
                </div>
                <!-- Footer -->
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
                    <a
                        :href="selectedAsset?.show_url"
                        class="bg-ebara-600 hover:bg-ebara-700 text-white font-semibold py-2 px-6 rounded-lg transition"
                    >
                        Lihat Halaman Penuh
                    </a>
                    <button type="button" @click="closeModal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

        <!-- Omni Search Bar with Glassmorphism -->
        <div class="mb-6" x-data="dashboardSearch">
            <div class="relative w-full" x-init="document.addEventListener('keydown', (e) => { if ((e.metaKey || e.ctrlKey) && e.key === 'k') { e.preventDefault(); $refs.searchInput.focus(); } })">
                <div class="relative">
                    <input
                        x-ref="searchInput"
                        type="text"
                        x-model="query"
                        @input.debounce.300ms="performSearch()"
                        @focus="showResults = true"
                        @click.away="showResults = false"
                        placeholder="Cari aset, material, tools, models... (⌘K)"
                        class="w-full pl-14 pr-16 py-4 bg-white/80 backdrop-blur-md border border-white/20 rounded-2xl focus:ring-2 focus:ring-ebara-500/50 focus:border-ebara-500 transition-all shadow-lg shadow-gray-200/50 focus:shadow-xl focus:shadow-ebara-200/30 text-gray-800 placeholder-gray-500"
                    >
                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                        <div x-show="!isSearching" class="text-gray-400">
                            <i class="ph ph-magnifying-glass text-2xl"></i>
                        </div>
                        <div x-show="isSearching" class="text-ebara-600">
                            <i class="ph ph-spinner-gap text-2xl animate-spin"></i>
                        </div>
                    </div>
                    <div x-show="query.length > 0" @click="clearSearch()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
                        <i class="ph ph-x text-xl"></i>
                    </div>
                </div>

                <!-- Search Results Dropdown -->
                <div
                    x-show="showResults && results.length > 0"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                    class="absolute top-full left-0 right-0 mt-2 bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20 overflow-hidden z-50 max-h-96 overflow-y-auto"
                >
                    <template x-for="result in results" :key="`${result.type}-${result.id}`">
                        <div
                            @click="openDetail(result.type, result.id)"
                            class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0 cursor-pointer group"
                        >
                            <div class="p-2 bg-ebara-100 rounded-lg group-hover:bg-ebara-200 transition-colors">
                                <i :class="['ph', result.icon, 'text-ebara-600 text-lg']"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-ebara-700 transition-colors" x-text="result.name"></p>
                                <p class="text-xs text-gray-500">
                                    <span class="capitalize" x-text="result.type"></span> • <span x-text="result.code"></span>
                                    <span x-show="result.quantity" class="ml-2">• Qty: <span x-text="result.quantity"></span></span>
                                    <span x-show="result.location" class="ml-2">• <span x-text="result.location"></span></span>
                                </p>
                            </div>
                            <div class="text-right">
                                <div x-show="result.recent_issues > 0" class="flex items-center gap-1">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                        <span x-text="result.recent_issues"></span>
                                    </span>
                                    <i class="ph ph-warning text-red-500 text-sm"></i>
                                </div>
                                <div x-show="result.status" class="mt-1">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                                          :class="{
                                              'bg-green-100 text-green-700': result.status === 'in_stock' || result.status === 'good',
                                              'bg-yellow-100 text-yellow-700': result.status === 'low_stock' || result.status === 'maintenance',
                                              'bg-red-100 text-red-700': result.status === 'out_of_stock' || result.status === 'damaged'
                                          }"
                                          x-text="result.status_label"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="results.length === 0 && !isSearching && query.length >= 2" class="p-4 text-center text-gray-500">
                        <i class="ph ph-search-slash text-2xl mb-2"></i>
                        <p>Tidak ada hasil ditemukan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 1: PULSE CARDS with Sparklines -->
        <div class="grid grid-cols-12 gap-6 mb-6 w-full">
            <!-- Total Asset Value Card -->
            <a href="{{ route('assets.materials.index') }}" class="col-span-12 sm:col-span-6 xl:col-span-3 group w-full" title="Klik untuk lihat Materials (Komponen Utama)">
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10">
                        <div id="sparkline-value" class="w-full h-full"></div>
                    </div>
                    <div class="flex items-start justify-between mb-4 relative z-10">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Nilai Aset</p>
                            <p class="text-3xl font-bold text-gray-900">
                                Rp {{ number_format($stats['totalAssetValue'] ?? 0, 0, ',', '.') }}
                            </p>
                            
                            <!-- Value Breakdown Tooltip/Text -->
                            <div class="mt-2 text-xs text-gray-500 space-y-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="flex justify-between">
                                    <span>Material:</span>
                                    <span>Rp {{ number_format($stats['valueBreakdown']['material'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Tools:</span>
                                    <span>Rp {{ number_format($stats['valueBreakdown']['tool'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 bg-ebara-50 rounded-2xl group-hover:bg-ebara-100 transition-colors h-fit">
                            <i class="ph ph-currency-dollar text-ebara-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 relative z-10">
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <i class="ph ph-trend-up text-xs"></i>
                            <span>{{ $stats['totalMaterials'] + $stats['totalTools'] + $stats['totalModels'] }} Aset terdaftar</span>
                        </span>
                    </div>
                </div>
            </a>

            <!-- Critical Issues Card -->
            <a href="{{ route('reports.index', ['priority' => 'Critical']) }}" class="col-span-12 sm:col-span-6 xl:col-span-3 group w-full">
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10">
                        <div id="sparkline-critical" class="w-full h-full"></div>
                    </div>
                    <div class="flex items-start justify-between mb-4 relative z-10">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Masalah Kritis</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['totalCriticalIssues'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-red-50 rounded-2xl group-hover:bg-red-100 transition-colors">
                            <i class="ph ph-warning-octagon text-red-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 relative z-10">
                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <i class="ph ph-warning-circle text-xs"></i>
                            <span>Perlu perhatian</span>
                        </span>
                    </div>
                </div>
            </a>

            <!-- Low Stock Alerts Card -->
            <a href="{{ route('assets.materials.index', ['filter' => 'low_stock']) }}" class="col-span-12 sm:col-span-6 xl:col-span-3 group w-full">
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10">
                        <div id="sparkline-stock" class="w-full h-full"></div>
                    </div>
                    <div class="flex items-start justify-between mb-4 relative z-10">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Stok Menipis</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['lowStockAlerts'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-yellow-50 rounded-2xl group-hover:bg-yellow-100 transition-colors">
                            <i class="ph ph-package text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 relative z-10">
                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <i class="ph ph-warning text-xs"></i>
                            <span>Perlu pesan ulang</span>
                        </span>
                    </div>
                </div>
            </a>

            <!-- Active Users Card -->
            <a href="{{ route('users.index') }}" class="col-span-12 sm:col-span-6 xl:col-span-3 group w-full">
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all hover:-translate-y-1 relative overflow-hidden">
                    <div class="absolute top-0 right-0 opacity-10">
                        <div id="sparkline-users" class="w-full h-full"></div>
                    </div>
                    <div class="flex items-start justify-between mb-4 relative z-10">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Pengguna Aktif</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['activeUsers'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-2xl group-hover:bg-blue-100 transition-colors">
                            <i class="ph ph-user-circle text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 relative z-10">
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <i class="ph ph-users text-xs"></i>
                            <span>30 hari terakhir</span>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- ROW 2: PRIMARY ANALYTICS -->
        <div class="grid grid-cols-12 gap-6 mb-6 w-full">
            <!-- Asset Health Trends (Gradient Line Chart) -->
            <div class="col-span-12 lg:col-span-8 w-full" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-chart-line-up text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Tren Kesehatan Aset</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Total laporan vs laporan selesai (7 hari terakhir)</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ph ph-dots-three text-gray-400 text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="relative w-full overflow-hidden min-h-[350px]">
                        <div x-show="!loaded" class="absolute inset-0 flex items-center justify-center bg-gray-50 animate-pulse">
                            <div class="w-full h-64 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg"></div>
                        </div>
                        <div x-show="loaded" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" id="healthTrendChart" class="w-full h-[350px]"></div>
                    </div>
                </div>
            </div>

            <!-- Inventory Composition (Donut Chart) -->
            <div class="col-span-12 lg:col-span-4 w-full" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-pie-chart text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Komposisi Inventaris</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Distribusi material, tools, dan models</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ph ph-dots-three text-gray-400 text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="relative w-full overflow-hidden min-h-[350px]">
                        <div x-show="!loaded" class="absolute inset-0 flex items-center justify-center bg-gray-50 animate-pulse">
                            <div class="w-48 h-48 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-full"></div>
                        </div>
                        <div x-show="loaded" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" id="compositionChart" class="w-full h-[350px]"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 3: ACTIVITY & CRITICAL ITEMS -->
        <div class="grid grid-cols-12 gap-6 w-full">
            <!-- Recent Activity -->
            <div class="col-span-12 lg:col-span-7 w-full">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-clock-counter-clockwise text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Aktivitas Terbaru</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Aksi terbaru dalam sistem</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ph ph-dots-three text-gray-400 text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($activity as $item)
                                <div class="flex items-start gap-4">
                                    <div class="p-3 rounded-lg @if($item['color'] == 'danger') bg-red-100 @elseif($item['color'] == 'warning') bg-yellow-100 @elseif($item['color'] == 'info') bg-blue-100 @else bg-gray-100 @endif">
                                        <i class="ph {{ $item['icon'] }} @if($item['color'] == 'danger') text-red-600 @elseif($item['color'] == 'warning') text-yellow-600 @elseif($item['color'] == 'info') text-blue-600 @else text-gray-600 @endif text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">{{ $item['message'] }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $item['time'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class="ph ph-clock-counter-clockwise text-4xl mb-2"></i>
                                    <p>Tidak ada aktivitas terbaru</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Critical Items -->
            <div class="col-span-12 lg:col-span-5 w-full">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-warning text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Perlu Perhatian</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Item yang memerlukan tindakan segera</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ph ph-dots-three text-gray-400 text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Item</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($critical as $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-3">
                                                <p class="text-sm font-medium text-gray-900 truncate max-w-[120px]" title="{{ $item['name'] }}">{{ $item['name'] }}</p>
                                                <p class="text-xs text-gray-500">{{ $item['code'] }}</p>
                                            </td>
                                            <td class="px-3 py-3">
                                                @if($item['status'] == 'critical')
                                                    <x-ui.badge variant="danger">{{ $item['status_display'] ?? 'Kritis' }}</x-ui.badge>
                                                @elseif($item['status'] == 'out_of_stock')
                                                    <x-ui.badge variant="danger">{{ $item['status_display'] ?? 'Stok Habis' }}</x-ui.badge>
                                                @else
                                                    <x-ui.badge variant="warning">{{ $item['status_display'] ?? 'Stok Menipis' }}</x-ui.badge>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3">
                                                <a
                                                    href="{{ $item['action_url'] }}"
                                                    class="text-xs font-medium text-ebara-600 hover:text-ebara-700 flex items-center gap-1"
                                                >
                                                    <i class="ph ph-arrow-right"></i>
                                                    Lihat
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-3 py-8 text-center text-sm text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <i class="ph ph-check-circle text-3xl mb-2 text-green-500"></i>
                                                    <p>Semua item dalam kondisi baik!</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ApexCharts CDN with fallback -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Fallback check for ApexCharts
        window.addEventListener('load', function() {
            if (typeof ApexCharts === 'undefined') {
                console.warn('ApexCharts CDN failed to load, attempting fallback...');
                // Try to load from another CDN
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/apexcharts';
                script.onload = function() {
                    console.log('ApexCharts loaded from fallback CDN');
                    // Re-initialize charts if they failed initially
                    if (typeof initializeCharts === 'function') {
                        initializeCharts();
                    }
                };
                script.onerror = function() {
                    console.error('Failed to load ApexCharts from all sources');
                };
                document.head.appendChild(script);
            }
        });
    </script>

    @push('scripts')
    <script>
        // Alpine.js Dashboard Search Component
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboardSearch', () => ({
                query: '',
                results: [],
                isSearching: false,
                showResults: false,
                
                async performSearch() {
                    if (this.query.length < 2) { 
                        this.results = []; 
                        this.showResults = false; 
                        return; 
                    }
                    
                    this.isSearching = true;
                    
                    try {
                        const response = await fetch('{{ route('api.global-search') }}', { 
                            method: 'POST', 
                            headers: { 
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify({ query: this.query }) 
                        });
                        
                        const data = await response.json();
                        this.results = data.data || [];
                        this.showResults = true;
                    } catch (error) {
                        console.error('Search error:', error);
                    } finally {
                        this.isSearching = false;
                    }
                },
                
                clearSearch() {
                    this.query = '';
                    this.results = [];
                    this.showResults = false;
                },
                
                openDetail(type, id) {
                    // Dispatch event to open modal
                    window.dispatchEvent(new CustomEvent('open-asset-modal', { 
                        detail: { type: type, id: id } 
                    }));
                    this.showResults = false;
                }
            }));
        });

        // Chart colors - Modern palette with Ebara theme
        const colors = {
            ebara: '#009B77',
            ebaraLight: '#41CC93',
            ebaraDark: '#006653',
            success: '#10B981',
            warning: '#F59E0B',
            danger: '#EF4444',
            info: '#3B82F6',
            purple: '#8B5CF6',
            pink: '#EC4899',
            primary: '#008065',
            secondary: '#6B7280',
            grid: '#E5E7EB',
            light: '#F3F4F6',
            dark: '#1F2937',
        };

        // Chart data from server
        const trendData = @json($chartData['trend']);
        const compositionData = @json($chartData['composition']);

        // Initialize charts function
        function initializeCharts() {
            // Check if ApexCharts is available
            if (typeof ApexCharts === 'undefined') {
                console.error('ApexCharts library is not loaded');
                return;
            }

            // Initialize chart variables
            let healthTrendChart, compositionChart, sparklineValue, sparklineCritical, sparklineStock, sparklineUsers;

            try {
                // Main Charts
                const healthTrendElement = document.querySelector('#healthTrendChart');
                if (healthTrendElement) {
                    healthTrendChart = new ApexCharts(healthTrendElement, healthTrendOptions);
                    healthTrendChart.render();
                }

                const compositionElement = document.querySelector('#compositionChart');
                if (compositionElement) {
                    compositionChart = new ApexCharts(compositionElement, compositionOptions);
                    compositionChart.render();
                }

                // Sparklines
                const sparklineValueElement = document.querySelector('#sparkline-value');
                if (sparklineValueElement) {
                    sparklineValue = new ApexCharts(sparklineValueElement, sparklineOptions);
                    sparklineValue.render();
                }

                const sparklineCriticalElement = document.querySelector('#sparkline-critical');
                if (sparklineCriticalElement) {
                    sparklineCritical = new ApexCharts(sparklineCriticalElement, { ...sparklineOptions, colors: [colors.danger] });
                    sparklineCritical.render();
                }

                const sparklineStockElement = document.querySelector('#sparkline-stock');
                if (sparklineStockElement) {
                    sparklineStock = new ApexCharts(sparklineStockElement, { ...sparklineOptions, colors: [colors.warning] });
                    sparklineStock.render();
                }

                const sparklineUsersElement = document.querySelector('#sparkline-users');
                if (sparklineUsersElement) {
                    sparklineUsers = new ApexCharts(sparklineUsersElement, { ...sparklineOptions, colors: [colors.info] });
                    sparklineUsers.render();
                }

                // Handle window resize with null checks
                window.addEventListener('resize', function() {
                    if (healthTrendChart && typeof healthTrendChart.resize === 'function') {
                        healthTrendChart.resize();
                    }
                    if (compositionChart && typeof compositionChart.resize === 'function') {
                        compositionChart.resize();
                    }
                    if (sparklineValue && typeof sparklineValue.resize === 'function') {
                        sparklineValue.resize();
                    }
                    if (sparklineCritical && typeof sparklineCritical.resize === 'function') {
                        sparklineCritical.resize();
                    }
                    if (sparklineStock && typeof sparklineStock.resize === 'function') {
                        sparklineStock.resize();
                    }
                    if (sparklineUsers && typeof sparklineUsers.resize === 'function') {
                        sparklineUsers.resize();
                    }
                });
            } catch (error) {
                console.error('Error initializing charts:', error);
            }
        }

        // Chart 1: Asset Health Trends (Gradient Line Chart)
        const healthTrendOptions = {
            series: trendData.series,
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    dynamicAnimation: {
                        enabled: true,
                        speed: 400,
                    },
                },
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        if (config.dataPointIndex !== undefined && config.seriesIndex !== undefined) {
                            const dateIndex = config.dataPointIndex;
                            const selectedDate = trendData.dates[dateIndex];
                            if (selectedDate) {
                                // Redirect to reports filtered by date
                                window.location.href = `/reports?date=${selectedDate}`;
                            }
                        }
                    }
                }
            },
            colors: [colors.danger, colors.success],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 90, 100],
                },
            },
            stroke: {
                curve: 'smooth',
                width: 3,
            },
            xaxis: {
                categories: trendData.categories,
                labels: {
                    style: { colors: colors.secondary, fontSize: '12px' },
                },
            },
            yaxis: {
                labels: {
                    style: { colors: colors.secondary, fontSize: '12px' },
                    formatter: function (val) { return val.toFixed(0) },
                },
            },
            grid: {
                borderColor: colors.grid,
                strokeDashArray: 4,
            },
            tooltip: {
                theme: 'dark',
                y: { formatter: function (val) { return val + ' laporan' } },
            },
            legend: {
                position: 'top',
                labels: { colors: colors.secondary },
            },
        };

        // Chart 2: Inventory Composition (Donut Chart)
        const compositionOptions = {
            series: compositionData.series,
            chart: {
                type: 'donut',
                height: 350,
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        if (config.dataPointIndex !== undefined) {
                            const assetTypes = ['material', 'tool', 'model'];
                            const selectedType = assetTypes[config.dataPointIndex];
                            if (selectedType) {
                                // Redirect to assets filtered by type
                                window.location.href = `/assets/${selectedType}s`;
                            }
                        }
                    }
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Aset',
                                fontSize: '24px',
                                fontWeight: 'bold',
                                color: colors.dark,
                                formatter: function () {
                                    return compositionData.counts.reduce((a, b) => a + b, 0);
                                }
                            },
                        },
                    },
                },
            },
            labels: compositionData.labels,
            colors: [colors.ebara, colors.ebaraLight, colors.ebaraDark],
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return opts.w.globals.labels[opts.seriesIndex] + ': ' + compositionData.percentages[opts.seriesIndex] + '%';
                },
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold',
                    colors: ['#fff'],
                },
                background: {
                    enabled: true,
                    borderRadius: 2,
                    foreColor: '#fff',
                    padding: 4,
                },
            },
            legend: {
                position: 'bottom',
                labels: { colors: colors.secondary },
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val, opts) {
                        return val + ' item (' + compositionData.percentages[opts.seriesIndex] + '%)';
                    },
                },
            },
        };

        // Sparklines for Stats Cards
        const sparklineOptions = {
            series: [{
                data: [4, 3, 6, 5, 8, 7, 9, 6, 8, 7, 6, 5, 8, 9],
            }],
            chart: {
                type: 'line',
                height: 80,
                width: 120,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'smooth',
                width: 2,
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                },
            },
            colors: [colors.ebara],
            tooltip: {
                enabled: false,
            },
        };

        // Initialize charts when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });
    </script>
    @endpush
</x-app-layout>