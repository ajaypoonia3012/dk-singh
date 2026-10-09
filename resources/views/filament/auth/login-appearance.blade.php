@if(request()->routeIs('filament.admin.auth.login'))
    @vite('resources/css/theme.css')
    <x-theme.tokens :theme="$theme" />
    <x-theme.login-appearance context="admin" :theme="$theme" standalone />

    <style>
        .fi-simple-layout {
            position: relative;
            z-index: 1;
            background: transparent;
        }

        .fi-simple-main {
            border: 1px solid var(--input-border);
            border-radius: var(--card-radius);
            background: color-mix(in srgb, var(--card-background) 94%, transparent);
            box-shadow: var(--card-shadow);
            color: var(--secondary-color);
            backdrop-filter: blur(0.75rem);
        }
    </style>
@endif
