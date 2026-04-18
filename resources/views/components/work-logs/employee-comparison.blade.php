@props([
    'employees' => [],
])

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between">
        <h3 class="text-xl font-black text-slate-800 flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-sm">
                 <i class="ph ph-users-three text-xl"></i>
            </div>
            {{ __('work_logs.page.employee_comparison') }}
        </h3>
        <div class="px-4 py-1.5 bg-indigo-50 rounded-full">
            <span class="text-xs font-black text-indigo-600 uppercase tracking-widest">{{ count($employees) }} {{ __('work_logs.fields.user') }}</span>
        </div>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 text-[10px] sm:text-xs font-black uppercase tracking-widest">
                    <th class="px-6 py-4 text-left border-b border-slate-100">{{ __('work_logs.table.user') }}</th>
                    <th class="px-6 py-4 text-left border-b border-slate-100">{{ __('work_logs.stats.total_hours') }}</th>
                    <th class="px-6 py-4 text-left border-b border-slate-100">{{ __('work_logs.stats.completed') }}</th>
                    <th class="px-6 py-4 text-left border-b border-slate-100">{{ __('work_logs.stats.completion_rate') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($employees as $employee)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm transition-transform group-hover:rotate-6">
                                <span class="text-sm font-black text-white uppercase text-center">
                                    {{ substr($employee['user_name'], 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <span class="text-sm font-black text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $employee['user_name'] }}</span>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ __('work_logs.stats.team_member') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-sm font-black">
                             {{ number_format($employee['total_hours'], 1) }}
                             <span class="mx-1 opacity-50">-</span>
                             <span class="text-[10px] uppercase opacity-70">{{ __('work_logs.units.hours') }}</span>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-1.5">
                             <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                             <span class="text-sm font-black text-slate-700">{{ $employee['completed_count'] }}</span>
                             <span class="text-xs font-bold text-slate-400">{{ __('work_logs.stats.fixed') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 min-w-[200px]">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('work_logs.stats.efficiency') }}</span>
                                <span class="text-xs font-black text-indigo-600">{{ number_format($employee['completion_rate'], 1) }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner flex">
                                <div class="h-full bg-indigo-600 rounded-full transition-all duration-1000 shadow-[0_0_8px_rgba(79,70,229,0.4)]"
                                     style="width: {{ $employee['completion_rate'] }}%"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center text-slate-400 italic">
                         <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="ph ph-users-four text-5xl text-slate-200"></i>
                         </div>
                        <p class="text-lg font-bold text-slate-400">{{ __('work_logs.messages.no_employees') }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
