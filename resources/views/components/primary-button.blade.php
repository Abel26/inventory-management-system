<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-ebara-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-ebara-700 focus:bg-ebara-700 active:bg-ebara-800 focus:outline-none focus:ring-2 focus:ring-ebara-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
