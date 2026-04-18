<x-app-layout>
    <x-slot name="title">{{ __('work_logs.page.my_work_title') }}</x-slot>

<div class="space-y-8" x-data="{ activeTab: 'overview', showTimerModal: false }" @keydown.escape.window="showTimerModal = false">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ __('work_logs.page.my_work_title') }}</h1>
            <p class="text-slate-500 mt-2 text-lg">{{ __('work_logs.page.my_work_description') }}</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('work-logs.create') }}"
               class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-sm shadow-indigo-200 transition-all active:scale-95">
                <i class="ph ph-plus-circle mr-2 text-xl"></i>
                {{ __('work_logs.actions.create') }}
            </a>

            <div class="h-8 w-px bg-slate-200 mx-2 hidden sm:block"></div>

            <a href="{{ route('work-logs.export-excel') }}?user_id={{ Auth::id() }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 font-semibold rounded-xl transition-all shadow-sm active:scale-95">
                <i class="ph ph-microsoft-excel-logo text-xl text-emerald-600"></i>
                <span>{{ __('work_logs.buttons.export_excel') }}</span>
            </a>
            <a href="{{ route('work-logs.export-pdf') }}?user_id={{ Auth::id() }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:border-rose-500 hover:bg-rose-50 text-slate-700 hover:text-rose-700 font-semibold rounded-xl transition-all shadow-sm active:scale-95">
                <i class="ph ph-file-pdf text-xl text-rose-600"></i>
                <span>{{ __('work_logs.buttons.export_pdf') }}</span>
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white p-1 rounded-2xl border border-slate-200 shadow-sm inline-flex mb-2">
        <nav class="flex gap-1" aria-label="Tabs">
            <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                    class="flex items-center px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200">
                <i class="ph ph-layout mr-2 text-lg"></i>Overview
            </button>
            <button @click="activeTab = 'history'"
                    :class="activeTab === 'history' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                    class="flex items-center px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200">
                <i class="ph ph-clock-counter-clockwise mr-2 text-lg"></i>History
            </button>
            <button @click="activeTab = 'monthly'"
                    :class="activeTab === 'monthly' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                    class="flex items-center px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200">
                <i class="ph ph-calendar-check mr-2 text-lg"></i>{{ __('work_logs.page.monthly_summary') }}
            </button>
        </nav>
    </div>

    <!-- Overview Tab (Employee Dashboard) -->
    <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">

        {{-- ROW 0: Welcome Banner --}}
        <div class="bg-gradient-to-br from-ebara-900 via-ebara-700 to-ebara-600 rounded-2xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute top-10 right-10 w-32 h-32 bg-white opacity-5 rounded-full blur-2xl"></div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative z-10">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-1">
                        {{ $greeting ?? __('work_logs.dashboard.welcome') }}, {{ Auth::user()->name }}{{ __('work_logs.dashboard.greeting_suffix') }}
                    </h1>
                    <p class="text-ebara-100 text-sm sm:text-base">
                        {{ now()->timezone('Asia/Jakarta')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('work-logs.create') }}"
                       class="flex items-center gap-2 px-4 py-2.5 bg-white text-ebara-600 rounded-xl hover:bg-ebara-50 transition-all font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm">
                        <i class="ph ph-plus-circle text-lg"></i>
                        <span>{{ __('work_logs.actions.create') }}</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ROW 1: Today's Quick Stats (4 cards) --}}
        <div class="grid grid-cols-12 gap-4 sm:gap-6">
            {{-- Today's Work Logs --}}
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 group">
                <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('work_logs.dashboard.logs_today') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $todayStats['today_total'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 sm:p-4 bg-blue-50 rounded-2xl group-hover:bg-blue-100 transition-colors">
                            <i class="ph ph-clipboard-text text-blue-600 text-xl sm:text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Today's Hours --}}
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 group">
                <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('work_logs.dashboard.hours_today') }}</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ number_format($todayStats['today_hours'] ?? 0, 1) }}
                                <span class="text-sm text-gray-400 font-medium">{{ __('work_logs.dashboard.hours_unit') }}</span>
                            </p>
                        </div>
                        <div class="p-3 sm:p-4 bg-emerald-50 rounded-2xl group-hover:bg-emerald-100 transition-colors">
                            <i class="ph ph-clock text-emerald-600 text-xl sm:text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- In Progress --}}
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 group">
                <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('work_logs.stats.in_progress') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $todayStats['today_in_progress'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 sm:p-4 bg-amber-50 rounded-2xl group-hover:bg-amber-100 transition-colors">
                            <i class="ph ph-spinner text-amber-600 text-xl sm:text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Completed Today --}}
            <div class="col-span-12 sm:col-span-6 xl:col-span-3 group">
                <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-md border border-gray-100 hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500 mb-1">{{ __('work_logs.stats.completed') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $todayStats['today_completed'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 sm:p-4 bg-green-50 rounded-2xl group-hover:bg-green-100 transition-colors">
                            <i class="ph ph-check-circle text-green-600 text-xl sm:text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 2: Timer Widget + Weekly Chart --}}
        <div class="grid grid-cols-12 gap-4 sm:gap-6">
            {{-- Timer Widget --}}
            <div class="col-span-12 lg:col-span-5">
                <div class="bg-indigo-600 rounded-2xl p-6 sm:p-8 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden group h-full">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 -mr-32 -mt-32 rounded-full transition-transform group-hover:scale-110"></div>
                    <div class="relative flex flex-col items-center text-center gap-6">
                        <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-inner">
                            <i class="ph ph-timer text-4xl animate-pulse"></i>
                        </div>
                        <div>
                            @php
                                $activeTimer = $activeWorkLogs->first();
                            @endphp
                            <h2 class="text-xl font-bold tracking-tight mb-1">
                                {{ $activeTimer ? __('work_logs.status.in_progress') : __('work_logs.actions.start_timer') }}
                            </h2>
                            <p class="text-indigo-100 font-medium text-sm" id="timer-status">
                                {{ $activeTimer ? $activeTimer->description : __('work_logs.page.my_work_description') }}
                            </p>
                        </div>

                        <div class="text-center">
                            <div class="text-4xl sm:text-5xl font-black tracking-tighter tabular-nums mb-1" id="stopwatch">
                                00:00:00
                            </div>
                            <span class="text-xs font-bold uppercase tracking-widest text-indigo-200">{{ __('work_logs.units.timeline') }}</span>
                        </div>

                        <div id="timer-actions">
                            @if($activeTimer)
                                <button onclick="stopTimer({{ $activeTimer->id }})"
                                        class="bg-rose-500 hover:bg-rose-600 text-white px-6 py-3 rounded-2xl font-bold text-sm uppercase tracking-widest shadow-xl shadow-rose-900/20 transition-all active:scale-95 flex items-center gap-2">
                                    <i class="ph ph-stop-circle text-xl"></i>
                                    {{ __('work_logs.actions.stop_timer') }}
                                </button>
                            @else
                                <button @click="showTimerModal = true"
                                        class="bg-white hover:bg-indigo-50 text-indigo-600 px-6 py-3 rounded-2xl font-bold text-sm uppercase tracking-widest shadow-xl shadow-indigo-900/20 transition-all active:scale-95 flex items-center gap-2">
                                    <i class="ph ph-play-circle text-xl"></i>
                                    {{ __('work_logs.actions.start_timer') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Weekly Progress Chart --}}
            <div class="col-span-12 lg:col-span-7" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden h-full">
                    <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-chart-bar text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm sm:text-base">{{ __('work_logs.dashboard.weekly_chart_title') }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5 hidden sm:block">{{ __('work_logs.dashboard.weekly_chart_subtitle') }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-ebara-50 text-ebara-700 text-xs font-semibold rounded-full whitespace-nowrap">
                            {{ number_format($weeklyBreakdown['total_hours'] ?? 0, 1) }} {{ __('work_logs.dashboard.hours_unit') }}
                        </span>
                    </div>
                    <div class="relative w-full overflow-hidden" style="min-height: 280px;">
                        <div x-show="!loaded" class="absolute inset-0 flex items-center justify-center bg-gray-50 animate-pulse">
                            <div class="w-full h-48 bg-gradient-to-r from-gray-200 via-gray-100 to-gray-200 rounded-lg m-4"></div>
                        </div>
                        <div x-show="loaded" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" id="weeklyChart" class="w-full" style="height: 280px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ROW 3: Active Work + Monthly Quick View --}}
        <div class="grid grid-cols-12 gap-4 sm:gap-6">
            {{-- Active Work Logs --}}
            <div class="col-span-12 lg:col-span-8">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-activity text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm sm:text-base">{{ __('work_logs.dashboard.active_work') }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $activeWorkLogs->count() }} {{ __('work_logs.dashboard.active_count') }}</p>
                            </div>
                        </div>
                        <button @click="activeTab = 'history'" class="text-sm font-medium text-ebara-600 hover:text-ebara-700 flex items-center gap-1 whitespace-nowrap">
                            <span class="hidden sm:inline">{{ __('work_logs.dashboard.view_all_work') }}</span>
                            <i class="ph ph-arrow-right"></i>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6">
                        @forelse($activeWorkLogs as $workLog)
                            <x-work-log-card :workLog="$workLog" />
                        @empty
                            <div class="text-center py-10">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="ph ph-ghost text-3xl text-slate-200"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-800 mb-1">{{ __('work_logs.dashboard.no_active_work') }}</h3>
                                <p class="text-sm text-slate-400">{{ __('work_logs.dashboard.no_active_work_desc') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Monthly Progress Quick View --}}
            <div class="col-span-12 lg:col-span-4">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-ebara-100 rounded-lg">
                                <i class="ph ph-calendar-check text-ebara-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm sm:text-base">{{ __('work_logs.dashboard.monthly_progress') }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $monthlySummary['month_name'] ?? '' }}</p>
                            </div>
                        </div>
                        <button @click="activeTab = 'monthly'" class="text-sm font-medium text-ebara-600 hover:text-ebara-700 flex items-center gap-1 whitespace-nowrap">
                            <i class="ph ph-arrow-right"></i>
                        </button>
                    </div>
                    <div class="p-4 sm:p-5 space-y-4">
                        {{-- Completion Rate --}}
                        <div class="text-center p-4 bg-gradient-to-br from-ebara-50 to-ebara-100 rounded-xl">
                            <p class="text-4xl font-bold text-ebara-700">{{ number_format($monthlySummary['completion_rate'] ?? 0, 0) }}%</p>
                            <p class="text-xs text-ebara-600 mt-1 font-medium">{{ __('work_logs.dashboard.monthly_completion_rate') }}</p>
                        </div>
                        {{-- Stats Grid --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="text-center p-3 bg-blue-50 rounded-xl">
                                <p class="text-xl font-bold text-blue-700">{{ number_format($monthlySummary['total_hours'] ?? 0, 1) }}</p>
                                <p class="text-[10px] text-blue-600 mt-0.5 font-medium">{{ __('work_logs.dashboard.hours_unit') }}</p>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-xl">
                                <p class="text-xl font-bold text-green-700">{{ $monthlySummary['completed_work_logs'] ?? 0 }}</p>
                                <p class="text-[10px] text-green-600 mt-0.5 font-medium">{{ __('work_logs.stats.completed') }}</p>
                            </div>
                        </div>
                        {{-- Recent Days Mini List --}}
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ __('work_logs.dashboard.recent_days') }}</p>
                            <div class="space-y-1.5">
                                @php $recentDays = array_slice($monthlySummary['daily_breakdown'] ?? [], 0, 5); @endphp
                                @foreach($recentDays as $day)
                                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="w-1.5 h-7 rounded-full {{ ($day['total_hours'] ?? 0) > 0 ? 'bg-ebara-500' : 'bg-gray-200' }}"></div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium text-gray-700 truncate">{{ $day['date'] }}</p>
                                        </div>
                                        <span class="text-xs font-bold {{ ($day['total_hours'] ?? 0) > 0 ? 'text-ebara-600' : 'text-gray-400' }}">
                                            {{ number_format($day['total_hours'] ?? 0, 1) }}h
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Start Timer Modal --}}
        <div x-show="showTimerModal" x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4">
                <!-- Overlay -->
                <div x-show="showTimerModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
                     @click="showTimerModal = false"></div>

                <!-- Modal Content -->
                <div x-show="showTimerModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg flex flex-col max-h-[90vh] overflow-hidden transform transition-all"
                     @click.away="showTimerModal = false">

                    <!-- Modal Header with Gradient -->
                    <div class="relative bg-gradient-to-r from-ebara-600 to-ebara-700 p-6 text-white flex-shrink-0">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <i class="ph ph-timer text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">{{ __('work_logs.actions.start_timer') }}</h3>
                                    <p class="text-ebara-100 text-sm">{{ __('work_logs.placeholders.description') }}</p>
                                </div>
                            </div>
                            <button type="button" @click="showTimerModal = false" class="text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200 p-2 rounded-lg">
                                <i class="ph ph-x text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 overflow-y-auto flex-1 space-y-5">
                        <!-- Description Field -->
                        <div class="space-y-2">
                            <label class="flex items-center text-sm font-semibold text-gray-700">
                                <i class="ph ph-text-align-left text-ebara-600 mr-2"></i>
                                {{ __('work_logs.fields.description') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <textarea id="timer-description" rows="3"
                                class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium placeholder-gray-400"
                                placeholder="{{ __('work_logs.placeholders.description') }}"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Priority Field -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="ph ph-flag text-ebara-600 mr-2"></i>
                                    {{ __('work_logs.fields.priority') }}
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <select id="timer-priority"
                                    class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                                    @foreach(\App\Enums\WorkPriority::cases() as $priority)
                                        <option value="{{ $priority->value }}">{{ $priority->getLabel() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Work Type Field -->
                            <div class="space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-700">
                                    <i class="ph ph-briefcase text-ebara-600 mr-2"></i>
                                    {{ __('work_logs.fields.work_type') }}
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <select id="timer-type"
                                    class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium">
                                    @foreach(\App\Enums\WorkType::cases() as $type)
                                        <option value="{{ $type->value }}">{{ $type->getLabel() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 p-6 border-t border-gray-200 flex-shrink-0">
                        <button type="button" @click="showTimerModal = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition-all duration-200 text-sm">
                            {{ __('work_logs.buttons.cancel') }}
                        </button>
                        <button type="button" onclick="startTimer()" class="px-5 py-2.5 bg-gradient-to-r from-ebara-600 to-ebara-700 hover:from-ebara-700 hover:to-ebara-800 text-white font-medium rounded-xl shadow-lg shadow-ebara-500/25 hover:shadow-ebara-500/40 transition-all duration-200 text-sm flex items-center justify-center gap-2">
                            <i class="ph ph-play-circle text-lg"></i>
                            <span>{{ __('work_logs.actions.start_timer') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- History Tab -->
    <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" class="space-y-6">
        <!-- Filter Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="filter_status" class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        {{ __('work_logs.fields.status') }}
                    </label>
                    <select id="filter_status" 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none appearance-none"
                            onchange="applyFilters()">
                        <option value="">{{ __('work_logs.select.all_status') }}</option>
                        @foreach(\App\Enums\WorkStatus::cases() as $status)
                            <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                {{ $status->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label for="filter_type" class="block text-xs font-black text-slate-400 uppercase tracking-widest">
                        {{ __('work_logs.fields.work_type') }}
                    </label>
                    <select id="filter_type" 
                             class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none appearance-none"
                            onchange="applyFilters()">
                        <option value="">{{ __('work_logs.select.all_work_types') }}</option>
                        @foreach(\App\Enums\WorkType::cases() as $type)
                            <option value="{{ $type->value }}" {{ request('work_type') == $type->value ? 'selected' : '' }}>
                                {{ $type->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Work Logs History List -->
        <div class="space-y-4">
            @forelse($workLogs as $workLog)
                <x-work-log-card :workLog="$workLog" />
            @empty
                <div class="bg-white rounded-3xl p-12 border-2 border-dashed border-slate-200 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="ph ph-file-search text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">{{ __('work_logs.messages.empty') }}</h3>
                    <p class="text-slate-500 max-w-xs mx-auto mb-6">{{ __('work_logs.messages.empty_description') }}</p>
                    <a href="{{ route('work-logs.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl transition-all shadow-sm active:scale-95">
                        <i class="ph ph-plus-circle mr-2 text-xl"></i>
                        {{ __('work_logs.actions.create_first') }}
                    </a>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($workLogs->hasPages())
            <div class="mt-8">
                {{ $workLogs->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Monthly Summary Tab -->
    <div x-show="activeTab === 'monthly'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        <x-work-logs.monthly-summary 
            :month="$month ?? date('m')" 
            :year="$year ?? date('Y')" 
            :userId="Auth::id()"
            :summary="$monthlySummary ?? []" />
    </div>
</div>

@push('scripts')
<script>
// Weekly Progress Chart
const weeklyChartData = @json($weeklyBreakdown);

function initializeWeeklyChart() {
    const chartElement = document.querySelector('#weeklyChart');
    if (!chartElement || typeof ApexCharts === 'undefined') return;

    const weeklyChartOptions = {
        series: [{
            name: '{{ __("work_logs.dashboard.hours_unit") }}',
            data: weeklyChartData.hours || [0,0,0,0,0,0,0],
        }],
        chart: {
            type: 'bar',
            height: 280,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif',
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
            },
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '55%',
            },
        },
        colors: ['#009B77'],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.3,
                opacityFrom: 0.9,
                opacityTo: 0.6,
                stops: [0, 100],
            },
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return val > 0 ? val.toFixed(1) : '';
            },
            style: {
                fontSize: '11px',
                colors: ['#006653'],
            },
        },
        xaxis: {
            categories: weeklyChartData.labels || [],
            labels: {
                style: { colors: '#6B7280', fontSize: '12px' },
            },
            axisBorder: { show: false },
            axisTicks: { show: false },
        },
        yaxis: {
            labels: {
                style: { colors: '#6B7280', fontSize: '12px' },
                formatter: function(val) { return val.toFixed(0) + 'h'; },
            },
        },
        grid: {
            borderColor: '#E5E7EB',
            strokeDashArray: 4,
            padding: { top: 0, right: 10, bottom: 0, left: 10 },
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) {
                    return val.toFixed(1) + ' {{ __("work_logs.dashboard.hours_unit") }}';
                },
            },
        },
    };

    const chart = new ApexCharts(chartElement, weeklyChartOptions);
    chart.render();

    window.addEventListener('resize', function() {
        if (chart && typeof chart.resize === 'function') {
            chart.resize();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initializeWeeklyChart();
});

// Timer & Stopwatch
let stopwatchInterval;
let startTime = @json($activeWorkLogs->first()?->start_time?->timestamp ?? null);

function updateStopwatch() {
    if (!startTime) return;
    
    const now = Math.floor(Date.now() / 1000);
    const diff = now - startTime;
    
    const hours = Math.floor(diff / 3600);
    const minutes = Math.floor((diff % 3600) / 60);
    const seconds = diff % 60;
    
    document.getElementById('stopwatch').textContent = 
        `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
}

if (startTime) {
    stopwatchInterval = setInterval(updateStopwatch, 1000);
    updateStopwatch();
}

function showStartTimerModal() {
    const modal = document.querySelector('[x-data]');
    if (modal && modal._x_dataStack) {
        modal._x_dataStack[0].showTimerModal = true;
    }
}

function hideStartTimerModal() {
    const modal = document.querySelector('[x-data]');
    if (modal && modal._x_dataStack) {
        modal._x_dataStack[0].showTimerModal = false;
    }
}

function startTimer() {
    const description = document.getElementById('timer-description').value;
    const priority = document.getElementById('timer-priority').value;
    const type = document.getElementById('timer-type').value;

    if (!description) {
        Swal.fire({
            icon: 'warning',
            title: 'Gagal',
            text: '{{ __('work_logs.validation.description_required') }}',
            confirmButtonColor: '#009B77',
        });
        return;
    }

    fetch('{{ route('api.work-logs.start-timer') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            description: description,
            priority: priority,
            work_type: type
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Timer dimulai!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: data.message,
                confirmButtonColor: '#dc2626',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ __('work_logs.messages.create_failed') }}',
            confirmButtonColor: '#dc2626',
        });
    });
}

function stopTimer(id) {
    Swal.fire({
        title: 'Konfirmasi',
        text: '{{ __('work_logs.messages.confirm_stop_timer') }}',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#009B77',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Ya, Hentikan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const url = '{{ route('api.work-logs.stop-timer', ':id') }}'.replace(':id', id);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Timer dihentikan!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message,
                        confirmButtonColor: '#dc2626',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ __('work_logs.messages.update_failed') }}',
                    confirmButtonColor: '#dc2626',
                });
            });
        }
    });
}

function applyFilters() {
    const status = document.getElementById('filter_status').value;
    const type = document.getElementById('filter_type').value;
    
    const params = new URLSearchParams();
    if (status) params.set('status', status);
    if (type) params.set('work_type', type);
    
    window.location.href = '{{ route('work-logs.my-work') }}?' + params.toString();
}
</script>
@endpush
</x-app-layout>
