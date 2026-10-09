<button {{ $attributes->merge(['type' => 'submit'])->class(['theme-button', 'theme-status-danger', 'text-xs uppercase tracking-widest']) }}>
    {{ $slot }}
</button>
