@props([
    'height' => 'h-[350px]',
])

<div class="bg-white rounded-xl border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <div class="h-6 bg-gray-200 rounded animate-pulse w-48"></div>
        <div class="h-4 bg-gray-200 rounded animate-pulse w-24"></div>
    </div>
    <div class="{{ $height }} flex items-center justify-center">
        <div class="w-full h-full bg-gray-100 rounded-lg animate-pulse"></div>
    </div>
</div>
