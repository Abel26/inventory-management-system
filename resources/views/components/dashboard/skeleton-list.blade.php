@props([
    'rows' => 5,
])

<div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <div class="h-6 bg-gray-200 rounded animate-pulse w-48"></div>
        <div class="h-4 bg-gray-200 rounded animate-pulse w-24"></div>
    </div>
    <div class="space-y-4">
        @for($i = 0; $i < $rows; $i++)
            <div class="flex items-start gap-4">
                <div class="w-8 h-8 bg-gray-200 rounded-lg animate-pulse flex-shrink-0"></div>
                <div class="flex-1">
                    <div class="h-4 bg-gray-200 rounded animate-pulse w-full mb-2"></div>
                    <div class="h-3 bg-gray-200 rounded animate-pulse w-24"></div>
                </div>
            </div>
        @endfor
    </div>
</div>
