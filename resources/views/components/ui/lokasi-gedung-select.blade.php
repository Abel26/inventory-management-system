@props([
    'name' => 'location',
    'id' => 'location',
    'value' => null,
    'required' => false,
    'placeholder' => 'Pilih Lokasi',
    'class' => 'w-full rounded-lg border-gray-300 shadow-sm border p-2.5 focus:ring-2 focus:ring-ebara-500 focus:border-transparent transition',
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

<div>
    @if(isset($label))
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select 
        name="{{ $name }}" 
        id="{{ $id }}" 
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="{{ $class }}"
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