@props([
    'month' => null,
    'year' => null,
    'userId' => null,
    'summary' => [
        'month' => null,
        'year' => null,
        'month_name' => '',
        'total_hours' => 0,
        'total_work_logs' => 0,
        'completed_work_logs' => 0,
        'average_hours_per_day' => 0,
        'completion_rate' => 0,
        'ineffective_days' => 0,
        'daily_breakdown' => [],
    ],
])

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <i class="ph ph-calendar-check text-indigo-600"></i>
                {{ __('work_logs.page.monthly_summary') }}
            </h3>
            <p class="text-sm font-bold text-slate-500 mt-1 uppercase tracking-widest">
                {{ $summary['month_name'] ?? '' }} {{ $summary['year'] ?? $year }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <select id="month_selector" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" @if($i == ($summary['month'] ?? $month)) selected @endif>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                @endfor
            </select>
            <select id="year_selector" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" @if($y == ($summary['year'] ?? $year)) selected @endif>{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6">
        <!-- Total Hours -->
        <div class="bg-indigo-50/50 rounded-2xl p-5 border border-indigo-100/50 shadow-sm transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-sm">
                    <i class="ph ph-clock text-xl"></i>
                </div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ __('work_logs.stats.total_hours') }}</p>
            </div>
            <p class="text-3xl font-black text-slate-900 monthly-summary-total-hours">
                {{ number_format($summary['total_hours'] ?? 0, 1) }}
                <span class="text-xs font-bold text-slate-400 tracking-normal">{{ __('work_logs.units.hours') }}</span>
            </p>
        </div>
        
        <!-- Total Days -->
        <div class="bg-emerald-50/50 rounded-2xl p-5 border border-emerald-100/50 shadow-sm transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-sm">
                    <i class="ph ph-calendar-blank text-xl"></i>
                </div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ __('work_logs.stats.total_work_logs') }}</p>
            </div>
            <p class="text-3xl font-black text-slate-900 monthly-summary-total-days">
                {{ $summary['total_work_logs'] ?? 0 }}
                <span class="text-xs font-bold text-slate-400 tracking-normal">{{ __('work_logs.units.days') }}</span>
            </p>
        </div>
        
        <!-- Average Hours/Day -->
        <div class="bg-blue-50/50 rounded-2xl p-5 border border-blue-100/50 shadow-sm transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-sm">
                    <i class="ph ph-chart-line text-xl"></i>
                </div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ __('work_logs.stats.average_hours_per_day') }}</p>
            </div>
            <p class="text-3xl font-black text-slate-900 monthly-summary-avg-hours">
                {{ number_format($summary['average_hours_per_day'] ?? 0, 1) }}
                <span class="text-xs font-bold text-slate-400 tracking-normal">{{ __('work_logs.units.hours') }}</span>
            </p>
        </div>
        
        <!-- Effective Days -->
        <div class="bg-amber-50/50 rounded-2xl p-5 border border-amber-100/50 shadow-sm transition-transform hover:-translate-y-1">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-amber-600 rounded-xl flex items-center justify-center text-white shadow-sm">
                    <i class="ph ph-check-fat text-xl"></i>
                </div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">{{ __('work_logs.stats.completed') }}</p>
            </div>
            <p class="text-3xl font-black text-slate-900 monthly-summary-effective-days">
                {{ $summary['completed_work_logs'] ?? 0 }}
                <span class="text-xs font-bold text-slate-400 tracking-normal">{{ __('work_logs.units.days') }}</span>
            </p>
        </div>
    </div>
    
    <!-- Daily Breakdown Table -->
    <div class="px-6 pb-6">
        <h4 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
             <i class="ph ph-list-numbers text-indigo-600"></i>
             {{ __('work_logs.stats.daily_breakdown') }}
        </h4>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-500 text-[10px] sm:text-xs font-black uppercase tracking-widest">
                        <th class="px-6 py-4 text-left border-b border-slate-100">{{ __('work_logs.table.date') }}</th>
                        <th class="px-6 py-4 text-left border-b border-slate-100">{{ __('work_logs.stats.total_hours') }}</th>
                        <th class="px-6 py-4 text-left border-b border-slate-100">{{ __('work_logs.stats.completed') }}</th>
                        <th class="px-6 py-4 text-left border-b border-slate-100">{{ __('work_logs.stats.in_progress') }}</th>
                        <th class="px-6 py-4 text-left border-b border-slate-100">{{ __('work_logs.stats.pending') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($summary['daily_breakdown'] ?? [] as $day)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-black text-slate-700">
                            {{ $day['date'] }}
                        </td>
                        <td class="px-6 py-4">
                             <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden max-w-[60px] hidden sm:block">
                                    <div class="h-full bg-indigo-600 rounded-full" style="width: {{ min(100, ($day['total_hours']/8)*100) }}%"></div>
                                </div>
                                <span class="text-sm font-black text-slate-900">{{ number_format($day['total_hours'], 1) }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ __('work_logs.units.hours_short') }}</span>
                             </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-black">
                                {{ $day['completed_count'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-black">
                                {{ $day['in_progress_count'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-xs font-black">
                                {{ $day['pending_count'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-slate-400 italic font-medium">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="ph ph-mask-sad text-4xl text-slate-200"></i>
                            </div>
                            <p class="text-lg">{{ __('work_logs.messages.no_daily_breakdown') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="px-6 py-5 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <span class="text-xs font-black text-slate-400 uppercase tracking-widest block">{{ __('work_logs.stats.ineffective_days') }}</span>
            <span class="text-xl font-black text-rose-600">{{ $summary['ineffective_days'] ?? 0 }} <span class="text-sm font-bold uppercase tracking-widest">{{ __('work_logs.units.days') }}</span></span>
        </div>
        <a href="{{ route('work-logs.export-excel') }}?month={{ $summary['month'] ?? $month }}&year={{ $summary['year'] ?? $year }}"
           class="inline-flex items-center px-6 py-3 bg-white border-2 border-emerald-500 text-emerald-600 hover:bg-emerald-50 font-black rounded-2xl transition-all shadow-sm active:scale-95">
            <i class="ph ph-download-simple mr-2 text-xl"></i>
            {{ __('work_logs.buttons.export') }}
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthSelector = document.getElementById('month_selector');
    const yearSelector = document.getElementById('year_selector');
    
    if (monthSelector && yearSelector) {
        function loadMonthlySummary() {
            const month = monthSelector.value;
            const year = yearSelector.value;
            
            const params = new URLSearchParams();
            params.append('month', month);
            params.append('year', year);
            
            fetch(`{{ route('api.work-logs.monthly-summary') }}?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateMonthlySummary(data.data);
                    }
                });
        }
        
        function updateMonthlySummary(summary) {
            // Update summary cards
            const totalHours = document.querySelector('.monthly-summary-total-hours');
            if (totalHours) {
                totalHours.textContent = (summary.total_hours || 0).toFixed(2);
            }
            
            const totalDays = document.querySelector('.monthly-summary-total-days');
            if (totalDays) {
                totalDays.textContent = summary.total_work_logs || 0;
            }
            
            const avgHours = document.querySelector('.monthly-summary-avg-hours');
            if (avgHours) {
                avgHours.textContent = (summary.average_hours_per_day || 0).toFixed(2);
            }
            
            const effectiveDays = document.querySelector('.monthly-summary-effective-days');
            if (effectiveDays) {
                effectiveDays.textContent = summary.completed_work_logs || 0;
            }
        }
        
        monthSelector.addEventListener('change', loadMonthlySummary);
        yearSelector.addEventListener('change', loadMonthlySummary);
    }
});
</script>
@endpush
