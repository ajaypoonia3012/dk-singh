<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => $theme?->dark_mode_enabled])>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $setting?->meta_title ?: $setting?->site_name ?: config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', $setting?->meta_description ?: $setting?->site_tagline)">
    @if(filled($setting?->meta_keywords))
        <meta name="keywords" content="@yield('meta_keywords', $setting->meta_keywords)">
    @endif
    <meta name="author" content="{{ $setting?->legal_business_name ?: $setting?->site_name ?: config('app.name') }}">
    <link rel="canonical" href="{{ url()->current() }}">

    @if(filled($setting?->google_site_verification))
        <meta name="google-site-verification" content="{{ $setting->google_site_verification }}">
    @endif

    <meta property="og:title" content="@yield('meta_title', $setting?->meta_title ?: $setting?->site_name)">
    <meta property="og:description" content="@yield('meta_description', $setting?->meta_description ?: $setting?->site_tagline)">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(filled($setting?->logo))
        <meta property="og:image" content="{{ asset('storage/'.$setting->logo) }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('meta_title', $setting?->meta_title ?: $setting?->site_name)">
    <meta name="twitter:description" content="@yield('meta_description', $setting?->meta_description ?: $setting?->site_tagline)">
    @if(filled($setting?->logo))
        <meta name="twitter:image" content="{{ asset('storage/'.$setting->logo) }}">
    @endif

    <link rel="icon" href="{{ filled($setting?->favicon) ? asset('storage/'.$setting->favicon) : asset('favicon.ico') }}">
    @if(filled($setting?->apple_touch_icon))
        <link rel="apple-touch-icon" href="{{ asset('storage/'.$setting->apple_touch_icon) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite([
        'resources/css/app.css',
        'resources/css/theme.css',
        'resources/css/blog.css',
        'resources/js/app.js',
    ])

    <style>
        :root {
            --primary-color: {{ $theme?->primary_color ?: '#facc15' }};
            --secondary-color: {{ $theme?->secondary_color ?: '#111111' }};
            --accent-color: {{ $theme?->accent_color ?: '#ffffff' }};
            --success-color: {{ $theme?->success_color ?: '#22c55e' }};
            --warning-color: {{ $theme?->warning_color ?: '#f59e0b' }};
            --danger-color: {{ $theme?->danger_color ?: '#ef4444' }};
            --info-color: {{ $theme?->info_color ?: '#3b82f6' }};
            --neutral-color: {{ $theme?->neutral_color ?: '#6b7280' }};
            --heading-font: "{{ $theme?->heading_font ?: 'Poppins' }}", sans-serif;
            --body-font: "{{ $theme?->body_font ?: 'Poppins' }}", sans-serif;
            --font-scale: {{ $theme?->font_scale ?: 1 }};
            --heading-weight: {{ $theme?->heading_weight ?: 800 }};
            --body-weight: {{ $theme?->body_weight ?: 400 }};
            --letter-spacing: {{ $theme?->letter_spacing ?: 0 }}px;
            --line-height: {{ $theme?->line_height ?: 1.5 }};
            --primary-button-text: {{ $theme?->primary_button_text_color ?: '#111111' }};
            --secondary-button-bg: {{ $theme?->secondary_button_background ?: '#111111' }};
            --secondary-button-text: {{ $theme?->secondary_button_text_color ?: '#ffffff' }};
            --button-radius: {{ $theme?->button_radius ?: '1rem' }};
            --button-shadow: {{ $theme?->button_shadow ?: '0 10px 25px rgba(0,0,0,.12)' }};
            --button-hover-transform: {{ $theme?->button_hover_animation ?: 'translateY(-2px)' }};
            --card-background: {{ $theme?->card_background ?: '#ffffff' }};
            --card-radius: {{ $theme?->card_radius ?: '1.5rem' }};
            --card-shadow: {{ $theme?->card_shadow ?: '0 20px 40px rgba(0,0,0,.08)' }};
            --navbar-height: {{ $theme?->navbar_height ?: 96 }}px;
            --navbar-background: {{ $theme?->navbar_background ?: '#ffffff' }};
            --navbar-text: {{ $theme?->navbar_text_color ?: '#111111' }};
            --navbar-hover: {{ $theme?->navbar_hover_color ?: '#facc15' }};
            --footer-background: {{ $theme?->footer_background ?: '#000000' }};
            --footer-text: {{ $theme?->footer_text_color ?: '#ffffff' }};
            --footer-link: {{ $theme?->footer_link_color ?: '#9ca3af' }};
            --input-radius: {{ $theme?->input_radius ?: '1rem' }};
            --input-border: {{ $theme?->input_border_color ?: '#d1d5db' }};
            --input-focus: {{ $theme?->input_focus_color ?: '#facc15' }};
            --container-width: {{ $theme?->container_width ?: 1280 }}px;
            --section-padding: {{ $theme?->section_padding ?: 96 }}px;
            --spacing-scale: {{ $theme?->spacing_scale ?: 1 }};
            --sidebar-width: {{ $theme?->sidebar_width ?: 320 }}px;
            --dark-background: {{ $theme?->dark_background ?: '#111111' }};
            --dark-surface: {{ $theme?->dark_surface ?: '#1f2937' }};
            --dark-text: {{ $theme?->dark_text ?: '#f9fafb' }};
        }

        html { font-size: calc(100% * var(--font-scale)); }
        body {
            background: var(--accent-color);
            color: var(--secondary-color);
            font-family: var(--body-font);
            font-weight: var(--body-weight);
            letter-spacing: var(--letter-spacing);
            line-height: var(--line-height);
        }
        h1, h2, h3, h4, h5, h6 { font-family: var(--heading-font); font-weight: var(--heading-weight); }
        .dark body { background: var(--dark-background); color: var(--dark-text); }
        @if($theme?->animations_enabled === false)
            *, *::before, *::after { animation: none !important; transition: none !important; }
        @endif
        {!! $theme?->custom_css !!}
    </style>

    @if(filled($setting?->google_analytics_id) && str_starts_with($setting->google_analytics_id, 'G-'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $setting->google_analytics_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', @js($setting->google_analytics_id));
        </script>
    @endif

    @stack('head')
</head>
<body>
    @if($theme?->announcement_enabled && filled($theme?->announcement_text))
        <aside class="px-4 py-2 text-center text-sm font-semibold" style="background: var(--primary-color); color: var(--primary-button-text);">
            @if(filled($theme->announcement_link))
                <a href="{{ $theme->announcement_link }}">{{ $theme->announcement_text }}</a>
            @else
                {{ $theme->announcement_text }}
            @endif
        </aside>
    @endif

    @include('partials.navbar')

    <main>@yield('content')</main>

    @include('partials.footer')

    @if(filled($setting?->whatsapp))
        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $setting->whatsapp) }}"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="Contact {{ $setting->site_name }} on WhatsApp"
           class="fixed bottom-6 right-6 z-50 group">
            <span class="relative bg-green-500 hover:bg-green-400 w-16 h-16 rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20.52 3.48A11.79 11.79 0 0012.03 0C5.4 0 .02 5.37 0 12a11.9 11.9 0 001.64 6L0 24l6.24-1.63A12 12 0 0012 24h.03c6.62 0 12-5.37 12-12 0-3.2-1.25-6.21-3.51-8.52zm-8.49 18.5h-.02a9.88 9.88 0 01-5.03-1.38l-.36-.21-3.7.97.99-3.6-.23-.37A9.9 9.9 0 012.03 12C2.05 6.5 6.52 2.03 12.02 2.03c2.65 0 5.14 1.03 7.01 2.91a9.86 9.86 0 012.9 7c-.02 5.5-4.5 9.98-9.9 9.98zm5.45-7.43c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.25-.46-2.38-1.46-.88-.79-1.48-1.77-1.65-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.5h-.57c-.2 0-.52.08-.8.37-.27.3-1.05 1.02-1.05 2.48 0 1.45 1.08 2.85 1.23 3.05.15.2 2.1 3.2 5.1 4.48.71.3 1.27.48 1.7.62.71.23 1.36.2 1.87.12.57-.08 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35z"/>
                </svg>
            </span>
        </a>
    @endif

    @stack('scripts')
    @if(filled($theme?->custom_js))
        <script>{!! $theme->custom_js !!}</script>
    @endif
</body>
</html>
