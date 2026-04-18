@props([
    'stats' => [
        'total_logs' => 0,
        'total_hours' => 0,
        'average_hours_per_day' => 0,
        'completed' => 0,
        'in_progress' => 0,
        'pending' => 0,
        'overdue' => 0,
    ],
    'trend' => null, // 'up', 'down', or null
])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
    <!-- Total Work Logs -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-center justify-between transition-transform group-hover:-translate-y-1 duration-300">
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">{{ __('work_logs.stats.total_logs') }}</p>
                <div class="flex items-baseline mt-1 gap-1">
                    <p class="text-3xl font-bold text-slate-900 work-log-stats-total-logs">{{ number_format($stats['total_logs'], 0) }}</p>
                </div>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                <i class="ph ph-clipboard-text text-2xl"></i>
            </div>
        </div>
        
        @if($trend)
        <div class="mt-4 flex items-center gap-2">
            @if($trend === 'up')
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                <i class="ph ph-trend-up mr-1"></i>
                {{ __('work_logs.stats.trend_up') }}
            </span>
            @elseif($trend === 'down')
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-800">
                <i class="ph ph-trend-down mr-1"></i>
                {{ __('work_logs.stats.trend_down') }}
            </span>
            @endif
        </div>
        @endif
    </div>
    
    <!-- Total Work Hours -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-center justify-between transition-transform group-hover:-translate-y-1 duration-300">
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">{{ __('work_logs.stats.total_hours') }}</p>
                <div class="flex items-baseline mt-1 gap-1">
                    <p class="text-3xl font-bold text-slate-900 work-log-stats-total-hours">{{ number_format($stats['total_hours'], 1) }}</p>
                    <span class="text-sm font-medium text-slate-400">{{ __('work_logs.units.hours') }}</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                <i class="ph ph-clock text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Average Hours Per Day -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-center justify-between transition-transform group-hover:-translate-y-1 duration-300">
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">{{ __('work_logs.stats.average_hours_per_day') }}</p>
                <div class="flex items-baseline mt-1 gap-1">
                    <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['average_hours_per_day'], 1) }}</p>
                    <span class="text-sm font-medium text-slate-400">{{ __('work_logs.units.hours') }}</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                <i class="ph ph-chart-line text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Completed -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-center justify-between transition-transform group-hover:-translate-y-1 duration-300">
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">{{ __('work_logs.stats.completed') }}</p>
                <div class="flex items-baseline mt-1 gap-1">
                    <p class="text-3xl font-bold text-slate-900 work-log-stats-completed">{{ number_format($stats['completed'], 0) }}</p>
                </div>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                <i class="ph ph-check-circle text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- In Progress -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-center justify-between transition-transform group-hover:-translate-y-1 duration-300">
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">{{ __('work_logs.stats.in_progress') }}</p>
                <div class="flex items-baseline mt-1 gap-1">
                    <p class="text-3xl font-bold text-slate-900 work-log-stats-in-progress">{{ number_format($stats['in_progress'], 0) }}</p>
                </div>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                <i class="ph ph-spinner text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Pending -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-center justify-between transition-transform group-hover:-translate-y-1 duration-300">
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">{{ __('work_logs.stats.pending') }}</p>
                <div class="flex items-baseline mt-1 gap-1">
                    <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['pending'], 0) }}</p>
                </div>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                <i class="ph ph-hourglass text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- On Hold -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-center justify-between transition-transform group-hover:-translate-y-1 duration-300">
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">{{ __('work_logs.status.on_hold') }}</p>
                <div class="flex items-baseline mt-1 gap-1">
                    <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['on_hold'] ?? 0, 0) }}</p>
                </div>
            </div>
            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 group-hover:bg-slate-600 group-hover:text-white transition-colors duration-300">
                <i class="ph ph-pause-circle text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Overdue/Cancelled -->
    <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-center justify-between transition-transform group-hover:-translate-y-1 duration-300">
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">{{ __('work_logs.stats.overdue') }}</p>
                <div class="flex items-baseline mt-1 gap-1">
                    <p class="text-3xl font-bold text-slate-900">{{ number_format($stats['overdue'] ?? 0, 0) }}</p>
                </div>
            </div>
            <div class="w-12 h-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300">
                <i class="ph ph-warning-circle text-2xl"></i>
            </div>
        </div>
    </div>
</div>
