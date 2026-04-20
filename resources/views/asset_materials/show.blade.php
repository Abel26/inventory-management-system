<x-app-layout>
    <x-slot name="title">{{ __('modules.asset_materials.title') }}: {{ $material->name }}</x-slot>

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('assets.materials.index') }}" class="w-10 h-10 bg-white rounded-xl shadow-sm border border-gray-200 flex items-center justify-center text-gray-400 hover:text-ebara-600 hover:border-ebara-200 transition-all">
                    <i class="ph ph-arrow-left text-xl"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="bg-ebara-100 text-ebara-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                            {{ __('modules.asset_materials.title') }}
                        </span>
                        <span class="text-gray-300">•</span>
                        <span class="text-sm font-mono text-gray-500">{{ $material->material_code }}</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $material->name }}</h1>
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
                            Informasi Umum
                        </h2>
                        <div class="px-3 py-1 rounded-full text-xs font-semibold {{ $material->stock_status_color }} bg-opacity-10 border border-current border-opacity-20">
                            {{ $material->stock_status_label }}
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.type') }}</label>
                                    <p class="text-gray-900 mt-1 font-medium">{{ __('modules.asset_materials.type_' . $material->type) }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.supplier') }}</label>
                                    <p class="text-gray-900 mt-1 font-medium">{{ $material->supplier ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.location') }}</label>
                                    <div class="flex items-center gap-2 mt-1 text-gray-900 font-medium">
                                        <i class="ph ph-map-pin-line text-ebara-600"></i>
                                        {{ $material->location ?? '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.stock') }}</label>
                                    <div class="flex items-end gap-2 mt-1">
                                        <span class="text-3xl font-bold text-gray-900">{{ $material->quantity }}</span>
                                        <span class="text-gray-500 mb-1 font-medium">{{ $material->satuan ? $material->satuan->nama : $material->unit }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Min. stok: {{ $material->min_threshold }} {{ $material->satuan ? $material->satuan->nama : $material->unit }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.asset_materials.unit_price') }}</label>
                                    <p class="text-gray-900 mt-1 font-bold text-lg">Rp {{ number_format($material->unit_price ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dates Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2 mb-6">
                        <i class="ph ph-clock text-ebara-600"></i>
                        Riwayat & Tanggal Penting
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                <i class="ph ph-calendar-plus text-2xl text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('modules.asset_materials.entry_date') }}</p>
                                <p class="text-gray-900 font-semibold">{{ $material->entry_date ? $material->entry_date->format('d M Y') : '-' }}</p>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                <i class="ph ph-calendar-x text-2xl text-red-600"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('modules.asset_materials.expiry_date') }}</p>
                                <p class="text-gray-900 font-semibold">{{ $material->expiry_date ? $material->expiry_date->format('d M Y') : '-' }}</p>
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
                        {{ $material->description ?: 'Tidak ada deskripsi tambahan.' }}
                    </div>
                </div>
            </div>

            <!-- Right Panel: QR & Metadata -->
            <div class="space-y-6">
                <!-- QR Code Card -->
                <div class="bg-gradient-to-br from-ebara-600 to-ebara-800 rounded-2xl shadow-lg p-6 text-white text-center">
                    <h3 class="font-bold text-lg mb-4">Scan Aset</h3>
                    <div class="bg-white p-4 rounded-xl inline-block shadow-inner mb-4">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->generate($material->material_code) !!}
                    </div>
                    <p class="font-mono text-sm opacity-90 tracking-widest">{{ $material->material_code }}</p>
                    <div class="mt-6 pt-6 border-t border-white/10">
                        <p class="text-xs opacity-75">Scan QR code ini untuk melihat detail aset melalui aplikasi mobile.</p>
                    </div>
                </div>

                <!-- Meta Info Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                            <span class="text-sm text-gray-500">{{ __('modules.common.created_at') }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $material->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                            <span class="text-sm text-gray-500">{{ __('modules.common.updated_at') }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $material->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
