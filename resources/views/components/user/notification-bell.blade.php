@props(['count' => 0, 'notifications' => null])

<div x-data="{ open: false }" class="relative">
    <!-- Bell Icon Trigger -->
    <button
        @click="open = !open"
        class="relative p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-ebara-500">
        <i class="ph ph-bell text-xl"></i>

        @if($count > 0)
            <span class="absolute top-1 right-1 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] font-medium text-white items-center justify-center">
                    {{ $count > 9 ? '9+' : $count }}
                </span>
            </span>
        @endif
    </button>

    <!-- Dropdown Panel -->
    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden"
        style="display: none;">
        
        <!-- Header -->
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Notifikasi</h3>
            @if($count > 0)
                <span class="px-2 py-1 text-xs font-medium bg-ebara-600 text-white rounded-full">
                    {{ $count }} baru
                </span>
            @endif
        </div>

        <!-- Content List -->
        <div class="max-h-80 overflow-y-auto">
            @if($notifications && $notifications->count() > 0)
                @foreach($notifications as $notification)
                    <a href="{{ route('reports.show', $notification->id) }}" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            @if($notification->user && $notification->user->avatar)
                                <img src="{{ $notification->user->avatar }}" alt="{{ $notification->user->name }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-ebara-500 to-ebara-700 flex items-center justify-center text-white font-semibold">
                                    {{ $notification->user ? substr($notification->user->name, 0, 1) : 'U' }}
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <!-- Asset Name & Issue Type -->
                            <p class="text-sm font-medium text-gray-900 truncate">
                                @if($notification->reportable)
                                    {{ $notification->reportable->name ?? $notification->reportable->model_name ?? 'Aset' }}
                                @else
                                    Aset tidak diketahui
                                @endif
                                <span class="font-normal text-gray-600">mengalami</span>
                                <span class="text-ebara-600">{{ $notification->issue_type }}</span>
                            </p>

                            <!-- Reporter & Time -->
                            <p class="text-xs text-gray-500 mt-1">
                                Dilaporkan oleh {{ $notification->user ? $notification->user->name : 'Unknown' }} • 
                                {{ $notification->created_at ? $notification->created_at->diffForHumans() : '-' }}
                            </p>
                        </div>

                        <!-- Status Indicator -->
                        <div class="flex-shrink-0">
                            <span class="w-2 h-2 rounded-full bg-ebara-600"></span>
                        </div>
                    </a>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-8 px-4 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <i class="ph ph-bell-slash text-3xl text-gray-400"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-900">Tidak ada notifikasi baru</p>
                    <p class="text-xs text-gray-500 mt-1">Semua laporan telah ditinjau</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        @if($count > 0)
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                <a href="{{ route('reports.index') }}" class="block text-center text-sm font-medium text-ebara-600 hover:text-ebara-700 transition-colors">
                    Lihat Semua Laporan →
                </a>
            </div>
        @endif
    </div>
</div>
