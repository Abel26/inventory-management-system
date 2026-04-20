<x-app-layout>
    <x-slot name="title">{{ __('modules.reports.detail_title') }} #{{ $report->report_code }}</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                    <h1 class="text-2xl font-bold text-gray-900">
                        #{{ $report->report_code }}
                    </h1>
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        @if($report->status->value === 'Pending') bg-yellow-100 text-yellow-800
                        @elseif($report->status->value === 'In Progress') bg-blue-100 text-blue-800
                        @elseif($report->status->value === 'Resolved') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ __('modules.reports.status_' . \Illuminate\Support\Str::slug($report->status->value, '_')) }}
                    </span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        @if($report->priority->value === 'Low') bg-gray-100 text-gray-800
                        @elseif($report->priority->value === 'Medium') bg-blue-100 text-blue-800
                        @elseif($report->priority->value === 'High') bg-orange-100 text-orange-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ __('modules.reports.priority_' . \Illuminate\Support\Str::slug($report->priority->value, '_')) }}
                    </span>
                </div>
                <p class="text-gray-600 mt-1">
                    {{ __('modules.reports.created_at') }} {{ $report->created_at->format('d M Y H:i') }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('reports.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg inline-flex items-center gap-2 transition">
                    <i class="ph ph-arrow-left text-lg"></i>
                    <span>{{ __('modules.common.back') }}</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Panel: Asset Details & Issue Description -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Asset Details Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('modules.reports.asset_details') }}</h2>
                        @if($report->reportable)
                            @php
                                $assetType = class_basename($report->reportable);
                                $routeName = match($assetType) {
                                    'AssetMaterial' => 'assets.materials.show',
                                    'AssetTool' => 'assets.tools.show',
                                    'AssetModel' => 'assets.models.show',
                                    default => null,
                                };
                            @endphp
                            @if($routeName)
                                <a href="{{ route($routeName, ['id' => $report->reportable->id]) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="text-ebara-600 hover:text-ebara-800 text-sm font-medium">
                                    {{ __('modules.reports.view_asset') }} <i class="ph ph-arrow-right"></i>
                                </a>
                            @endif
                        @endif
                    </div>

                    @if($report->reportable)
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-shrink-0">
                                @if(isset($report->reportable->photo_path) && $report->reportable->photo_path)
                                    <img src="{{ asset('storage/' . $report->reportable->photo_path) }}"
                                         alt="{{ $report->reportable->name }}"
                                         class="w-20 h-20 sm:w-32 sm:h-32 object-cover rounded-lg">
                                @else
                                    <div class="w-20 h-20 sm:w-32 sm:h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="ph ph-cube text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $report->reportable->name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="ph ph-map-pin"></i> {{ $report->reportable->location ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="ph ph-tag"></i> {{ class_basename($report->reportable) }}
                                </p>
                                @if(isset($report->reportable->code) && $report->reportable->code)
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="ph ph-qr-code"></i> {{ $report->reportable->code }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 italic">{{ __('modules.reports.asset_not_found') }}</p>
                    @endif
                </div>

                <!-- Issue Description Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('modules.reports.issue_description') }}</h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-600">{{ __('modules.reports.issue_type') }}</label>
                                <p class="mt-1">{{ __('modules.reports.issue_' . \Illuminate\Support\Str::slug($report->issue_type->value, '_')) }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">{{ __('modules.reports.priority') }}</label>
                                <p class="mt-1">{{ __('modules.reports.priority_' . \Illuminate\Support\Str::slug($report->priority->value, '_')) }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-600">{{ __('modules.common.description') }}</label>
                            <p class="mt-1 text-gray-900 bg-gray-50 p-3 rounded-lg">
                                {{ $report->description }}
                            </p>
                        </div>

                        @if($report->photo_path)
                            <div>
                                <label class="text-sm font-medium text-gray-600">{{ __('modules.reports.photo_evidence') }}</label>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $report->photo_path) }}"
                                         alt="Foto Bukti"
                                         class="w-full max-w-md rounded-lg border border-gray-200">
                                </div>
                            </div>
                        @endif

                        <div class="border-t pt-4 mt-4">
                            <label class="text-sm font-medium text-gray-600">{{ __('modules.reports.reported_by') }}</label>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="h-6 w-6 rounded-full bg-ebara-100 flex items-center justify-center">
                                    <span class="text-xs font-medium text-ebara-600">
                                        {{ strtoupper(substr($report->user->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <span>{{ $report->user->name ?? 'Unknown' }}</span>
                                <span class="text-gray-400 text-sm">({{ $report->user->email ?? '-' }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Resolution Form -->
            <div class="space-y-6">
                <!-- Resolution Form Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('modules.reports.update_status') }}</h2>

                    <form method="POST" action="{{ route('reports.update-status', ['id' => $report->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">{{ __('modules.common.status') }}</label>
                                <select id="status" name="status"
                                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-ebara-500 focus:border-ebara-500">
                                    <option value="Pending" {{ $report->status->value === 'Pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="In Progress" {{ $report->status->value === 'In Progress' ? 'selected' : '' }}>
                                        In Progress
                                    </option>
                                    <option value="Resolved" {{ $report->status->value === 'Resolved' ? 'selected' : '' }}>
                                        Resolved
                                    </option>
                                    <option value="Rejected" {{ $report->status->value === 'Rejected' ? 'selected' : '' }}>
                                        Rejected
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="admin_note" class="block text-sm font-medium text-gray-700">{{ __('modules.reports.admin_note') }}</label>
                                <textarea id="admin_note" name="admin_note"
                                          rows="4"
                                          class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-ebara-500 focus:border-ebara-500"
                                          placeholder="{{ __('modules.reports.admin_note_placeholder') }}">{{ $report->admin_note ?? '' }}</textarea>
                            </div>

                            <button type="submit" class="w-full bg-ebara-600 hover:bg-ebara-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center justify-center gap-2 transition">
                                <i class="ph ph-check-circle"></i>
                                {{ __('modules.reports.update_status') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Resolution History Card -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('modules.reports.status_history') }}</h2>

                    <div class="space-y-4">
                        <!-- Created -->
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="ph ph-file-plus text-gray-600"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ __('modules.reports.report_created') }}</p>
                                <p class="text-sm text-gray-600">{{ $report->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        @if($report->status->value !== 'Pending')
                            <!-- In Progress -->
                            @if($report->status->value !== 'Pending')
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="ph ph-spinner text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">In Progress</p>
                                        <p class="text-sm text-gray-600">{{ __('modules.reports.status_changed_to') }} In Progress</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Resolved/Rejected -->
                            @if($report->status->value === 'Resolved' || $report->status->value === 'Rejected')
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 rounded-full
                                            @if($report->status->value === 'Resolved') bg-green-100
                                            @else bg-red-100
                                            @endif flex items-center justify-center">
                                            <i class="ph
                                                @if($report->status->value === 'Resolved') ph-check-circle text-green-600
                                                @else ph-x-circle text-red-600
                                                @endif"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $report->status->value }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ __('modules.reports.resolved_by') }} {{ $report->resolver->name ?? 'Unknown' }}
                                        </p>
                                        @if($report->resolved_at)
                                            <p class="text-sm text-gray-600">{{ $report->resolved_at->format('d M Y H:i') }}</p>
                                        @endif
                                        @if($report->admin_note)
                                            <p class="text-sm text-gray-600 mt-1">
                                                <strong>{{ __('modules.reports.note_label') }}</strong> {{ $report->admin_note }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
