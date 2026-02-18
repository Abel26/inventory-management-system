<x-app-layout>
    <x-slot name="title">{{ __('modules.reports.create_title') }}</x-slot>

    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('reports.index') }}" class="text-gray-600 hover:text-gray-900 transition">
                <i class="ph ph-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('modules.reports.create_title') }}</h1>
                <p class="text-gray-600 mt-1">{{ __('modules.reports.create_subtitle') }}</p>
            </div>
        </div>

        @if(session('asset'))
            <!-- Asset Card from Session -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                    <!-- Asset Photo -->
                    <div class="w-20 h-20 sm:w-32 sm:h-32 bg-white rounded-lg border border-gray-200 flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-package text-3xl sm:text-5xl text-blue-600"></i>
                    </div>

                    <!-- Asset Details -->
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            {{ session('asset')['name'] }}
                            <span class="ml-2 px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-full">
                                {{ session('asset_type') ? ucfirst(session('asset_type')) : 'Asset' }}
                            </span>
                        </h3>

                        <x-ui.form-grid columns="2">
                            <div>
                                <p class="text-gray-500">{{ __('modules.common.code') }}</p>
                                <p class="font-medium text-gray-900">{{ session('asset')['code'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">{{ __('modules.common.type') }}</p>
                                <p class="font-medium text-gray-900">{{ session('asset')['type'] }}</p>
                            </div>
                        </x-ui.form-grid>
                        <x-ui.form-grid columns="2">
                            <div>
                                <p class="text-gray-500">{{ __('modules.common.condition') }}</p>
                                <p class="font-medium text-gray-900">{{ session('asset')['condition'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">{{ __('modules.common.location') }}</p>
                                <p class="font-medium text-gray-900">{{ session('asset')['location'] }}</p>
                            </div>
                        </x-ui.form-grid>
                    </div>
                </div>
            </div>
        </div>
        @elseif($assetInfo)
            <!-- Asset Card from URL Parameter -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                    <!-- Asset Photo -->
                    <div class="w-20 h-20 sm:w-32 sm:h-32 bg-white rounded-lg border border-gray-200 flex items-center justify-center flex-shrink-0">
                        <i class="ph ph-package text-3xl sm:text-5xl text-blue-600"></i>
                    </div>

                    <!-- Asset Details -->
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            {{ $assetInfo['name'] }}
                            <span class="ml-2 px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-full">
                                {{ $assetInfo['type'] }}
                            </span>
                        </h3>

                        <x-ui.form-grid columns="2">
                            <div>
                                <p class="text-gray-500">{{ __('modules.common.code') }}</p>
                                <p class="font-medium text-gray-900">{{ $assetInfo['code'] }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">{{ __('modules.common.type') }}</p>
                                <p class="font-medium text-gray-900">{{ $assetInfo['type'] }}</p>
                            </div>
                        </x-ui.form-grid>
                        <x-ui.form-grid columns="2">
                            <div>
                                <p class="text-gray-500">{{ __('modules.common.condition') }}</p>
                                <p class="font-medium text-gray-900">{{ $assetInfo['condition'] ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">{{ __('modules.common.location') }}</p>
                                <p class="font-medium text-gray-900">{{ $assetInfo['location'] }}</p>
                            </div>
                        </x-ui.form-grid>
                    </div>
                </div>
            </div>
        @else
            <!-- No Asset Selected Warning -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <i class="ph ph-warning-circle text-3xl text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-yellow-900">{{ __('modules.reports.no_asset_selected') }}</h3>
                        <p class="text-yellow-700 mt-1">{{ __('modules.reports.scan_qr_first') }}</p>
                        <a href="{{ route('reports.scan') }}" class="inline-flex items-center gap-2 mt-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            <i class="ph ph-qr-code text-lg"></i>
                            <span>{{ __('modules.reports.scan_qr_code') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Report Form -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-100 p-6">
            <form id="reportForm" action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <input type="hidden" name="reportable_id" value="{{ session('asset')['id'] ?? $assetInfo['id'] ?? '' }}">
                <input type="hidden" name="reportable_type" value="{{ session('asset')['model_class'] ?? $assetInfo['model_class'] ?? '' }}">

                <!-- Issue Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('modules.reports.issue_type') }}</label>
                    <x-ui.form-grid columns="2">
                        <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 hover:bg-gray-100 rounded-lg p-3 transition">
                            <input type="radio" name="issue_type" value="Damage" class="w-4 h-4 text-red-600" required>
                            <span class="text-sm text-gray-700">{{ __('modules.reports.issue_damage') }}</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 hover:bg-gray-100 rounded-lg p-3 transition">
                            <input type="radio" name="issue_type" value="Maintenance" class="w-4 h-4 text-yellow-600">
                            <span class="text-sm text-gray-700">{{ __('modules.reports.issue_maintenance') }}</span>
                        </label>
                    </x-ui.form-grid>
                    <x-ui.form-grid columns="2">
                        <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 hover:bg-gray-100 rounded-lg p-3 transition">
                            <input type="radio" name="issue_type" value="Lost" class="w-4 h-4 text-orange-600">
                            <span class="text-sm text-gray-700">{{ __('modules.reports.issue_lost') }}</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 hover:bg-gray-100 rounded-lg p-3 transition">
                            <input type="radio" name="issue_type" value="Stock Discrepancy" class="w-4 h-4 text-purple-600">
                            <span class="text-sm text-gray-700">{{ __('modules.reports.issue_stock_discrepancy') }}</span>
                        </label>
                    </x-ui.form-grid>
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('modules.reports.priority') }}</label>
                    <x-ui.form-grid columns="2">
                        <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 hover:bg-gray-100 rounded-lg p-3 transition">
                            <input type="radio" name="priority" value="Low" class="w-4 h-4 text-gray-600">
                            <span class="text-sm text-gray-700">{{ __('modules.reports.priority_low') }}</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 hover:bg-gray-100 rounded-lg p-3 transition">
                            <input type="radio" name="priority" value="Medium" class="w-4 h-4 text-yellow-600">
                            <span class="text-sm text-gray-700">{{ __('modules.reports.priority_medium') }}</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 hover:bg-gray-100 rounded-lg p-3 transition">
                            <input type="radio" name="priority" value="High" class="w-4 h-4 text-orange-600">
                            <span class="text-sm text-gray-700">{{ __('modules.reports.priority_high') }}</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer bg-gray-50 hover:bg-gray-100 rounded-lg p-3 transition">
                            <input type="radio" name="priority" value="Critical" class="w-4 h-4 text-red-600">
                            <span class="text-sm text-gray-700">{{ __('modules.reports.priority_critical') }}</span>
                        </label>
                    </x-ui.form-grid>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">{{ __('modules.common.description') }}</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        required
                        class="w-full rounded-lg border-gray-300 shadow-sm border p-3 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition"
                        placeholder="{{ __('modules.reports.description_placeholder') }}"></textarea>
                </div>

                <!-- Photo Upload -->
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">{{ __('modules.reports.photo_evidence') }}</label>
                    <div class="flex items-center gap-4">
                        <label class="flex-1 cursor-pointer">
                            <input type="file" name="photo" accept="image/*" class="hidden" id="photoInput">
                            <div class="w-24 h-24 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-200 transition cursor-pointer" onclick="document.getElementById('photoInput').click()">
                                <div class="text-center">
                                    <i class="ph ph-camera text-2xl text-gray-400"></i>
                                    <p class="text-xs text-gray-500 mt-1">{{ __('modules.reports.upload') }}</p>
                                </div>
                            </div>
                        </label>
                        <div id="photoPreview" class="w-24 h-24 bg-gray-100 rounded-lg border border-gray-200 hidden overflow-hidden">
                            <img id="previewImage" src="" alt="Preview" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ __('modules.reports.photo_max_size') }}</p>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('reports.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                        {{ __('modules.common.cancel') }}
                    </a>
                    <button type="button" id="submitReportBtn" class="bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-6 rounded-lg inline-flex items-center gap-2 transition">
                        <i class="ph ph-paper-plane-right text-lg"></i>
                        <span>{{ __('modules.reports.submit_report') }}</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    @push('scripts')
    <script>
        // Photo preview functionality
        document.getElementById('photoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('photoPreview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // SweetAlert2 confirmation before submit
        document.getElementById('submitReportBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '{{ __('modules.swal.submit_report') }}',
                text: '{{ __('modules.swal.submit_report_text') }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a', // Green color for confirm
                cancelButtonColor: '#6b7280', // Gray color for cancel
                confirmButtonText: '{{ __('modules.swal.yes_submit') }}',
                cancelButtonText: '{{ __('modules.swal.cancel') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reportForm').submit();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
