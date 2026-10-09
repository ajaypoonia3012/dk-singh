<button {{ $attributes->merge(['type' => 'submit'])->class(['theme-button', 'theme-button-primary', 'text-xs uppercase tracking-widest']) }}>
    {{ $slot }}
</button>
