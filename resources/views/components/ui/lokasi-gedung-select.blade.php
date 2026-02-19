@props([
    'name' => 'location',
    'id' => 'location',
    'value' => null,
    'required' => false,
    'placeholder' => 'Pilih Lokasi',
    'disabled' => false,
    'gedungs' => null
])

@php
    // Jika gedungs tidak di-pass dari controller, ambil dari database
    if (isset($gedungs)) {
        $gedungsList = $gedungs;
    } else {
        $gedungsList = \App\Models\Gedung::orderBy('nama')->get();
    }
@endphp

<div class="space-y-2">
    @if(isset($label))
        <label for="{{ $id }}" class="flex items-center text-sm font-semibold text-gray-700">
            <i class="ph ph-map-pin text-ebara-600 mr-2"></i>
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-1">*</span>
            @endif
        </label>
    @endif
    
    <select 
        name="{{ $name }}" 
        id="{{ $id }}" 
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="w-full py-3 px-4 border-2 border-gray-200 rounded-xl focus:border-ebara-500 focus:ring-2 focus:ring-ebara-500/20 transition-all duration-200 text-sm font-medium"
    >
        <option value="">{{ $placeholder }}</option>
        @foreach($gedungsList as $gedung)
            <option 
                value="{{ $gedung->nama }}" 
                @if($value == $gedung->nama) selected @endif
            >
                {{ $gedung->nama }} ({{ $gedung->gedung_id }})
            </option>
        @endforeach
    </select>
</div>