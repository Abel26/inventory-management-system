<x-app-layout>
    <x-slot name="title">{{ __('modules.asset_tools.title') }}: {{ $tool->name }}</x-slot>

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('assets.tools.index') }}" class="w-10 h-10 bg-white rounded-xl shadow-sm border border-gray-200 flex items-center justify-center text-gray-400 hover:text-ebara-600 hover:border-ebara-200 transition-all">
                    <i class="ph ph-arrow-left text-xl"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                            {{ __('modules.asset_tools.title') }}
                        </span>
                        <span class="text-gray-300">•</span>
                        <span class="text-sm font-mono text-gray-500">{{ $tool->tool_code }}</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $tool->name }}</h1>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="window.print()" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-xl inline-flex items-center gap-2 shadow-sm transition">
                    <i class="ph ph-printer text-lg"></i>
                    <span>Cetak</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Panel: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Overview Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                        <h2 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="ph ph-info text-ebara-600"></i>
                            Informasi Peralatan
                        </h2>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                            @if($tool->condition === 'Good') bg-green-100 text-green-800 border-green-200
                            @elseif($tool->condition === 'Repair') bg-yellow-100 text-yellow-800 border-yellow-200
                            @elseif($tool->condition === 'Damaged') bg-red-100 text-red-800 border-red-200
                            @else bg-gray-100 text-gray-800 border-gray-200
                            @endif border">
                            {{ $tool->condition_label }}
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.category') }}</label>
                                    <p class="text-gray-900 mt-1 font-medium">{{ __('modules.asset_tools.category_' . \Illuminate\Support\Str::snake($tool->category)) }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.brand') }}</label>
                                    <p class="text-gray-900 mt-1 font-medium">{{ $tool->brand ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.type') }}</label>
                                    <p class="text-gray-900 mt-1 font-medium">{{ $tool->type ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.location') }}</label>
                                    <div class="flex items-center gap-2 mt-1 text-gray-900 font-medium">
                                        <i class="ph ph-map-pin-line text-ebara-600"></i>
                                        {{ $tool->location ?? '-' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.stock') }}</label>
                                    <div class="flex items-end gap-2 mt-1">
                                        <span class="text-3xl font-bold text-gray-900">{{ $tool->quantity }}</span>
                                        <span class="text-gray-500 mb-1 font-medium">{{ $tool->unit ?? 'Unit' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Purchase Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2 mb-6">
                        <i class="ph ph-receipt text-ebara-600"></i>
                        Informasi Pembelian
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                <i class="ph ph-calendar text-2xl text-ebara-600"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('modules.asset_tools.purchase_date') }}</p>
                                <p class="text-gray-900 font-semibold">{{ $tool->purchase_date ? $tool->purchase_date->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                <i class="ph ph-currency-circle-dollar text-2xl text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('modules.asset_tools.purchase_price') }}</p>
                                <p class="text-gray-900 font-semibold">Rp {{ number_format($tool->purchase_price ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                <i class="ph ph-shield-check text-2xl text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Garansi Hingga</p>
                                <p class="text-gray-900 font-semibold">{{ $tool->warranty_expiry ? $tool->warranty_expiry->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2 mb-4">
                        <i class="ph ph-article text-ebara-600"></i>
                        {{ __('modules.common.description') }}
                    </h2>
                    <div class="text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-xl italic">
                        {{ $tool->description ?: 'Tidak ada deskripsi tambahan.' }}
                    </div>
                </div>
            </div>

            <!-- Right Panel: QR & Metadata -->
            <div class="space-y-6">
                <!-- QR Code Card -->
                <div class="bg-gradient-to-br from-ebara-600 to-ebara-800 rounded-2xl shadow-lg p-6 text-white text-center">
                    <h3 class="font-bold text-lg mb-4">Scan QR Peralatan</h3>
                    <div class="bg-white p-4 rounded-xl inline-block shadow-inner mb-4">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->generate($tool->tool_code) !!}
                    </div>
                    <p class="font-mono text-sm opacity-90 tracking-widest">{{ $tool->tool_code }}</p>
                </div>

                <!-- Meta Info Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                            <span class="text-sm text-gray-500">{{ __('modules.common.created_at') }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $tool->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                            <span class="text-sm text-gray-500">{{ __('modules.common.updated_at') }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $tool->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
