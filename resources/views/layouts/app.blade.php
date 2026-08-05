<!DOCTYPE html>
<html lang="en">

<head>

<!-- Google tag (gtag.js) -->

@if(!empty($setting?->google_analytics_id) && str_starts_with($setting->google_analytics_id, 'G-'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $setting->google_analytics_id }}"></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', '{{ $setting->google_analytics_id }}');
    </script>
@endif


    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', $setting?->site_name ?? 'DK Singh Fitness')</title>

    <meta name="description" content="@yield('meta_description', $setting?->meta_description ?? 'Premium fitness coaching and transformation programs.')">

    <link rel="canonical" href="@yield('canonical', url()->current())">

    <meta name="google-site-verification"
content="{{ $setting?->google_site_verification }}">


<!-- OPEN GRAPH -->


<meta
    name="keywords"
    content="@yield('meta_keywords', $setting->meta_keywords ?? 'fitness, nutrition, gym, workout')"
>

<meta name="author"
      content="{{ $setting->site_name ?? 'DK Singh Fitness' }}">

<!-- OPEN GRAPH -->

<meta property="og:title" content="@yield('meta_title', $setting?->meta_title ?? $setting?->site_name ?? 'DK Singh Fitness')">

<meta property="og:description"
      content="@yield('meta_description', $setting->meta_description ?? 'Premium fitness coaching and transformation programs.')">

<meta property="og:type" content="@yield('og_type', 'website')">

<meta property="og:url"
      content="{{ url()->current() }}">

<meta property="og:image" content="@yield('meta_image', !empty($setting?->logo) ? asset('storage/' . $setting->logo) : asset('favicon.ico'))">

<!-- TWITTER -->

<meta name="twitter:card" content="summary_large_image">

<meta name="twitter:title"
      content="@yield('meta_title', $setting?->meta_title ?? $setting?->site_name ?? 'DK Singh Fitness')">

<meta name="twitter:description"
      content="@yield('meta_description', $setting?->meta_description ?? 'Premium fitness coaching and transformation programs.')">

<meta name="twitter:image" content="@yield('meta_image', !empty($setting?->logo) ? asset('storage/' . $setting->logo) : asset('favicon.ico'))">

@stack('head')



    <!-- FAVICON -->

   <link rel="icon"
      type="image/png"
      href="{{ !empty($setting?->favicon) ? asset('storage/' . $setting->favicon) : asset('favicon.ico') }}">

    <!-- FONTS -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <!-- STYLES -->

    @vite([
    'resources/css/app.css',
    'resources/css/theme.css',
'resources/css/blog.css',
    'resources/js/app.js',
])

<style>
:root{

    --primary-color: {{ $theme?->primary_color ?? '#facc15' }};
    --secondary-color: {{ $theme?->secondary_color ?? '#111111' }};
    --accent-color: {{ $theme?->accent_color ?? '#ffffff' }};

}
</style>

</head>

<body class="font-[Poppins]">

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- WHATSAPP FLOATING BUTTON -->

    @if($setting && $setting->whatsapp)

    <a href="https://wa.me/{{ $setting->whatsapp }}"
       target="_blank"
       class="fixed bottom-6 right-6 z-50 group">

        <div class="relative">

            <!-- PING -->

            <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-30"></div>

            <!-- BUTTON -->

            <div class="relative bg-green-500 hover:bg-green-400 w-16 h-16 rounded-full flex items-center justify-center shadow-2xl hover:scale-110 transition duration-300">

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="32"
                     height="32"
                     fill="white"
                     viewBox="0 0 24 24">

                    <path d="M20.52 3.48A11.79 11.79 0 0012.03 0C5.4 0 .02 5.37 0 12a11.9 11.9 0 001.64 6L0 24l6.24-1.63A12 12 0 0012 24h.03c6.62 0 12-5.37 12-12 0-3.2-1.25-6.21-3.51-8.52zm-8.49 18.5h-.02a9.88 9.88 0 01-5.03-1.38l-.36-.21-3.7.97.99-3.6-.23-.37A9.9 9.9 0 012.03 12C2.05 6.5 6.52 2.03 12.02 2.03c2.65 0 5.14 1.03 7.01 2.91a9.86 9.86 0 012.9 7c-.02 5.5-4.5 9.98-9.9 9.98zm5.45-7.43c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.25-.46-2.38-1.46-.88-.79-1.48-1.77-1.65-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.5h-.57c-.2 0-.52.08-.8.37-.27.3-1.05 1.02-1.05 2.48 0 1.45 1.08 2.85 1.23 3.05.15.2 2.1 3.2 5.1 4.48.71.3 1.27.48 1.7.62.71.23 1.36.2 1.87.12.57-.08 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35z"/>

                </svg>

            </div>

        </div>

    </a>

    @endif

</body>

</html>
