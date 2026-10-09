<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => $theme?->dark_mode_enabled])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $setting?->site_name ?? config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/css/theme.css', 'resources/js/app.js'])
        <x-theme.tokens :theme="$theme" />
    </head>
    <body class="antialiased">
        @php($isLogin = request()->routeIs('login'))

        <x-theme.login-appearance context="public" :theme="$theme" :enabled="$isLogin">
            <main @class([
                'theme-login-page min-h-screen flex flex-col justify-center items-center px-4 py-8 sm:px-6',
                'theme-surface-muted' => ! $isLogin,
            ])>
                <a href="/" class="mb-6" aria-label="{{ $setting?->site_name ?: config('app.name') }} home">
                    @if(filled($setting?->logo))
                        <img src="{{ asset('storage/'.$setting->logo) }}" alt="{{ $setting->site_name }}" class="w-20 h-20 object-contain">
                    @else
                        <span class="theme-section-heading text-xl">{{ $setting?->site_name ?: config('app.name') }}</span>
                    @endif
                </a>

                <x-theme.card class="theme-login-card w-full sm:max-w-md theme-card-padding overflow-hidden">
                    {{ $slot }}
                </x-theme.card>
            </main>
        </x-theme.login-appearance>
    </body>
</html>
