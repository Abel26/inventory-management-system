@props([
    'workLog' => null,
    'showActions' => true,
])

@if(!$workLog)
    <div class="border border-red-200 bg-red-50 rounded-lg p-4">
        <p class="text-red-600 text-sm">{{ __('work_logs.messages.invalid_work_log') }}</p>
    </div>
@else
<div class="group bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-xl transition-all duration-300 relative overflow-hidden {{ $workLog->isLocked() ? 'opacity-80' : '' }}">
    <!-- Background Accent -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 -mr-16 -mt-16 rounded-full transition-transform group-hover:scale-110"></div>
    
    <div class="relative flex flex-col sm:flex-row justify-between items-start gap-6">
        <div class="flex-1 space-y-4">
            <!-- Badges Row -->
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $workLog->status->getColor() }}">
                    <i class="ph ph-{{ $workLog->status->getIcon() }} mr-1.5 text-sm"></i>
                    {{ $workLog->status->getLabel() }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $workLog->priority->getColor() }}">
                    <i class="ph ph-{{ $workLog->priority->getIcon() }} mr-1.5 text-sm"></i>
                    {{ $workLog->priority->getLabel() }}
                </span>
                @if($workLog->isLocked())
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700">
                    <i class="ph ph-lock-key mr-1.5 text-sm"></i>
                    {{ __('work_logs.status.locked') }}
                </span>
                @endif
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-500">
                    <i class="ph ph-tag mr-1.5 text-sm"></i>
                    {{ $workLog->work_type->getLabel() }}
                </span>
            </div>

            <!-- Content Area -->
            <div>
                <h3 class="text-xl font-black text-slate-800 leading-tight group-hover:text-indigo-600 transition-colors">
                    {{ $workLog->description }}
                </h3>
                <div class="mt-3 flex flex-wrap gap-x-6 gap-y-2 text-sm">
                    <div class="flex items-center text-slate-500 font-bold">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center mr-2 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                            <i class="ph ph-calendar-blank text-lg"></i>
                        </div>
                        {{ $workLog->work_date->format('d M Y') }}
                    </div>
                    <div class="flex items-center text-slate-500 font-bold">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center mr-2 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                            <i class="ph ph-timer text-lg"></i>
                        </div>
                        {{ $workLog->work_duration }}
                    </div>
                    @if($workLog->location)
                    <div class="flex items-center text-slate-500 font-bold">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center mr-2 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                            <i class="ph ph-map-pin text-lg"></i>
                        </div>
                        {{ $workLog->location->nama }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Progress Information -->
            @if($workLog->completion_percentage !== null)
            <div class="pt-2">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ __('work_logs.fields.completion_percentage') }}</span>
                    <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg">{{ $workLog->completion_percentage }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden shadow-inner flex">
                    <div class="h-full bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(79,70,229,0.4)]" 
                         style="width: {{ $workLog->completion_percentage }}%"></div>
                </div>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        @if($showActions)
        <div class="flex flex-row sm:flex-col items-center gap-2 w-full sm:w-auto mt-4 sm:mt-0 border-t sm:border-t-0 border-slate-100 pt-4 sm:pt-0">
            <a href="{{ route('work-logs.show', $workLog->id) }}?from=my-work"
               class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-white border-2 border-slate-200 hover:border-indigo-500 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 font-black rounded-2xl transition-all shadow-sm active:scale-95 text-xs uppercase tracking-widest">
                <i class="ph ph-eye mr-2 text-lg"></i>
                {{ __('work_logs.actions.view') }}
            </a>
            @if(!$workLog->isLocked())
            <a href="{{ route('work-logs.edit', $workLog->id) }}?from=my-work"
               class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-white border-2 border-slate-200 hover:border-amber-500 hover:bg-amber-50 text-slate-700 hover:text-amber-700 font-black rounded-2xl transition-all shadow-sm active:scale-95 text-xs uppercase tracking-widest">
                <i class="ph ph-pencil-simple mr-2 text-lg"></i>
                {{ __('work_logs.actions.edit') }}
            </a>
            @else
            <div class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-slate-50 border-2 border-slate-100 text-slate-300 font-black rounded-2xl cursor-not-allowed text-xs uppercase tracking-widest">
                <i class="ph ph-lock mr-2 text-lg"></i>
                {{ __('work_logs.status.locked') }}
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endif
