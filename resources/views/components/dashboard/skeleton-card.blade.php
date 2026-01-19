@props([
    'count' => 1,
    'height' => 'h-24',
])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @for($i = 0; $i < $count; $i++)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="h-4 bg-gray-200 rounded animate-pulse mb-2 w-24"></div>
                    <div class="h-8 bg-gray-200 rounded animate-pulse mb-2 w-32"></div>
                    <div class="h-4 bg-gray-200 rounded animate-pulse w-20"></div>
                </div>
                <div class="w-12 h-12 bg-gray-200 rounded-lg animate-pulse"></div>
            </div>
        </div>
    @endfor
</div>
