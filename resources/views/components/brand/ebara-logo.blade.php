@props(['class' => 'h-8 w-8'])

<svg 
    {{ $attributes->merge(['class' => $class]) }} 
    viewBox="0 0 100 100" 
    fill="none" 
    xmlns="http://www.w3.org/2000/svg"
>
    <!-- Ebara-style Logo (simplified representation) -->
    <circle cx="50" cy="50" r="45" fill="#009B77" fill-opacity="0.1"/>
    <path d="M50 15 L85 50 L50 85 L15 50 Z" fill="#009B77"/>
    <path d="M50 25 L75 50 L50 75 L25 50 Z" fill="white"/>
    <circle cx="50" cy="50" r="10" fill="#009B77"/>
</svg>
