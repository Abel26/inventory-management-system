<x-app-layout>
    <x-slot name="title">{{ __('work_logs.page.all_work_logs') }}</x-slot>

<div class="space-y-6" x-data="{ activeTab: 'overview' }">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ __('work_logs.page.all_work_logs') }}</h1>
            <p class="text-slate-500 mt-2 text-lg">{{ __('work_logs.page.all_work_logs_description') }}</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @can('Create work logs')
            <a href="{{ route('work-logs.create') }}"
               class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-sm shadow-indigo-200 transition-all active:scale-95">
                <i class="ph ph-plus-circle mr-2 text-xl"></i>
                {{ __('work_logs.buttons.create') }}
            </a>
            @endcan

            <div class="h-8 w-px bg-slate-200 mx-2 hidden sm:block"></div>

            <a href="{{ route('work-logs.export-excel') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 font-semibold rounded-xl transition-all shadow-sm active:scale-95">
                <i class="ph ph-microsoft-excel-logo text-xl text-emerald-600"></i>
                <span>{{ __('work_logs.buttons.export_excel') }}</span>
            </a>
            <a href="{{ route('work-logs.export-pdf') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:border-rose-500 hover:bg-rose-50 text-slate-700 hover:text-rose-700 font-semibold rounded-xl transition-all shadow-sm active:scale-95">
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
                <i class="ph ph-chart-pie mr-2 text-lg"></i>{{ __('work_logs.page.all_work_logs') }}
            </button>
            <button @click="activeTab = 'comparison'"
                    :class="activeTab === 'comparison' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                    class="flex items-center px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200">
                <i class="ph ph-users-three mr-2 text-lg"></i>{{ __('work_logs.page.employee_comparison') }}
            </button>
            <button @click="activeTab = 'monthly'"
                    :class="activeTab === 'monthly' ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                    class="flex items-center px-5 py-2.5 rounded-xl font-bold text-sm transition-all duration-200">
                <i class="ph ph-calendar-check mr-2 text-lg"></i>{{ __('work_logs.page.monthly_summary') }}
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div class="mt-4">
        <!-- Overview Tab -->
        <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Filters Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('work_logs.fields.date_range') }}</label>
                        <div class="relative flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-transparent transition-all group/date">
                            <div class="pl-4 pr-1 text-slate-400 group-focus-within/date:text-indigo-500 transition-colors">
                                <i class="ph ph-calendar-blank text-lg"></i>
                            </div>
                            <input type="date" id="start_date" class="w-full py-2.5 bg-transparent border-none text-slate-700 focus:ring-0 outline-none text-xs font-black cursor-pointer">
                            <div class="px-2 text-slate-300">
                                <i class="ph ph-arrow-right text-xs"></i>
                            </div>
                            <div class="pl-1 pr-1 text-slate-400 group-focus-within/date:text-indigo-500 transition-colors">
                                <i class="ph ph-calendar-blank text-lg"></i>
                            </div>
                            <input type="date" id="end_date" class="w-full py-2.5 bg-transparent border-none text-slate-700 focus:ring-0 outline-none text-xs font-black cursor-pointer pr-4">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('work_logs.fields.status') }}</label>
                        <select id="status_filter" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none appearance-none">
                            <option value="">{{ __('work_logs.select.all_statuses') }}</option>
                            @foreach(\App\Enums\WorkStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->getLabel() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest">{{ __('work_logs.fields.user') }}</label>
                        <select id="user_filter" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none appearance-none">
                            <option value="">{{ __('work_logs.select.all_users') }}</option>
                            @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                         <button onclick="window.location.reload()" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors" title="Reset Filters">
                            <i class="ph ph-arrows-counter-clockwise text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="mb-8">
                <x-work-logs.work-log-stats :stats="$stats ?? []" :trend="$trend ?? null" />
            </div>

            <!-- Work Logs Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800">{{ __('work_logs.actions.view') }}</h3>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-widest" id="table_count_display">--- Records</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('work_logs.table.work_code') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('work_logs.table.user') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('work_logs.table.description') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('work_logs.table.date') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('work_logs.table.duration') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('work_logs.table.status') }}</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('work_logs.table.priority') }}</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('work_logs.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="work_logs_table_body" class="divide-y divide-slate-100">
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">
                                    <i class="ph ph-spinner ph-spin text-4xl mb-2 block mx-auto text-indigo-200"></i>
                                    {{ __('work_logs.actions.saving') }}...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Footer -->
                <div id="pagination" class="px-6 py-4 bg-slate-50/30 border-t border-slate-100 flex items-center justify-between">
                    <!-- Pagination will be rendered here -->
                </div>
            </div>
        </div>

        <!-- Employee Comparison Tab -->
        <div x-show="activeTab === 'comparison'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <x-work-logs.employee-comparison :employees="$employees ?? []" />
        </div>

        <!-- Monthly Summary Tab -->
        <div x-show="activeTab === 'monthly'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
            <x-work-logs.monthly-summary 
                :month="$month ?? date('m')" 
                :year="$year ?? date('Y')" 
                :userId="$userId ?? null"
                :summary="$monthlySummary ?? []" />
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set default dates
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    
    const formatDate = (date) => {
        const d = new Date(date);
        let month = '' + (d.getMonth() + 1);
        let day = '' + d.getDate();
        const year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    };

    document.getElementById('start_date').value = formatDate(firstDayOfMonth);
    document.getElementById('end_date').value = formatDate(today);

    // Event listeners
    document.getElementById('start_date').addEventListener('change', loadWorkLogs);
    document.getElementById('end_date').addEventListener('change', loadWorkLogs);
    document.getElementById('status_filter').addEventListener('change', loadWorkLogs);
    document.getElementById('user_filter').addEventListener('change', loadWorkLogs);
    
    // Initial load
    loadWorkLogs();
});

