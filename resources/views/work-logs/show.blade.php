<x-app-layout>
    <x-slot name="title">{{ __('work_logs.page.show_title', ['code' => $workLog->work_code]) }}</x-slot>

<div class="min-h-screen bg-[#f8fafc] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <a href="{{ request()->has('from') && request()->input('from') == 'my-work' ? route('work-logs.my-work') : route('work-logs.index') }}"
                   class="inline-flex items-center text-xs font-black text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest mb-6 group">
                    <div class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center mr-3 group-hover:border-indigo-200 group-hover:bg-indigo-50 transition-all">
                        <i class="ph ph-caret-left text-lg"></i>
                    </div>
                    {{ __('work_logs.actions.back') }}
                </a>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none mb-2">
                    {{ __('work_logs.page.show_title', ['code' => $workLog->work_code]) }}
                </h1>
                <p class="text-slate-500 font-bold flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    {{ $workLog->work_date->translatedFormat('l, d F Y') }}
                </p>
            </div>

            @if($workLog->canEdit())
            <div class="flex items-center gap-3">
                <a href="{{ route('work-logs.edit', $workLog->id) }}"
                   class="inline-flex items-center px-6 py-3 bg-white border-2 border-slate-200 hover:border-amber-500 hover:bg-amber-50 text-slate-700 hover:text-amber-700 font-black rounded-2xl transition-all shadow-sm active:scale-95 text-xs uppercase tracking-widest group">
                    <i class="ph ph-pencil-simple mr-2 text-xl group-hover:rotate-12 transition-transform"></i>
                    {{ __('work_logs.actions.edit') }}
                </a>
                <button type="button"
                        onclick="confirmDelete('{{ route('work-logs.destroy', $workLog->id) }}')"
                        class="inline-flex items-center px-6 py-3 bg-white border-2 border-slate-200 hover:border-rose-500 hover:bg-rose-50 text-slate-700 hover:text-rose-700 font-black rounded-2xl transition-all shadow-sm active:scale-95 text-xs uppercase tracking-widest group">
                    <i class="ph ph-trash mr-2 text-xl group-hover:shake transition-transform"></i>
                    {{ __('work_logs.actions.delete') }}
                </button>
            </div>
            @endif
        </div>

        <!-- Main Detail Card -->
        <div class="bg-white shadow-2xl shadow-indigo-100 rounded-[2.5rem] border border-slate-100 overflow-hidden relative group">
            <!-- Decorative Element -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50/30 -mr-32 -mt-32 rounded-full pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>

            <!-- Top Status Bar -->
            <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-100 relative">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest {{ $workLog->status->getColor() }} shadow-sm">
                            <i class="ph ph-{{ $workLog->status->getIcon() }} mr-2 text-lg"></i>
                            {{ $workLog->status->getLabel() }}
                        </span>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest {{ $workLog->priority->getColor() }} shadow-sm">
                            <i class="ph ph-{{ $workLog->priority->getIcon() }} mr-2 text-lg"></i>
                            {{ $workLog->priority->getLabel() }}
                        </span>
                    </div>
                    @if($workLog->isLocked())
                        <span class="inline-flex items-center px-4 py-1.5 bg-amber-100 text-amber-700 rounded-full text-xs font-black uppercase tracking-widest border border-amber-200 shadow-sm animate-pulse">
                            <i class="ph ph-lock-key text-lg mr-2"></i>
                            {{ __('work_logs.status.locked') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-8 sm:p-12 space-y-12 relative">
                <!-- Description Section -->
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="ph ph-article text-lg"></i>
                        {{ __('work_logs.fields.description') }}
                    </h3>
                    <div class="bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100">
                        <p class="text-slate-800 text-xl font-bold leading-relaxed">{{ $workLog->description }}</p>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    <!-- Work Type -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 text-slate-400 transition-colors group-hover:text-indigo-400">
                            <i class="ph ph-briefcase text-lg"></i>
                            {{ __('work_logs.fields.work_type') }}
                        </h3>
                        <p class="text-lg font-black text-slate-700 ml-7">{{ $workLog->work_type->getLabel() }}</p>
                    </div>

                    <!-- Time Stats -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i class="ph ph-clock text-lg"></i>
                            {{ __('work_logs.fields.duration') }}
                        </h3>
                        <div class="flex items-center gap-4 ml-7">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('work_logs.units.timeline') }}</span>
                                <span class="text-lg font-black text-slate-700">{{ $workLog->start_time->format('H:i') }} - {{ $workLog->end_time->format('H:i') }}</span>
                            </div>
                            <div class="w-px h-8 bg-slate-200"></div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('work_logs.units.total') }}</span>
                                <span class="text-lg font-black text-indigo-600">{{ $workLog->work_duration }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    @if($workLog->location)
                    <div class="space-y-2">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i class="ph ph-map-pin text-lg"></i>
                            {{ __('work_logs.fields.location') }}
                        </h3>
                        <p class="text-lg font-black text-slate-700 ml-7">{{ $workLog->location->nama }}</p>
                    </div>
                    @endif
                </div>

                <!-- Progress Section -->
                @if($workLog->completion_percentage !== null)
                <div class="space-y-4 pt-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i class="ph ph-chart-line text-lg"></i>
                            {{ __('work_logs.fields.completion_percentage') }}
                        </h3>
                        <span class="text-lg font-black text-indigo-600 bg-indigo-50 px-4 py-1 rounded-2xl ring-1 ring-indigo-200 shadow-sm">{{ $workLog->completion_percentage }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-4 overflow-hidden border-2 border-white shadow-inner">
                        <div class="h-full bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 rounded-full transition-all duration-1000 shadow-[0_0_15px_rgba(79,70,229,0.3)]" 
                             style="width: {{ $workLog->completion_percentage }}%"></div>
                    </div>
                </div>
                @endif

                <!-- Notes Section -->
                @if($workLog->notes)
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="ph ph-notebook text-lg"></i>
                        {{ __('work_logs.fields.notes') }}
                    </h3>
                    <div class="bg-indigo-50/50 p-6 rounded-[2rem] border border-indigo-100/50">
                        <p class="text-slate-700 font-bold whitespace-pre-wrap leading-relaxed">{{ $workLog->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Attachment Section -->
                @if($workLog->attachment_path)
                <div class="space-y-4">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="ph ph-paperclip text-lg"></i>
                        {{ __('work_logs.fields.attachment') ?? 'Lampiran Bukti Kerja' }}
                    </h3>
                    <div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-slate-200 hover:border-indigo-300 transition-all group/file">
                        <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover/file:bg-indigo-50 group-hover/file:text-indigo-500 transition-all">
                            <i class="ph ph-file-text text-2xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-slate-700 truncate">{{ $workLog->attachment_name }}</p>
                            <p class="text-[10px] font-bold text-slate-400">{{ round($workLog->attachment_size / 1024, 2) }} KB</p>
                        </div>
                        <a href="{{ asset('storage/' . $workLog->attachment_path) }}" target="_blank" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-black hover:bg-indigo-100 transition-colors">
                            {{ __('work_logs.actions.view') }}
                        </a>
                    </div>
                </div>
                @endif

                <!-- Admin Comments Section -->
                <div class="space-y-6 pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <i class="ph ph-chats text-lg"></i>
                        {{ __('work_logs.fields.admin_comments') ?? 'Umpan Balik Admin' }}
                    </h3>
                    
                    <div class="space-y-4">
                        @forelse($workLog->admin_comments ?? [] as $comment)
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs">
                                    {{ substr($comment['admin_name'] ?? 'A', 0, 1) }}
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-black text-slate-700">{{ $comment['admin_name'] ?? 'Admin' }}</span>
                                        <span class="text-[10px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm font-bold text-slate-600">{{ $comment['comment'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-sm font-bold text-slate-400 italic">Belum ada umpan balik dari admin.</p>
                            </div>
                        @endforelse
                    </div>

                    @if(Auth::user()->canViewUserManagement())
                        <form action="{{ route('work-logs.add-comment', $workLog->id) }}" method="POST" class="mt-6 space-y-3">
                            @csrf
                            <textarea name="comment" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Tulis umpan balik..."></textarea>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-100 transition-all active:scale-95">
                                    Kirim Umpan Balik
                                </button>
                            </div>
                        </form>
                    @endif
                </div>

                <!-- Technical Information -->
                <div class="pt-10 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="flex items-center gap-4 group/meta">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover/meta:bg-indigo-50 group-hover/meta:text-indigo-500 transition-all duration-300">
                            <i class="ph ph-user text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('work_logs.fields.created_by') }}</p>
                            <p class="text-sm font-black text-slate-700 tracking-tight">{{ $workLog->user->full_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 group/meta">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover/meta:bg-indigo-50 group-hover/meta:text-indigo-500 transition-all duration-300">
                            <i class="ph ph-calendar text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('work_logs.fields.created_at') }}</p>
                            <p class="text-sm font-black text-slate-700 tracking-tight">{{ $workLog->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 group/meta">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover/meta:bg-indigo-50 group-hover/meta:text-indigo-500 transition-all duration-300">
                            <i class="ph ph-clock-countdown text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('work_logs.fields.updated_at') }}</p>
                            <p class="text-sm font-black text-slate-700 tracking-tight">{{ $workLog->updated_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(url) {
    Swal.fire({
        title: 'Konfirmasi',
        text: '{{ __('work_logs.messages.confirm_delete') }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Pekerjaan telah dihapus!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '{{ request()->has('from') && request()->input('from') == 'my-work' ? route('work-logs.my-work') : route('work-logs.index') }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || '{{ __('work_logs.messages.delete_failed') }}',
                        confirmButtonColor: '#dc2626',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ __('work_logs.messages.delete_failed') }}',
                    confirmButtonColor: '#dc2626',
                });
            });
        }
    });
}
</script>
</x-app-layout>
