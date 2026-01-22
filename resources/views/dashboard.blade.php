<x-app-layout>
    <x-slot name="header">
        Dashboard Eksekutif
    </x-slot>

    <x-slot name="description">
        Ringkasan performa inventaris dan laporan masalah.
    </x-slot>

    <!-- Main Content Wrapper - Forces Full Width with max-width for wide screens -->
    <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 bg-slate-50 -mx-4 sm:-mx-6 lg:-mx-8 py-8">
        
        <!-- Welcome Banner with Glassmorphism Effect -->
        <div class="mb-6 bg-gradient-to-br from-ebara-900 to-ebara-600 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <!-- Decorative Circle Blob -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute top-10 right-10 w-32 h-32 bg-white opacity-5 rounded-full blur-2xl"></div>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
                <div>
                    <h1 class="text-2xl font-bold mb-1">
                        Selamat Pagi, {{ auth()->user()->name ?? 'Admin' }}! 👋
                    </h1>
                    <p class="text-ebara-100">
                        Hari ini ada <span class="font-bold text-white">{{ $stats['totalCriticalIssues'] }}</span> laporan kritis yang perlu ditinjau.
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

        <!-- Quick Actions Toolbar -->
        <div class="mb-6 flex flex-wrap gap-3 items-center justify-between bg-white rounded-xl p-4 shadow-md border border-gray-100">
            <div class="flex flex-wrap gap-3">
                <a href="#" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all hover:shadow-sm">
                    <i class="ph ph-qr-code text-lg"></i>
                    <span class="text-sm font-medium">Scan QR</span>
                </a>
                <a href="{{ route('assets.materials.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all hover:shadow-sm">
                    <i class="ph ph-package text-lg"></i>
                    <span class="text-sm font-medium">Lihat Material</span>
                </a>
                <a href="{{ route('assets.tools.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all hover:shadow-sm">
                    <i class="ph ph-wrench text-lg"></i>
                    <span class="text-sm font-medium">Lihat Peralatan</span>
                </a>
                <a href="{{ route('assets.models.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all hover:shadow-sm">
                    <i class="ph ph-cube text-lg"></i>
                    <span class="text-sm font-medium">Lihat Model</span>
                </a>
            </div>
            <div class="relative w-full sm:w-auto" x-data="{ filterOpen: false }">
                <button @click="filterOpen = !filterOpen" class="flex items-center gap-2 px-4 py-2 bg-ebara-600 text-white rounded-lg hover:bg-ebara-700 transition-all shadow-md hover:shadow-lg">
                    <i class="ph ph-funnel text-lg"></i>
                    <span class="text-sm font-medium">Filter Waktu</span>
                    <i class="ph ph-caret-down"></i>
                </button>
                <div x-show="filterOpen" @click.away="filterOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                    <button @click="filterByTimeframe('month'); filterOpen = false" class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <i class="ph ph-calendar text-gray-400"></i>
                        Bulan Ini
                    </button>
                    <button @click="filterByTimeframe('quarter'); filterOpen = false" class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <i class="ph ph-calendar-check text-gray-400"></i>
                        Kuartal Ini
                    </button>
                    <button @click="filterByTimeframe('year'); filterOpen = false" class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 transition-colors flex items-center gap-2">
                        <i class="ph ph-calendar-blank text-gray-400"></i>
                        Tahun Ini
                    </button>
                </div>
            </div>
        </div>

        <!-- Dashboard Header with Search - Floating Effect -->
        <div class="mb-6">
            <div class="relative w-full" x-data="{ searchOpen: false, searchResults: [], searching: false }" x-init="document.addEventListener('keydown', (e) => { if ((e.metaKey || e.ctrlKey) && e.key === 'k') { e.preventDefault(); document.querySelector('input[type=\'text\']')?.focus(); } })">
                <input
                    type="text"
                    x-model="searchQuery"
                    @input="searchAssets"
                    @focus="searchOpen = true"
                    @click.away="searchOpen = false"
                    placeholder="Cari aset, alat, model..."
                    class="w-full pl-12 pr-16 py-3.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-all shadow-lg shadow-gray-200/50 focus:shadow-xl focus:shadow-ebara-200/30"
                >
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                <!-- Keyboard Shortcut Hint -->
                <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1 px-2 py-1 bg-gray-100 rounded-md border border-gray-200">
                    <span class="text-xs font-medium text-gray-500">⌘</span>
                    <span class="text-xs font-medium text-gray-500">K</span>
                </div>

                <!-- Search Results Dropdown -->
                <div
                    x-show="searchOpen && searchResults.length > 0"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50"
                >
                    <template x-for="result in searchResults" :key="result.id">
                        <a
                            :href="result.url"
                            class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0"
                        >
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i :class="['ph', result.icon, 'text-ebara-600 text-lg']"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900" x-text="result.name"></p>
                                <p class="text-xs text-gray-500">
                                    <span x-text="result.type"></span> • <span x-text="result.code"></span>
                                </p>
                            </div>
                        </a>
                    </template>
                </div>
            </div>
        </div>

        <!-- ROW 1: PULSE CARDS (4 Cards) - Responsive: Full on mobile, 2 on tablet, 4 on desktop -->
        <div class="grid grid-cols-12 gap-6 mb-6 w-full">
            <!-- Total Asset Value Card -->
            <a href="#" class="col-span-12 sm:col-span-6 xl:col-span-3 group w-full">
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Nilai Aset</p>
                            <p class="text-3xl font-bold text-gray-900">
                                Rp {{ number_format($stats['totalAssetValue'], 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="p-4 bg-ebara-50 rounded-2xl group-hover:bg-ebara-100 transition-colors">
                            <i class="ph ph-currency-dollar text-ebara-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <i class="ph ph-trend-up text-xs"></i>
                            <span>Aset terdaftar</span>
                        </span>
                    </div>
                </div>
            </a>

            <!-- Critical Issues Card -->
            <a href="{{ route('reports.index') }}?priority=Critical" class="col-span-12 sm:col-span-6 xl:col-span-3 group w-full">
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Masalah Kritis</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['totalCriticalIssues'] }}</p>
                        </div>
                        <div class="p-4 bg-red-50 rounded-2xl group-hover:bg-red-100 transition-colors">
                            <i class="ph ph-warning-octagon text-red-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <i class="ph ph-warning-circle text-xs"></i>
                            <span>Perlu perhatian</span>
                        </span>
                    </div>
                </div>
            </a>

            <!-- Low Stock Alerts Card -->
            <a href="{{ route('assets.materials.index') }}" class="col-span-12 sm:col-span-6 xl:col-span-3 group w-full">
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Stok Menipis</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['lowStockAlerts'] }}</p>
                        </div>
                        <div class="p-4 bg-yellow-50 rounded-2xl group-hover:bg-yellow-100 transition-colors">
                            <i class="ph ph-package text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <i class="ph ph-warning text-xs"></i>
                            <span>Perlu pesan ulang</span>
                        </span>
                    </div>
                </div>
            </a>

            <!-- Active Users Card -->
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 group w-full">
                <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">Pengguna Aktif</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['activeUsers'] }}</p>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-2xl group-hover:bg-blue-100 transition-colors">
                            <i class="ph ph-user-circle text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <i class="ph ph-users text-xs"></i>
                            <span>30 hari terakhir</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 2: PRIMARY ANALYTICS (The Big Charts) -->
        <div class="grid grid-cols-12 gap-6 mb-6 w-full">
            <!-- Left (8 Cols): Tren Pelaporan Masalah (Area Chart) -->
            <div class="col-span-12 lg:col-span-8 w-full" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <!-- Card Header with Icon -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-chart-line-up text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Tren Pelaporan Masalah</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Volume selama 12 bulan terakhir dengan gradien</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ph ph-dots-three text-gray-400 text-lg"></i>
                        </button>
                    </div>
                    
                    <!-- Chart Container with Loading Skeleton -->
                    <div class="relative w-full overflow-hidden min-h-[350px]">
                        <!-- Loading Skeleton -->
                        <div x-show="!loaded" class="absolute inset-0 flex items-center justify-center bg-gray-50 animate-pulse">
                            <div class="w-full h-64 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg"></div>
                        </div>
                        <!-- Chart -->
                        <div x-show="loaded" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" id="monthlyTrendChart" class="w-full h-[350px]"></div>
                    </div>
                </div>
            </div>

            <!-- Right (4 Cols): Kesehatan Sistem (Radial Gauge) -->
            <div class="col-span-12 lg:col-span-4 w-full" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <!-- Card Header with Icon -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-heartbeat text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Kesehatan Sistem</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Skor kondisi aset secara keseluruhan</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ph ph-dots-three text-gray-400 text-lg"></i>
                        </button>
                    </div>
                    
                    <!-- Chart Container with Loading Skeleton -->
                    <div class="relative w-full overflow-hidden min-h-[350px]">
                        <!-- Loading Skeleton -->
                        <div x-show="!loaded" class="absolute inset-0 flex items-center justify-center bg-gray-50 animate-pulse">
                            <div class="w-48 h-48 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-full"></div>
                        </div>
                        <!-- Chart Content -->
                        <div x-show="loaded" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="flex flex-col items-center justify-center p-4">
                            <div class="relative w-full overflow-hidden">
                                <div id="healthGaugeChart" class="w-full h-[250px]"></div>
                            </div>
                            <div class="mt-4 text-center">
                                <p class="text-4xl font-bold" :style="'color: ' . $charts['healthGauge']['color'] . ';">
                                    {{ $charts['healthGauge']['series'][0] }}%
                                </p>
                                <p class="text-sm font-medium text-gray-600 mt-1">
                                    {{ $charts['healthGauge']['status'] }}
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    {{ $charts['healthGauge']['healthyAssets'] }} / {{ $charts['healthGauge']['totalAssets'] }} aset sehat
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 3: DEEP DIVE (Radar + Stacked Column) -->
        <div class="grid grid-cols-12 gap-6 mb-6 w-full">
            <!-- Left (4 Cols): Performa Kategori (Radar Chart) -->
            <div class="col-span-12 lg:col-span-4 w-full" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <!-- Card Header with Icon -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-radar text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Performa Kategori</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Perbandingan multi-variabel Material, Peralatan, Cetakan</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ph ph-dots-three text-gray-400 text-lg"></i>
                        </button>
                    </div>
                    
                    <!-- Chart Container with Loading Skeleton -->
                    <div class="relative w-full overflow-hidden min-h-[384px]">
                        <!-- Loading Skeleton -->
                        <div x-show="!loaded" class="absolute inset-0 flex items-center justify-center bg-gray-50 animate-pulse">
                            <div class="w-64 h-64 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg"></div>
                        </div>
                        <!-- Chart -->
                        <div x-show="loaded" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" id="radarChart" class="w-full h-[384px]"></div>
                    </div>
                </div>
            </div>

            <!-- Right (8 Cols): Status Laporan (Stacked Column) -->
            <div class="col-span-12 lg:col-span-8 w-full" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <!-- Card Header with Icon -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-chart-bar text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Status Laporan</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Rincian bulanan dengan menunggu, diproses, dan selesai</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ph ph-dots-three text-gray-400 text-lg"></i>
                        </button>
                    </div>
                    
                    <!-- Chart Container with Loading Skeleton -->
                    <div class="relative w-full overflow-hidden min-h-[350px]">
                        <!-- Loading Skeleton -->
                        <div x-show="!loaded" class="absolute inset-0 flex items-center justify-center bg-gray-50 animate-pulse">
                            <div class="w-full h-64 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg"></div>
                        </div>
                        <!-- Chart -->
                        <div x-show="loaded" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" id="stackedColumnChart" class="w-full h-[350px]"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 4: DISTRIBUTION (Treemap - Full Width) -->
        <div class="grid grid-cols-12 gap-6 mb-6 w-full">
            <!-- Full Width (12 Cols): Hierarki Distribusi Aset (Treemap) -->
            <div class="col-span-12 w-full" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <!-- Card Header with Icon -->
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-squares-four text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Hierarki Distribusi Aset</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Visualisasi proporsi stok berdasarkan kategori aset</p>
                            </div>
                        </div>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ph ph-dots-three text-gray-400 text-lg"></i>
                        </button>
                    </div>
                    
                    <!-- Chart Container with Loading Skeleton -->
                    <div class="relative w-full overflow-hidden min-h-[500px]">
                        <!-- Loading Skeleton -->
                        <div x-show="!loaded" class="absolute inset-0 flex items-center justify-center bg-gray-50 animate-pulse">
                            <div class="w-full h-96 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg"></div>
                        </div>
                        <!-- Chart -->
                        <div x-show="loaded" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" id="treemapChart" class="w-full h-[500px]"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 5: ACTION ITEMS -->
        <div class="grid grid-cols-12 gap-6 w-full">
            <!-- Left (7 Cols): Aktivitas Terbaru (Timeline list) -->
            <div class="col-span-12 lg:col-span-7 w-full">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <!-- Card Header with Icon -->
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
                    
                    <!-- Activity List -->
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

            <!-- Right (5 Cols): Perlu Perhatian (Table of Critical/Empty Stock items) -->
            <div class="col-span-12 lg:col-span-5 w-full">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <!-- Card Header with Icon -->
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
                    
                    <!-- Critical Items Table -->
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

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Dashboard JavaScript -->
    <script>
        // Chart colors - Ebara palette with accent colors
        const colors = {
            ebara: '#009B77',
            ebaraLight: '#41CC93',
            ebaraDark: '#006653',
            success: '#10B981',
            warning: '#F59E0B',
            danger: '#EF4444',
            info: '#3B82F6',
            primary: '#008065',
            secondary: '#6B7280',
            grid: '#E5E7EB',
        };

        // Chart 1: Spline Area Chart for Monthly Trend
        const monthlyTrendOptions = {
            series: [{
                name: 'Laporan',
                data: @json($charts['monthlyTrend']['series'][0]['data'] ?? []),
            }],
            chart: {
                type: 'area',
                width: '100%',
                height: '100%',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                defaultLocale: 'id',
                locales: [{
                    name: 'id',
                    options: {
                        toolbar: {
                            download: 'Unduh SVG',
                            selection: 'Pilihan',
                            selectionZoom: 'Perbesar Pilihan',
                            zoomIn: 'Perbesar',
                            zoomOut: 'Perkecil',
                            pan: 'Geser',
                            reset: 'Reset',
                        },
                    },
                }],
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
                        if (event.dataPointIndex !== undefined) {
                            const month = config.xaxis.categories[event.dataPointIndex];
                            // Navigate to reports filtered by month
                            window.location.href = "{{ route('reports.index') }}?month=" + month;
                        }
                    }
                }
            },
            colors: [colors.ebara],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 90, 100],
                    colorStops: [
                        [0, colors.ebara],
                        [50, colors.ebaraLight],
                        [100, colors.ebaraDark],
                    ],
                },
            },
            stroke: {
                curve: 'smooth',
                width: 3,
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: @json($charts['monthlyTrend']['categories'] ?? []),
                labels: {
                    style: { colors: colors.secondary, fontSize: '12px' },
                },
                tooltip: { enabled: true },
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
                position: window.innerWidth < 768 ? 'bottom' : 'top',
                labels: { colors: colors.secondary },
            },
        };

        const monthlyTrendChart = new ApexCharts(document.querySelector('#monthlyTrendChart'), monthlyTrendOptions);
        monthlyTrendChart.render();

        // Chart 2: Radial Bar (Gauge) for System Health
        const healthGaugeOptions = {
            series: [{
                name: 'Skor Kesehatan',
                data: [@json($charts['healthGauge']['series'][0] ?? 0)],
            }],
            chart: {
                type: 'radialBar',
                width: '100%',
                height: '100%',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                defaultLocale: 'id',
                locales: [{
                    name: 'id',
                    options: {
                        toolbar: {
                            download: 'Unduh SVG',
                            selection: 'Pilihan',
                            selectionZoom: 'Perbesar Pilihan',
                            zoomIn: 'Perbesar',
                            zoomOut: 'Perkecil',
                            pan: 'Geser',
                            reset: 'Reset',
                        },
                    },
                }],
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                },
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        if (event.dataPointIndex !== undefined) {
                            // Navigate to assets filtered by health status
                            window.location.href = "{{ route('assets.materials.index') }}?status=healthy";
                        }
                    }
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    hollow: {
                        size: '70%',
                        margin: 15,
                        background: 'transparent',
                    },
                    track: {
                        background: '#e7e7e7',
                        strokeWidth: '97%',
                    },
                    dataLabels: {
                        name: {
                            show: true,
                            fontSize: '24px',
                            fontWeight: 'bold',
                            color: colors.secondary,
                            offsetY: 0,
                        },
                        value: {
                            show: false,
                        },
                    },
                },
            },
            colors: [@json($charts['healthGauge']['color'] ?? colors.ebara)],
            fill: {
                type: 'solid',
            },
            stroke: {
                lineCap: 'round',
            },
            labels: ['Kesehatan'],
            tooltip: {
                theme: 'dark',
            },
        };

        const healthGaugeChart = new ApexCharts(document.querySelector('#healthGaugeChart'), healthGaugeOptions);
        healthGaugeChart.render();

        // Chart 3: Radar Chart for Asset Category Performance
        const radarOptions = {
            series: @json($charts['radarData']['series'] ?? []),
            chart: {
                type: 'radar',
                width: '100%',
                height: '100%',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                defaultLocale: 'id',
                locales: [{
                    name: 'id',
                    options: {
                        toolbar: {
                            download: 'Unduh SVG',
                            selection: 'Pilihan',
                            selectionZoom: 'Perbesar Pilihan',
                            zoomIn: 'Perbesar',
                            zoomOut: 'Perkecil',
                            pan: 'Geser',
                            reset: 'Reset',
                        },
                    },
                }],
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                },
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        if (event.dataPointIndex !== undefined && event.seriesIndex !== undefined) {
                            const seriesName = config.series[event.seriesIndex].name;
                            // Navigate to assets filtered by category
                            if (seriesName === 'Material') {
                                window.location.href = "{{ route('assets.materials.index') }}";
                            } else if (seriesName === 'Peralatan') {
                                window.location.href = "{{ route('assets.tools.index') }}";
                            } else if (seriesName === 'Cetakan') {
                                window.location.href = "{{ route('assets.models.index') }}";
                            }
                        }
                    }
                }
            },
            stroke: {
                show: true,
                width: 2,
            },
            fill: {
                opacity: 0.2,
            },
            plotOptions: {
                radar: {
                    polygons: {
                        strokeColors: [colors.ebara],
                        connectorColors: [colors.grid],
                    },
                },
            },
            xaxis: {
                categories: @json($charts['radarData']['categories'] ?? []),
                labels: {
                    style: { colors: colors.secondary, fontSize: '12px' },
                },
            },
            yaxis: {
                show: false,
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '11px',
                    colors: [colors.secondary],
                },
                background: {
                    enabled: true,
                    borderRadius: 2,
                    foreColor: '#fff',
                    padding: 4,
                },
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) { return val.toFixed(0) + '%' },
                },
            },
            legend: {
                position: window.innerWidth < 768 ? 'bottom' : 'top',
                labels: { colors: colors.secondary },
            },
        };

        const radarChart = new ApexCharts(document.querySelector('#radarChart'), radarOptions);
        radarChart.render();

        // Chart 4: Stacked Column Chart for Issues by Status
        const stackedColumnOptions = {
            series: @json($charts['stackedColumnData']['series'] ?? []),
            chart: {
                type: 'bar',
                stacked: true,
                width: '100%',
                height: '100%',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                defaultLocale: 'id',
                locales: [{
                    name: 'id',
                    options: {
                        toolbar: {
                            download: 'Unduh SVG',
                            selection: 'Pilihan',
                            selectionZoom: 'Perbesar Pilihan',
                            zoomIn: 'Perbesar',
                            zoomOut: 'Perkecil',
                            pan: 'Geser',
                            reset: 'Reset',
                        },
                    },
                }],
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                },
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        if (event.dataPointIndex !== undefined && event.seriesIndex !== undefined) {
                            const seriesName = config.series[event.seriesIndex].name;
                            const month = config.xaxis.categories[event.dataPointIndex];
                            // Navigate to reports filtered by status and month
                            if (seriesName === 'Menunggu') {
                                window.location.href = "{{ route('reports.index') }}?status=Pending&month=" + month;
                            } else if (seriesName === 'Diproses') {
                                window.location.href = "{{ route('reports.index') }}?status=In%20Progress&month=" + month;
                            } else if (seriesName === 'Selesai') {
                                window.location.href = "{{ route('reports.index') }}?status=Resolved&month=" + month;
                            } else if (seriesName === 'Ditolak') {
                                window.location.href = "{{ route('reports.index') }}?status=Rejected&month=" + month;
                            }
                        }
                    }
                }
            },
            colors: [colors.warning, colors.info, colors.success, colors.danger],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '70%',
                },
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold',
                    colors: [colors.secondary],
                },
                formatter: function (val, opts) {
                    return val > 0 ? val : '';
                }
            },
            xaxis: {
                categories: @json($charts['stackedColumnData']['categories'] ?? []),
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
                y: {
                    formatter: function (val) { return val + ' masalah' },
                },
            },
            legend: {
                position: window.innerWidth < 768 ? 'bottom' : 'top',
                labels: { colors: colors.secondary },
            },
        };

        const stackedColumnChart = new ApexCharts(document.querySelector('#stackedColumnChart'), stackedColumnOptions);
        stackedColumnChart.render();

        // Chart 5: Treemap for Inventory Distribution
        // Filter out zero values to prevent blank treemap
        const treemapDataRaw = @json($charts['treemapData']['series'][0]['data'] ?? []);
        const treemapDataFiltered = treemapDataRaw.filter(item => item.value > 0);

        const treemapOptions = {
            series: [{
                data: treemapDataFiltered.length > 0 ? treemapDataFiltered : [{ x: 0, y: 0, value: 1, name: 'Tidak Ada Data', category: 'N/A' }]
            }],
            chart: {
                type: 'treemap',
                width: '100%',
                height: '100%',
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                defaultLocale: 'id',
                locales: [{
                    name: 'id',
                    options: {
                        toolbar: {
                            download: 'Unduh SVG',
                            selection: 'Pilihan',
                            selectionZoom: 'Perbesar Pilihan',
                            zoomIn: 'Perbesar',
                            zoomOut: 'Perkecil',
                            pan: 'Geser',
                            reset: 'Reset',
                        },
                    },
                }],
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                },
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        if (event.dataPointIndex !== undefined) {
                            const dataPoint = config.series[0].data[event.dataPointIndex];
                            const category = dataPoint.category;
                            // Navigate to assets filtered by category
                            if (category === 'Material') {
                                window.location.href = "{{ route('assets.materials.index') }}";
                            } else if (category === 'Peralatan') {
                                window.location.href = "{{ route('assets.tools.index') }}";
                            } else if (category === 'Cetakan') {
                                window.location.href = "{{ route('assets.models.index') }}";
                            }
                        }
                    }
                }
            },
            colors: [
                colors.ebara,
                colors.ebaraLight,
                colors.info,
                colors.warning,
                colors.success,
            ],
            plotOptions: {
                treemap: {
                    enableShades: true,
                    shadeIntensity: 0.3,
                    distributed: true,
                },
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold',
                    colors: '#fff',
                },
                formatter: function (val, opts) {
                    const dataPoint = opts.w.config.series[0].data[opts.dataPointIndex];
                    return dataPoint.category + ': ' + val + ' item';
                },
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) { return val + ' item' },
                },
            },
            legend: {
                show: false,
            },
        };

        const treemapChart = new ApexCharts(document.querySelector('#treemapChart'), treemapOptions);
        treemapChart.render();

        // Global Search Function
        function searchAssets(query) {
            if (query.length < 2) {
                this.searchResults = [];
                return;
            }

            fetch('{{ route('dashboard.search') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ query: query }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.searchResults = data.data;
                }
            })
            .catch(error => console.error('Search error:', error));
        }

        // Filter by Timeframe Function
        function filterByTimeframe(timeframe) {
            fetch('{{ route('dashboard.filter') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ timeframe: timeframe }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateAllCharts(data.data);
                }
            })
            .catch(error => console.error('Filter error:', error));
        }

        // Update All Charts with new data
        function updateAllCharts(chartsData) {
            // Update Monthly Trend
            monthlyTrendChart.updateOptions({
                xaxis: { categories: chartsData.monthlyTrend.categories },
            });
            monthlyTrendChart.updateSeries([{
                data: chartsData.monthlyTrend.series[0].data,
            }]);

            // Update Health Gauge
            healthGaugeChart.updateSeries([{
                data: [chartsData.healthGauge.series[0]],
            }]);
            healthGaugeChart.updateOptions({
                colors: [chartsData.healthGauge.color],
            });

            // Update Radar Chart
            radarChart.updateSeries(chartsData.radarData.series);

            // Update Stacked Column Chart
            stackedColumnChart.updateOptions({
                xaxis: { categories: chartsData.stackedColumnData.categories },
            });
            stackedColumnChart.updateSeries(chartsData.stackedColumnData.series);

            // Update Treemap Chart
            const treemapDataRaw = chartsData.treemapData.series[0].data;
            const treemapDataFiltered = treemapDataRaw.filter(item => item.value > 0);
            treemapChart.updateSeries([{
                data: treemapDataFiltered.length > 0 ? treemapDataFiltered : [{ x: 0, y: 0, value: 1, name: 'Tidak Ada Data', category: 'N/A' }]
            }]);
        }

        // Force resize all charts after page load to handle sidebar animation
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (monthlyTrendChart) monthlyTrendChart.resize();
                if (healthGaugeChart) healthGaugeChart.resize();
                if (radarChart) radarChart.resize();
                if (stackedColumnChart) stackedColumnChart.resize();
                if (treemapChart) treemapChart.resize();
            }, 500);
        });

        // Also resize on window resize
        window.addEventListener('resize', function() {
            const isMobile = window.innerWidth < 768;
            
            // Update legend position on resize
            if (monthlyTrendChart) {
                monthlyTrendChart.updateOptions({
                    legend: { position: isMobile ? 'bottom' : 'top' }
                });
                monthlyTrendChart.resize();
            }
            if (healthGaugeChart) healthGaugeChart.resize();
            if (radarChart) {
                radarChart.updateOptions({
                    legend: { position: isMobile ? 'bottom' : 'top' }
                });
                radarChart.resize();
            }
            if (stackedColumnChart) {
                stackedColumnChart.updateOptions({
                    legend: { position: isMobile ? 'bottom' : 'top' }
                });
                stackedColumnChart.resize();
            }
            if (treemapChart) treemapChart.resize();
        });
    </script>
</x-app-layout>