// Load work logs data
function loadWorkLogs() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const status = document.getElementById('status_filter').value;
    const user = document.getElementById('user_filter').value;
    
    const params = new URLSearchParams();
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    if (status) params.append('status', status);
    if (user) params.append('user_id', user);
    
    fetch(`{{ route('api.work-logs.data') }}?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderWorkLogs(data.data);
                updateStats(data.stats);
            }
        });
}

// Render work logs table
function renderWorkLogs(workLogs) {
    const tbody = document.getElementById('work_logs_table_body');
    const countDisplay = document.getElementById('table_count_display');
    
    if (countDisplay) {
        countDisplay.textContent = `${workLogs.length} Records`;
    }
    
    if (workLogs.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-slate-400 italic">
                    <i class="ph ph-folder-not-found text-4xl mb-2 block mx-auto text-slate-200"></i>
                    {{ __('work_logs.messages.no_work_logs') }}
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = workLogs.map(log => `
        <tr class="hover:bg-slate-50 transition-colors group">
            <td class="px-6 py-4 text-sm font-bold text-indigo-600 font-mono">${log.work_code}</td>
            <td class="px-6 py-4">
                <div class="text-sm font-bold text-slate-700">${log.user.full_name}</div>
                <div class="text-xs text-slate-400 italic">${log.user.email || ''}</div>
            </td>
            <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate" title="${log.description}">${log.description}</td>
            <td class="px-6 py-4 text-sm text-slate-600 font-medium">${log.work_date}</td>
            <td class="px-6 py-4 text-sm text-slate-600 font-bold">${log.work_duration}</td>
            <td class="px-6 py-4 text-sm">${log.status_badge}</td>
            <td class="px-6 py-4 text-sm">${log.priority_badge}</td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-center gap-1">
                    <a href="${log.show_url}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="{{ __('work_logs.buttons.view') }}">
                        <i class="ph ph-eye text-lg"></i>
                    </a>
                    ${log.can_edit ? `
                    <a href="${log.edit_url}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="{{ __('work_logs.buttons.edit') }}">
                        <i class="ph ph-pencil-simple text-lg"></i>
                    </a>
                    ` : ''}
                    ${log.can_delete ? `
                    <button onclick="deleteWorkLog(${log.id})" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="{{ __('work_logs.buttons.delete') }}">
                        <i class="ph ph-trash text-lg"></i>
                    </button>
                    ` : ''}
                </div>
            </td>
        </tr>
    `).join('');
}

// Update stats
function updateStats(stats) {
    const totalLogs = document.querySelector('.work-log-stats-total-logs');
    if (totalLogs) totalLogs.textContent = stats.total_logs || 0;
    
    const totalHours = document.querySelector('.work-log-stats-total-hours');
    if (totalHours) totalHours.textContent = (stats.total_hours || 0).toFixed(1);
    
    const completed = document.querySelector('.work-log-stats-completed');
    if (completed) completed.textContent = stats.completed || 0;
    
    const inProgress = document.querySelector('.work-log-stats-in-progress');
    if (inProgress) inProgress.textContent = stats.in_progress || 0;
}

// Delete work log
function deleteWorkLog(id) {
    Swal.fire({
        title: '{{ __('modules.swal.confirm_title') }}',
        text: '{{ __('modules.swal.delete_warning') }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e11d48', // rose-600
        cancelButtonColor: '#64748b', // slate-500
        confirmButtonText: '{{ __('modules.swal.yes_delete') }}',
        cancelButtonText: '{{ __('modules.swal.cancel') }}',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('work-logs.destroy', ':id') }}`.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __('modules.swal.success') }}',
                        text: '{{ __('modules.swal.data_deleted') }}',
                        confirmButtonColor: '#009B77',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                    loadWorkLogs();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('work_logs.messages.delete_error') }}',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('work_logs.messages.delete_error') }}',
                    text: 'Terjadi kesalahan sistem.'
                });
            });
        }
    });
}
</script>
@endpush
</x-app-layout>
