@props([
    'id' => 'modal',
    'title' => 'Modal Title',
    'maxWidth' => 'lg', // 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', 'full'
    'showClose' => true,
    'showFooter' => true,
    'closeOnBackdrop' => true,
    'closeOnEscape' => true
])

@php
    $maxWidthClasses = match($maxWidth) {
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '4xl' => 'sm:max-w-4xl',
        'full' => 'sm:max-w-full',
        default => 'sm:max-w-lg'
    };
@endphp

<div 
    id="{{ $id }}" 
    class="fixed inset-0 bg-gray-900/50 z-50 overflow-y-auto hidden"
    @keydown.escape.window="{{ $closeOnEscape ? 'document.getElementById(\'' . $id . '\').classList.add(\'hidden\')' : '' }}"
>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div 
            class="relative bg-white rounded-xl shadow-2xl w-full mx-4 {{ $maxWidthClasses }} flex flex-col max-h-[90vh]"
            @click.away="{{ $closeOnBackdrop ? 'document.getElementById(\'' . $id . '\').classList.add(\'hidden\')' : '' }}"
        >
            <!-- Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200 flex-shrink-0">
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                @if($showClose)
                    <button 
                        type="button"
                        onclick="document.getElementById('{{ $id }}').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100"
                        aria-label="Close modal"
                    >
                        <i class="ph ph-x text-2xl"></i>
                    </button>
                @endif
            </div>

            <!-- Body -->
            <div class="p-6 overflow-y-auto flex-1">
                {{ $slot }}
            </div>

            <!-- Footer -->
            @if($showFooter)
                <div class="flex flex-col sm:flex-row justify-end gap-3 p-6 border-t border-gray-200 flex-shrink-0">
                    {{ $footer ?? '' }}
                </div>
            @endif
        </div>
    </div>
</div>
