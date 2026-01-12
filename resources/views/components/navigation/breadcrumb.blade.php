@props(['items' => []])

<nav class="flex mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-ebara-500 transition-colors">
                <i class="ph ph-house mr-2"></i>
                Home
            </a>
        </li>
        @foreach($items as $index => $item)
            <li>
                <div class="flex items-center">
                    <i class="ph ph-caret-right text-gray-300 mx-1"></i>
                    @if($index === count($items) - 1)
                        <span class="ml-1 text-sm font-medium text-gray-700">{{ $item['title'] }}</span>
                    @else
                        <a href="{{ $item['url'] }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-ebara-500 transition-colors">
                            {{ $item['title'] }}
                        </a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
