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
                        {{ $greeting ?? __('dashboard.welcome') }}, {{ \Illuminate\Support\Facades\Auth::user()?->name ?? 'Admin' }}{{ __('dashboard.greeting_suffix') }} 👋
                    </h1>
                    <p class="text-ebara-100">
                        {!! __('dashboard.critical_reports_summary', ['count' => $stats['totalCriticalIssues'] ?? 0]) !!}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('reports.create') }}" class="flex items-center gap-2 px-4 py-2 bg-white text-ebara-600 rounded-xl hover:bg-ebara-50 transition-all font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="ph ph-plus-circle text-xl"></i>
                        <span>{{ __('dashboard.report_issue') }}</span>
                    </a>
                    <a href="{{ route('dashboard.export-excel') }}" class="flex items-center gap-2 px-4 py-2 bg-ebara-800 bg-opacity-30 text-white rounded-xl hover:bg-opacity-40 transition-all font-medium shadow-lg hover:shadow-xl backdrop-blur-sm border border-white/20">
                        <i class="ph ph-download-simple text-xl"></i>
                        <span>{{ __('dashboard.download_report') }}</span>
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
            class="fixed inset-0 bg-gray-900/50 z-50 flex items-center justify-center pointer-events-auto"
            style="display: none;"
            x-cloak
        >
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 flex flex-col max-h-[90vh] overflow-hidden" @click.away="closeModal()">
                <!-- Header -->
                <div class="flex justify-between items-center p-6 border-b border-gray-100">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900" x-text="selectedAsset?.name ?? '{{ __('dashboard.asset_detail') }}'"></h3>
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
                            <p class="text-xs text-gray-500">{{ __('dashboard.detail_modal.code') }}</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.code"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('dashboard.detail_modal.category') }}</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.category"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('dashboard.detail_modal.location') }}</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.location"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('dashboard.detail_modal.stock') }}</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.stock"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">{{ __('dashboard.detail_modal.price') }}</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.price"></p>
                        </div>
                        <div x-show="selectedAsset?.supplier">
                            <p class="text-xs text-gray-500">{{ __('dashboard.detail_modal.supplier') }}</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.supplier"></p>
                        </div>
                        <div x-show="selectedAsset?.brand">
                            <p class="text-xs text-gray-500">{{ __('dashboard.detail_modal.brand') }}</p>
                            <p class="font-medium text-gray-900" x-text="selectedAsset?.brand"></p>
                        </div>
                        <div x-show="selectedAsset?.type">
                            <p class="text-xs text-gray-500">{{ __('dashboard.detail_modal.type') }}</p>
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
                        >{{ __('dashboard.report_history') }}</button>
                        <button
                            class="py-2 px-4 font-medium text-sm border-b-2 transition-colors"
                            :class="activeTab === 'desc' ? 'border-ebara-600 text-ebara-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            @click="activeTab = 'desc'"
                        >{{ __('dashboard.description') }}</button>
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
                                <p class="text-sm">{{ __('dashboard.detail_modal.no_history') }}</p>
                            </div>
                        </template>
                    </div>
                    <div x-show="activeTab === 'desc'">
                        <div class="text-gray-700 text-sm whitespace-pre-line leading-relaxed" x-text="selectedAsset?.description || '{{ __('dashboard.detail_modal.no_description') }}'"/>
                    </div>
                </div>
                <!-- Footer -->
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
                    <a
                        :href="selectedAsset?.show_url"
                        class="bg-ebara-600 hover:bg-ebara-700 text-white font-semibold py-2 px-6 rounded-lg transition"
                    >
                        {{ __('dashboard.view_full_page') }}
                    </a>
                    <button type="button" @click="closeModal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                        {{ __('dashboard.close') }}
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
                        placeholder="{{ __('dashboard.search_placeholder') }}"
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
                        <p>{{ __('dashboard.search.no_results') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 0: MOLD MODIFICATION SCHEDULE (PINDAH KE ATAS) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 w-full mb-6" x-data="moldSchedule" x-init="moldModifications = window.dashboardMoldData || []">
            <!-- Mold Modification Schedule Widget -->
            <div class="col-span-12 w-full">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-calendar-check text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">{{ __('dashboard.mold_schedule_title') }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('dashboard.mold_schedule_subtitle') }}</p>
                            </div>
                        </div>
                        <button @click="showModal = true" class="flex items-center gap-2 px-4 py-2 bg-ebara-600 text-white rounded-lg hover:bg-ebara-700 transition-colors font-medium">
                            <i class="ph ph-plus-circle"></i>
                            <span>{{ __('dashboard.input_schedule') }}</span>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div class="overflow-x-auto max-w-full">
                            <table class="w-full min-w-[600px] divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">{{ __('dashboard.table.model') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">{{ __('dashboard.table.spec_before') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">{{ __('dashboard.table.spec_after') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">{{ __('dashboard.table.production_date') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">{{ __('dashboard.table.status') }}</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">{{ __('dashboard.table.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <template x-for="item in moldModifications" :key="item.id">
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 font-medium" x-text="item.asset_model?.model_name || item.model_name || '-'"></td>
                                            <td class="px-4 py-3 text-sm text-gray-700" x-text="item.spec_before || '-'"></td>
                                            <td class="px-4 py-3 text-sm text-gray-700" x-text="item.spec_after || '-'"></td>
                                            <td class="px-4 py-3 text-sm text-gray-700" x-text="item.production_date || '-'"></td>
                                            <td class="px-4 py-3 text-sm">
                                                <span :class="item.status === 'done' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'" class="px-3 py-1 rounded-full text-xs font-medium" x-text="item.status === 'done' ? '{{ __('dashboard.status.done') }}' : '{{ __('dashboard.status.pending') }}'"></span>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <div class="flex items-center gap-2">
                                                    <button x-show="item.status !== 'done'" @click="markAsDone(item.id)" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors duration-200 flex items-center gap-1">
                                                        <i class="ph ph-check text-sm"></i>
                                                        {{ __('dashboard.actions.mark_done') }}
                                                    </button>
                                                    <span x-show="item.status === 'done'" class="text-gray-500 text-xs">{{ __('dashboard.actions.done') }}</span>
                                             
                                                    <button x-show="item.status !== 'done'" @click="editMoldModification(item)" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors duration-200 flex items-center gap-1" title="Edit">
                                                        <i class="ph ph-pencil text-sm"></i>
                                                    </button>
                                                    <button x-show="item.status !== 'done'" @click="deleteMoldModification(item.id)" class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-colors duration-200 flex items-center gap-1" title="{{ __('dashboard.js.confirm_delete') }}">
                                                        <i class="ph ph-trash text-sm"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="moldModifications.length === 0">
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 text-sm">
                                                {{ __('dashboard.table.empty') }}
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal: Input Jadwal Baru -->
                <div x-show="showModal" x-cloak>
                    <div
                        id="mold-schedule-modal"
                        class="fixed inset-0 bg-gray-900/50 z-50 overflow-y-auto"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        @keydown.escape.window="showModal = false"
                    >
                        <div class="flex items-center justify-center min-h-screen p-4">
                            <div
                                class="relative bg-white rounded-xl shadow-2xl w-full mx-4 sm:max-w-2xl flex flex-col max-h-[90vh]"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                @click.away="showModal = false"
                            >
                                <!-- Header -->
                                <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">{{ __('dashboard.modal.title_create') }}</h3>
                                        <p class="text-sm text-gray-500" id="modalSubtitle">{{ __('dashboard.modal.subtitle') }}</p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="showModal = false"
                                        class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100"
                                        aria-label="Close modal"
                                    >
                                        <i class="ph ph-x text-2xl"></i>
                                    </button>
                                </div>

                                <!-- Body -->
                                <div class="p-6 overflow-y-auto flex-1">
                                    <form @submit.prevent="submitForm()" id="mold-schedule-form">
                                        <input type="hidden" x-model="editingId" name="id">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Asset Model ID -->
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('dashboard.modal.model_label') }} <span class="text-red-500">*</span></label>
                                                <select x-model="form.asset_model_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors">
                                                    <option value="">{{ __('dashboard.modal.model_placeholder') }}</option>
                                                    @foreach($assetModels as $model)
                                                        <option value="{{ $model->id }}">{{ $model->name }} @if($model->model_code)({{ $model->model_code }})@endif</option>
                                                    @endforeach
                                                </select>
                                                <p class="mt-1 text-xs text-gray-500">{{ __('dashboard.modal.model_help') }}</p>
                                            </div>
                                            
                                            <!-- Spec Before -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('dashboard.modal.spec_before_label') }} <span class="text-red-500">*</span></label>
                                                <textarea x-model="form.spec_before" placeholder="{{ __('dashboard.modal.spec_before_placeholder') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors" rows="3"></textarea>
                                            </div>
                                            
                                            <!-- Spec After -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('dashboard.modal.spec_after_label') }} <span class="text-red-500">*</span></label>
                                                <textarea x-model="form.spec_after" placeholder="{{ __('dashboard.modal.spec_after_placeholder') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors" rows="3"></textarea>
                                            </div>
                                            
                                            <!-- Production Date -->
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('dashboard.modal.date_label') }} <span class="text-red-500">*</span></label>
                                                <input type="date"
                                                       id="production-date-input"
                                                       x-model="form.production_date"
                                       required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ebara-500 focus:border-ebara-500 transition-colors" />
                                <p class="mt-1 text-xs text-gray-500">{{ __('dashboard.modal.date_help') }}</p>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Footer -->
                                <div class="flex flex-col sm:flex-row justify-end gap-3 p-6 border-t border-gray-200 flex-shrink-0">
                                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                                        {{ __('dashboard.modal.cancel') }}
                                    </button>
                                    <button type="submit" form="mold-schedule-form" :disabled="isSubmitting" class="px-4 py-2 bg-ebara-600 text-white rounded-lg hover:bg-ebara-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                                        <span x-text="isSubmitting ? '{{ __('dashboard.modal.saving') }}' : '{{ __('dashboard.modal.save') }}'"></span>
                                        <i x-show="!isSubmitting" class="ph ph-check-circle"></i>
                                        <i x-show="isSubmitting" class="ph ph-spinner-gap animate-spin"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
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
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('dashboard.cards.total_asset_value') }}</p>
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
                            <span>{{ __('dashboard.cards.registered_assets', ['count' => ($stats['totalMaterials'] ?? 0) + ($stats['totalTools'] ?? 0) + ($stats['totalModels'] ?? 0)]) }}</span>
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
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('dashboard.cards.critical_issues') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['totalCriticalIssues'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-red-50 rounded-2xl group-hover:bg-red-100 transition-colors">
                            <i class="ph ph-warning-octagon text-red-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 relative z-10">
                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <i class="ph ph-warning-circle text-xs"></i>
                            <span>{{ __('dashboard.cards.needs_attention') }}</span>
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
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('dashboard.cards.low_stock') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['lowStockAlerts'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-yellow-50 rounded-2xl group-hover:bg-yellow-100 transition-colors">
                            <i class="ph ph-package text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 relative z-10">
                        <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <i class="ph ph-warning text-xs"></i>
                            <span>{{ __('dashboard.cards.reorder_needed') }}</span>
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
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('dashboard.cards.active_users') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['activeUsers'] ?? 0 }}</p>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-2xl group-hover:bg-blue-100 transition-colors">
                            <i class="ph ph-user-circle text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 relative z-10">
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full flex items-center gap-1">
                            <i class="ph ph-users text-xs"></i>
                            <span>{{ __('dashboard.cards.last_30_days') }}</span>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- ROW 1.5: WORK LOG WIDGETS -->
        <div class="grid grid-cols-12 gap-6 mb-6 w-full">
            <!-- Today's Work Stats -->
            <div class="col-span-12 lg:col-span-6 w-full">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-calendar-check text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">{{ __('dashboard.work_logs.today_title') }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('dashboard.work_logs.today_subtitle') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('work-logs.my-work') }}" class="text-sm font-medium text-ebara-600 hover:text-ebara-700 flex items-center gap-1">
                            <span>{{ __('dashboard.work_logs.view_my_work') }}</span>
                            <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <!-- Total Today -->
                            <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                                <p class="text-3xl font-bold text-blue-700">{{ $workLogStats['today_total'] ?? 0 }}</p>
                                <p class="text-xs text-blue-600 mt-1">{{ __('dashboard.work_logs.cards.today_total') }}</p>
                            </div>
                            <!-- Completed Today -->
                            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                                <p class="text-3xl font-bold text-green-700">{{ $workLogStats['today_completed'] ?? 0 }}</p>
                                <p class="text-xs text-green-600 mt-1">{{ __('dashboard.work_logs.cards.today_completed') }}</p>
                            </div>
                            <!-- In Progress -->
                            <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl">
                                <p class="text-3xl font-bold text-yellow-700">{{ $workLogStats['today_in_progress'] ?? 0 }}</p>
                                <p class="text-xs text-yellow-600 mt-1">{{ __('dashboard.work_logs.cards.today_in_progress') }}</p>
                            </div>
                            <!-- Pending -->
                            <div class="text-center p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl">
                                <p class="text-3xl font-bold text-gray-700">{{ $workLogStats['today_pending'] ?? 0 }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ __('dashboard.work_logs.cards.today_pending') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Work Summary -->
            <div class="col-span-12 lg:col-span-6 w-full">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-chart-bar text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">{{ __('dashboard.work_logs.weekly_title') }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('dashboard.work_logs.weekly_subtitle') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('work-logs.index') }}" class="text-sm font-medium text-ebara-600 hover:text-ebara-700 flex items-center gap-1">
                            <span>{{ __('dashboard.work_logs.view_all') }}</span>
                            <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-3 gap-4">
                            <!-- Total This Week -->
                            <div class="text-center p-4 bg-gradient-to-br from-ebara-50 to-ebara-100 rounded-xl">
                                <p class="text-3xl font-bold text-ebara-700">{{ $workLogStats['total_this_week'] ?? 0 }}</p>
                                <p class="text-xs text-ebara-600 mt-1">{{ __('dashboard.work_logs.cards.total_this_week') }}</p>
                            </div>
                            <!-- In Progress -->
                            <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl">
                                <p class="text-3xl font-bold text-orange-700">{{ $workLogStats['in_progress'] ?? 0 }}</p>
                                <p class="text-xs text-orange-600 mt-1">{{ __('dashboard.work_logs.cards.in_progress') }}</p>
                            </div>
                            <!-- Pending -->
                            <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                                <p class="text-3xl font-bold text-purple-700">{{ $workLogStats['pending'] ?? 0 }}</p>
                                <p class="text-xs text-purple-600 mt-1">{{ __('dashboard.work_logs.cards.pending') }}</p>
                            </div>
                        </div>
                        
                        <!-- Completed This Week -->
                        <div class="mt-4 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">{{ __('dashboard.work_logs.cards.completed') }}</p>
                                <p class="text-2xl font-bold text-green-700">{{ $workLogStats['completed'] ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="ph ph-check-circle text-green-600 text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                <h3 class="font-bold text-gray-800">{{ __('dashboard.charts.asset_health_title') }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('dashboard.charts.asset_health_subtitle') }}</p>
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
                                <h3 class="font-bold text-gray-800">{{ __('dashboard.charts.inventory_composition_title') }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('dashboard.charts.inventory_composition_subtitle') }}</p>
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
                                <h3 class="font-bold text-gray-800">{{ __('dashboard.activity.title') }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('dashboard.activity.subtitle') }}</p>
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
                                    <p>{{ __('dashboard.activity.empty') }}</p>
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
                                <h3 class="font-bold text-gray-800">{{ __('dashboard.critical.title') }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ __('dashboard.critical.subtitle') }}</p>
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
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('dashboard.critical.table.item') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('dashboard.critical.table.status') }}</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('dashboard.critical.table.action') }}</th>
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
                                                    <x-ui.badge variant="danger">{{ $item['status_display'] ?? __('dashboard.status.critical') }}</x-ui.badge>
                                                @elseif($item['status'] == 'out_of_stock')
                                                    <x-ui.badge variant="danger">{{ $item['status_display'] ?? __('dashboard.status.out_of_stock') }}</x-ui.badge>
                                                @else
                                                    <x-ui.badge variant="warning">{{ $item['status_display'] ?? __('dashboard.status.low_stock') }}</x-ui.badge>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3">
                                                <a
                                                    href="{{ $item['action_url'] }}"
                                                    class="text-xs font-medium text-ebara-600 hover:text-ebara-700 flex items-center gap-1"
                                                >
                                                    <i class="ph ph-arrow-right"></i>
                                                    {{ __('dashboard.critical.view') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-3 py-8 text-center text-sm text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <i class="ph ph-check-circle text-3xl mb-2 text-green-500"></i>
                                                    <p>{{ __('dashboard.critical.empty') }}</p>
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

        </div>
    </div>

    <!-- Notification Container -->
    <div x-data="notificationSystem" class="fixed top-4 right-4 z-50 space-y-2">
        <template x-for="notification in notifications" :key="notification.id">
            <div x-show="notification.show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-full"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform translate-x-full"
                 class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
                 :class="{
                     'border-l-4 border-green-500': notification.type === 'success',
                     'border-l-4 border-red-500': notification.type === 'error',
                     'border-l-4 border-yellow-500': notification.type === 'warning',
                     'border-l-4 border-blue-500': notification.type === 'info'
                 }">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i x-show="notification.type === 'success'" class="ph ph-check-circle text-green-400 text-xl"></i>
                            <i x-show="notification.type === 'error'" class="ph ph-x-circle text-red-400 text-xl"></i>
                            <i x-show="notification.type === 'warning'" class="ph ph-warning text-yellow-400 text-xl"></i>
                            <i x-show="notification.type === 'info'" class="ph ph-info text-blue-400 text-xl"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                            <p class="mt-1 text-sm text-gray-500" x-text="notification.message"></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="removeNotification(notification.id)" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ebara-500">
                                <span class="sr-only">{{ __('dashboard.close') }}</span>
                                <i class="ph ph-x text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Confirmation Modal -->
    <div x-data="confirmationModal"
         x-show="show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 z-50 flex items-center justify-center"
         style="display: none;">
        <div class="bg-white rounded-lg shadow-xl max-w-md mx-4" @click.stop>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full"
                         :class="{
                             'bg-red-100': type === 'danger',
                             'bg-yellow-100': type === 'warning',
                             'bg-blue-100': type === 'info'
                         }">
                        <i x-show="type === 'danger'" class="ph ph-warning text-red-600 text-xl"></i>
                        <i x-show="type === 'warning'" class="ph ph-warning text-yellow-600 text-xl"></i>
                        <i x-show="type === 'info'" class="ph ph-info text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="title"></h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500" x-text="message"></p>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button @click="confirmAction()"
                            class="flex-1 justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition"
                            :class="{
                                'bg-red-600 hover:bg-red-700 focus:ring-red-500': type === 'danger',
                                'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500': type === 'warning',
                                'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500': type === 'info'
                            }">
                        <span x-text="confirmText"></span>
                    </button>
                    <button @click="cancel()"
                            class="flex-1 justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ebara-500 transition">
                        <span x-text="cancelText"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Mold data passed from server (used by Alpine component)
        window.dashboardMoldData = @json($moldModifications ?? []);
        window.assetModels = @json($assetModels ?? []);
        window.moldStoreUrl = '{{ route('mold-modifications.store') }}';
        window.moldUpdateUrl = '{{ route('mold-modifications.update', ':id') }}';
        window.moldDestroyUrl = '{{ route('mold-modifications.destroy', ':id') }}';
        window.moldUpdateStatusUrl = '{{ route('mold-modifications.update.status', ':id') }}';

        document.addEventListener('alpine:init', () => {
            // ========== NOTIFICATION SYSTEM ==========
            Alpine.data('notificationSystem', () => ({
                notifications: [],
                
                addNotification(type, title, message, duration = 5000) {
                    const id = Date.now() + Math.random();
                    const notification = {
                        id,
                        type,
                        title,
                        message,
                        show: true
                    };
                    
                    this.notifications.push(notification);
                    
                    // Auto remove after duration
                    if (duration > 0) {
                        setTimeout(() => {
                            this.removeNotification(id);
                        }, duration);
                    }
                    
                    return id;
                },
                
                removeNotification(id) {
                    const index = this.notifications.findIndex(n => n.id === id);
                    if (index !== -1) {
                        this.notifications[index].show = false;
                        setTimeout(() => {
                            this.notifications.splice(index, 1);
                        }, 300);
                    }
                },
                
                success(title, message, duration) {
                    return this.addNotification('success', title, message, duration);
                },
                
                error(title, message, duration) {
                    return this.addNotification('error', title, message, duration);
                },
                
                warning(title, message, duration) {
                    return this.addNotification('warning', title, message, duration);
                },
                
                info(title, message, duration) {
                    return this.addNotification('info', title, message, duration);
                }
            }));

            // ========== CONFIRMATION MODAL ==========
            Alpine.data('confirmationModal', () => ({
                show: false,
                type: 'info',
                title: '',
                message: '',
                confirmText: '{{ __('dashboard.js.yes_save') }}',
                cancelText: '{{ __('dashboard.js.cancel') }}',
                resolvePromise: null,
                rejectPromise: null,
                
                confirm(options = {}) {
                    return new Promise((resolve, reject) => {
                        this.type = options.type || 'info';
                        this.title = options.title || '{{ __('dashboard.js.confirm_save') }}';
                        this.message = options.message || '{{ __('dashboard.js.confirm_message_save') }}';
                        this.confirmText = options.confirmText || '{{ __('dashboard.js.yes_save') }}';
                        this.cancelText = options.cancelText || '{{ __('dashboard.js.cancel') }}';
                        this.resolvePromise = resolve;
                        this.rejectPromise = reject;
                        this.show = true;
                    });
                },
                
                confirmAction() {
                    if (this.resolvePromise) {
                        this.resolvePromise(true);
                    }
                    this.close();
                },
                
                cancel() {
                    if (this.rejectPromise) {
                        this.rejectPromise(false);
                    }
                    this.close();
                },
                
                close() {
                    this.show = false;
                    this.resolvePromise = null;
                    this.rejectPromise = null;
                }
            }));

            // ========== GLOBAL HELPER FUNCTIONS ==========
            window.showNotification = function(type, title, message, duration) {
                const notificationSystem = Alpine.$data(document.querySelector('[x-data="notificationSystem"]'));
                if (notificationSystem) {
                    return notificationSystem.addNotification(type, title, message, duration);
                }
            };
            
            window.showConfirm = function(options) {
                const confirmationModal = Alpine.$data(document.querySelector('[x-data="confirmationModal"]'));
                if (confirmationModal) {
                    return confirmationModal.confirm(options);
                }
                return Promise.resolve(true);
            };

            // ========== MOLD SCHEDULE COMPONENT ==========
            Alpine.data('moldSchedule', () => ({
                showModal: false,
                
                // Watch for showModal changes to handle modal display
                init() {
                    // Modal is now handled directly by Alpine.js x-show, no initialization needed
                },
                isSubmitting: false,
                isEditing: false,
                editingId: null,
                moldModifications: window.dashboardMoldData || [],
                assetModels: window.assetModels || [],
                form: {
                    asset_model_id: '',
                    spec_before: '',
                    spec_after: '',
                    production_date: '',
                    description: ''
                },

                async submitForm() {
                    if (this.isSubmitting) return;
                    
                    // Validate that a model is selected
                    if (!this.form.asset_model_id) {
                        showNotification('warning', '{{ __('dashboard.js.validation') }}', '{{ __('dashboard.js.select_model_first') }}');
                        return;
                    }
                    
                    // Validate required fields
                    if (!this.form.spec_before || !this.form.spec_after || !this.form.production_date) {
                        showNotification('warning', '{{ __('dashboard.js.validation') }}', '{{ __('dashboard.js.complete_required') }}');
                        return;
                    }
                    
                    // Show confirmation dialog
                        const confirmed = await showConfirm({
                        type: 'info',
                        title: this.isEditing ? '{{ __('dashboard.js.confirm_update') }}' : '{{ __('dashboard.js.confirm_save') }}',
                        message: this.isEditing ? '{{ __('dashboard.js.confirm_message_update', ['model' => ':model']) }}'.replace(':model', this.getSelectedModelName()) : '{{ __('dashboard.js.confirm_message_save', ['model' => ':model']) }}'.replace(':model', this.getSelectedModelName()),
                        confirmText: this.isEditing ? '{{ __('dashboard.js.yes_update') }}' : '{{ __('dashboard.js.yes_save') }}',
                        cancelText: '{{ __('dashboard.js.cancel') }}'
                    });
                    
                    if (!confirmed) return;
                    
                    this.isSubmitting = true;
                    const loadingNotificationId = showNotification('info', '{{ __('dashboard.js.saving_title') }}', this.isEditing ? '{{ __('dashboard.js.saving_message_update') }}' : '{{ __('dashboard.js.saving_message_save') }}', 0);

                    try {
                        // Add model_name to form data based on selected asset_model_id
                        const formData = {
                            ...this.form,
                            model_name: this.getSelectedModelName()
                        };

                        // Determine URL and method based on editing mode
                        const url = this.isEditing ?
                            window.moldUpdateUrl.replace(':id', this.editingId) :
                            window.moldStoreUrl;
                        const method = this.isEditing ? 'PUT' : 'POST';

                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify(formData)
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            // Handle validation errors
                            if (data.errors) {
                                let errorMessage = '{{ __('dashboard.js.validation_failed') }}:\n';
                                Object.keys(data.errors).forEach(key => {
                                    errorMessage += `- ${data.errors[key][0]}\n`;
                                });
                                showNotification('error', '{{ __('dashboard.js.validation_failed') }}', errorMessage);
                            } else {
                                showNotification('error', '{{ __('dashboard.js.save_failed') }}', data.message || (this.isEditing ? '{{ __('dashboard.js.fail_update') }}' : '{{ __('dashboard.js.fail_save') }}'));
                            }
                            return;
                        }

                        // Remove loading notification
                        if (loadingNotificationId) {
                            const notificationSystem = Alpine.$data(document.querySelector('[x-data="notificationSystem"]'));
                            if (notificationSystem) {
                                notificationSystem.removeNotification(loadingNotificationId);
                            }
                        }
                        
                        if (this.isEditing) {
                            // Update existing item in array
                            const index = this.moldModifications.findIndex(m => m.id === this.editingId);
                            if (index !== -1) {
                                this.moldModifications[index] = data.data;
                            }
                            showNotification('success', '{{ __('dashboard.js.success') }}', '{{ __('dashboard.js.success_update') }}');
                        } else {
                            // Add new item to array
                            this.moldModifications.unshift(data.data);
                            showNotification('success', '{{ __('dashboard.js.success') }}', '{{ __('dashboard.js.success_save') }}');
                        }
                        
                        this.resetForm();
                        this.showModal = false;
                    } catch (error) {
                        console.error('Submission error:', error);
                        showNotification('error', '{{ __('dashboard.js.error') }}', '{{ __('dashboard.js.error_occurred') }}' + error.message);
                    } finally {
                        // Remove loading notification in case of error
                        if (loadingNotificationId) {
                            const notificationSystem = Alpine.$data(document.querySelector('[x-data="notificationSystem"]'));
                            if (notificationSystem) {
                                notificationSystem.removeNotification(loadingNotificationId);
                            }
                        }
                        this.isSubmitting = false;
                    }
                },

                async markAsDone(id) {
                    // Find the modification to get model name for confirmation
                    const item = this.moldModifications.find(m => m.id === id); // Fix: use item instead of modification for consistency or handle variable naming
                    const modelName = item?.asset_model?.name || item?.model_name || '{{ __('dashboard.js.model_unknown') }}';
                    
                    const confirmed = await showConfirm({
                        type: 'success',
                        title: '{{ __('dashboard.js.confirm_done') }}',
                        message: '{{ __('dashboard.js.confirm_done_message', ['model' => ':model']) }}'.replace(':model', modelName),
                        confirmText: '{{ __('dashboard.js.yes_mark_done') }}',
                        cancelText: '{{ __('dashboard.js.cancel') }}'
                    });
                    
                    if (!confirmed) return;

                    try {
                        const response = await fetch(window.moldUpdateStatusUrl.replace(':id', id), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify({ status: 'done' })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            showNotification('error', '{{ __('dashboard.js.fail_update_status') }}', data.message || '{{ __('dashboard.js.fail_update_status') }}');
                            return;
                        }

                        const index = this.moldModifications.findIndex(m => m.id === id);
                        if (index !== -1) {
                            this.moldModifications[index] = data.data;
                        }
                        showNotification('success', '{{ __('dashboard.js.success') }}', '{{ __('dashboard.js.success_done') }}');
                    } catch (error) {
                        console.error('Update error:', error);
                        showNotification('error', '{{ __('dashboard.js.error') }}', '{{ __('dashboard.js.error_occurred') }}' + error.message);
                    }
                },

                resetForm() {
                    this.form = {
                        asset_model_id: '',
                        spec_before: '',
                        spec_after: '',
                        production_date: '',
                        description: ''
                    };
                    this.isEditing = false;
                    this.editingId = null;
                    
                    // Reset modal title and subtitle with validation
                    const titleElement = document.getElementById('modalTitle');
                    const subtitleElement = document.getElementById('modalSubtitle');
                    
                    if (titleElement) {
                        titleElement.textContent = '{{ __('dashboard.modal.title_create') }}';
                    }
                    if (subtitleElement) {
                        subtitleElement.textContent = '{{ __('dashboard.modal.subtitle') }}';
                    }

                    // Reset Flatpickr if exists
                    const dateInput = document.getElementById('production-date-input');
                    if (dateInput && dateInput._flatpickr) {
                        dateInput._flatpickr.clear();
                    }
                },

                async editMoldModification(item) {
                    if (!item || !item.id) {
                        showNotification('error', '{{ __('dashboard.js.error') }}', '{{ __('dashboard.js.item_invalid') }}');
                        return;
                    }
                    
                    // Set editing mode
                    this.isEditing = true;
                    this.editingId = item.id;
                    
                    // Enhanced date formatting
                    let formattedDate = '';
                    
                    if (item.production_date) {
                        // Extract YYYY-MM-DD from string or Date object
                        if (typeof item.production_date === 'string') {
                            // Match YYYY-MM-DD pattern (e.g. 2026-02-18 or 2026-02-18T00:00:00)
                            const match = item.production_date.match(/^(\d{4}-\d{2}-\d{2})/);
                            if (match) {
                                formattedDate = match[1];
                            } else {
                                // Try parsing as Date
                                const date = new Date(item.production_date);
                                if (!isNaN(date.getTime())) {
                                    formattedDate = date.toISOString().split('T')[0];
                                }
                            }
                        } else if (item.production_date instanceof Date) {
                             formattedDate = item.production_date.toISOString().split('T')[0];
                        }
                    }
                    
                    // Populate form with formatted data
                    this.form = {
                        asset_model_id: item.asset_model_id || '',
                        spec_before: item.spec_before || '',
                        spec_after: item.spec_after || '',
                        production_date: formattedDate,
                        description: item.description || ''
                    };
                    
                    // Show modal
                    this.showModal = true;
                    
                    // Update UI components after modal is shown
                    this.$nextTick(() => {
                        // Update Modal Title
                        const titleElement = document.getElementById('modalTitle');
                        const subtitleElement = document.getElementById('modalSubtitle');
                        
                        if (titleElement) titleElement.textContent = '{{ __('dashboard.modal.title_edit') }}';
                        if (subtitleElement) subtitleElement.textContent = '{{ __('dashboard.modal.subtitle_edit') }}';
                        
                        // Sync Flatpickr instance if it exists
                        const dateInput = document.getElementById('production-date-input');
                        if (dateInput) {
                            // If Flatpickr is attached (global init)
                            if (dateInput._flatpickr) {
                                if (formattedDate) {
                                    dateInput._flatpickr.setDate(formattedDate, true);
                                } else {
                                    dateInput._flatpickr.clear();
                                }
                            } else {
                                // Fallback for native input
                                dateInput.value = formattedDate;
                            }
                        }
                    });
                },



                async deleteMoldModification(id) {
                    const item = this.moldModifications.find(m => m.id === id);
                    const modelName = item?.asset_model?.name || item?.model_name || '{{ __('dashboard.js.model_unknown') }}';
                    
                    const confirmed = await showConfirm({
                        type: 'danger',
                        title: '{{ __('dashboard.js.confirm_delete') }}',
                        message: '{{ __('dashboard.js.confirm_delete_message', ['model' => ':model']) }}'.replace(':model', modelName),
                        confirmText: '{{ __('dashboard.js.yes_delete') }}',
                        cancelText: '{{ __('dashboard.js.cancel') }}'
                    });
                    
                    if (!confirmed) return;

                    try {
                        const response = await fetch(window.moldDestroyUrl.replace(':id', id), {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            showNotification('error', '{{ __('dashboard.js.fail_delete') }}', data.message || '{{ __('dashboard.js.fail_delete_message') }}');
                            return;
                        }

                        // Remove item from array
                        const index = this.moldModifications.findIndex(m => m.id === id);
                        if (index !== -1) {
                            this.moldModifications.splice(index, 1);
                        }
                        
                        showNotification('success', '{{ __('dashboard.js.success') }}', '{{ __('dashboard.js.success_delete') }}');
                    } catch (error) {
                        console.error('Delete error:', error);
                        showNotification('error', '{{ __('dashboard.js.error') }}', '{{ __('dashboard.js.error_occurred') }}' + error.message);
                    }
                },
                
                getSelectedModelName() {
                    const selectedModel = this.assetModels.find(model => model.id == this.form.asset_model_id);
                    return selectedModel ? selectedModel.name : '';
                }
            }));

            // ========== DASHBOARD SEARCH COMPONENT ==========
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

        // Legacy reference removed - using Alpine.data('moldSchedule') above

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
                y: { formatter: function (val) { return val + ' {{ __('dashboard.charts.reports_unit') }}' } },
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
                                label: '{{ __('dashboard.charts.total_assets') }}',
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

        // Alpine.js Mold Modification Schedule Component - REMOVED (using inline x-data)
    </script>
    @endpush
</x-app-layout>