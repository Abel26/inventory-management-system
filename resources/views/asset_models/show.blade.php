<x-app-layout>
    <x-slot name="title">{{ __('modules.asset_models.title') }}: {{ $model->name }}</x-slot>

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('assets.models.index') }}" class="w-10 h-10 bg-white rounded-xl shadow-sm border border-gray-200 flex items-center justify-center text-gray-400 hover:text-ebara-600 hover:border-ebara-200 transition-all">
                    <i class="ph ph-arrow-left text-xl"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                            {{ __('modules.asset_models.title') }}
                        </span>
                        <span class="text-gray-300">•</span>
                        <span class="text-sm font-mono text-gray-500">{{ $model->model_code }}</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $model->name }}</h1>
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
                            Informasi Model
                        </h2>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                            @if($model->condition === 'Good') bg-green-100 text-green-800 border-green-200
                            @elseif($model->condition === 'Repair') bg-yellow-100 text-yellow-800 border-yellow-200
                            @elseif($model->condition === 'Damaged') bg-red-100 text-red-800 border-red-200
                            @else bg-gray-100 text-gray-800 border-gray-200
                            @endif border">
                            {{ $model->condition_label }}
                        </span>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.asset_models.material') }}</label>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($model->material)
                                            <a href="{{ route('assets.materials.show', $model->material->id) }}" 
                                               target="_blank"
                                               class="text-ebara-600 hover:underline font-medium">
                                                {{ $model->material->name }}
                                            </a>
                                            <span class="text-xs text-gray-400 font-mono">({{ $model->material->material_code }})</span>
                                        @else
                                            <p class="text-gray-900 font-medium">-</p>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.type') }}</label>
                                    <p class="text-gray-900 mt-1 font-medium">{{ $model->type ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.supplier') }}</label>
                                    <p class="text-gray-900 mt-1 font-medium">{{ $model->supplier ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.common.location') }}</label>
                                    <div class="flex items-center gap-2 mt-1 text-gray-900 font-medium">
                                        <i class="ph ph-map-pin-line text-ebara-600"></i>
                                        {{ $model->location ?? '-' }}
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.asset_models.manufacture_date') }}</label>
                                    <p class="text-gray-900 mt-1 font-medium">{{ $model->manufacture_date ? $model->manufacture_date->format('d M Y') : '-' }}</p>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('modules.asset_materials.unit_price') }}</label>
                                    <p class="text-gray-900 mt-1 font-bold">Rp {{ number_format($model->unit_price ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features/Stats Card -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Modifications History (Placeholder or Summary) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2 mb-4">
                            <i class="ph ph-wrench text-ebara-600"></i>
                            Modifikasi Model
                        </h3>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                            <span class="text-sm text-gray-600">Pending Modifikasi</span>
                            <span class="font-bold text-ebara-600 text-lg">{{ $model->pending_modifications_count }}</span>
                        </div>
                        @if($model->hasCriticalModifications())
                            <div class="mt-3 p-3 bg-red-50 text-red-700 text-xs rounded-lg flex items-center gap-2 border border-red-100">
                                <i class="ph ph-warning-circle text-lg"></i>
                                <span>Terdapat modifikasi kritis yang memerlukan perhatian segera.</span>
                            </div>
                        @endif
                    </div>

                    <!-- Description Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2 mb-4">
                            <i class="ph ph-article text-ebara-600"></i>
                            {{ __('modules.common.description') }}
                        </h3>
                        <div class="text-gray-600 leading-relaxed italic text-sm">
                            {{ $model->description ?: 'Tidak ada deskripsi tambahan.' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: QR & Metadata -->
            <div class="space-y-6">
                <!-- QR Code Card -->
                <div class="bg-gradient-to-br from-ebara-600 to-ebara-800 rounded-2xl shadow-lg p-6 text-white text-center">
                    <h3 class="font-bold text-lg mb-4">QR Code Model</h3>
                    <div class="bg-white p-4 rounded-xl inline-block shadow-inner mb-4">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->generate($model->model_code) !!}
                    </div>
                    <p class="font-mono text-sm opacity-90 tracking-widest">{{ $model->model_code }}</p>
                </div>

                <!-- Meta Info Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                            <span class="text-sm text-gray-500">{{ __('modules.common.created_at') }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $model->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b border-gray-50">
                            <span class="text-sm text-gray-500">{{ __('modules.common.updated_at') }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $model->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
