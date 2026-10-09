<button {{ $attributes->merge(['type' => 'button'])->class(['theme-button', 'theme-button-secondary', 'text-xs uppercase tracking-widest', 'disabled:opacity-25']) }}>
    {{ $slot }}
</button>
