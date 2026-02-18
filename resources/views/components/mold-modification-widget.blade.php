<!-- ROW 4: MOLD MODIFICATION SCHEDULE -->
<div class="grid grid-cols-12 gap-6 w-full mt-6">
    <div class="col-span-12 w-full">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-ebara-100 rounded-lg">
                        <i class="ph ph-gear text-ebara-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Jadwal Modifikasi Cetakan</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Tracking modifikasi mold untuk produksi</p>
                    </div>
                </div>
                <button 
                    @click="window.moldModalData.showModal = true" 
                    class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-ebara-600 text-white rounded-lg hover:bg-ebara-700 transition-colors text-sm"
                >
                    <i class="ph ph-plus-circle"></i>
                    <span class="hidden sm:inline">Input Jadwal</span>
                </button>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-[700px] w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">No</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Model Name</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Before</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">After</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Production Date</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($moldModifications ?? [] as $item)
                                <tr :class="'{{ $item['row_class'] ?? '' }} transition-colors'">
                                    <td class="px-3 py-3 text-sm" :class="'{{ $item['text_class'] ?? '' }}'">
                                        <span>{{ $loop->index + 1 }}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="text-sm font-medium" :class="'{{ $item['text_class'] ?? '' }}'">{{ $item['model_name'] }}</div>
                                        <div class="text-xs text-gray-500" @if($item['asset_model'])>{{ '(' . $item['asset_model']['model_code'] . ')' }}</div>
                                    </td>
                                    <td class="px-3 py-3 text-sm" :class="'{{ $item['text_class'] ?? '' }}'">{{ $item['spec_before'] }}</td>
                                    <td class="px-3 py-3 text-sm" :class="'{{ $item['text_class'] ?? '' }}'">{{ $item['spec_after'] }}</td>
                                    <td class="px-3 py-3 text-sm" :class="'{{ $item['text_class'] ?? '' }}'">
                                        <div>{{ $item['production_date_formatted'] }}</div>
                                        @if($item['is_critical'] ?? false)
                                            <div class="text-xs mt-1">
                                                <span class="font-bold text-red-600 flex items-center gap-1">
                                                    <i class="ph ph-warning"></i>
                                                    H-7 WARNING!
                                                </span>
                                            </div>
                                        @elseif($item['status'] === 'pending')
                                            <div class="text-xs text-gray-500">
                                                <span>{{ $item['days_remaining'] . ' hari lagi' }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3">
                                        <span 
                                            class="px-2 py-1 text-xs font-medium rounded-full"
                                            :class="{
                                                'bg-green-100 text-green-700': '{{ $item['status'] }}' === 'done',
                                                'bg-yellow-100 text-yellow-700': '{{ $item['status'] }}' === 'pending' && !({{ $item['is_critical'] ?? false }}),
                                                'bg-red-100 text-red-700': '{{ $item['status'] }}' === 'pending' && ({{ $item['is_critical'] ?? false }})
                                            }"
                                        >
                                            {{ $item['status_label'] }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3">
                                        @if($item['status'] === 'pending')
                                            <button 
                                                @click="window.moldModalData.markAsDone({{ $item['id'] }})"
                                                class="text-xs font-medium text-ebara-600 hover:text-ebara-700 flex items-center gap-1 transition-colors"
                                            >
                                                <i class="ph ph-check-circle"></i>
                                                Mark as Done
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                                <i class="ph ph-check-circle text-green-600"></i>
                                                Completed
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-8 text-center text-sm text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="ph ph-gear text-4xl mb-2"></i>
                                            <p>Belum ada jadwal modifikasi cetakan</p>
                                            <button 
                                                @click="window.moldModalData.showModal = true"
                                                class="mt-3 text-ebara-600 hover:text-ebara-700 text-sm font-medium"
                                            >
                                                + Tambah Jadwal Pertama
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>