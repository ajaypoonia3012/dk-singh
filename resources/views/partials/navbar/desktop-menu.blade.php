<div class="hidden lg:flex items-center justify-center gap-3.5 xl:gap-5 2xl:gap-6 text-sm font-semibold shrink-0">

    <a href="/" class="theme-navbar-link transition">
        {{ $setting?->home_label ?: 'Home' }}
    </a>

    {{-- PRIMARY FITNESS MEGA-MENU HUB --}}
    <div class="relative py-2" id="fitnessMegaMenuTriggerContainer">
        <a
            href="{{ route('fitness.index') }}"
            id="fitnessMegaMenuTrigger"
            class="theme-navbar-link transition inline-flex items-center gap-1 focus:outline-none"
            aria-expanded="false"
            aria-haspopup="true"
            aria-controls="fitnessMegaMenu"
        >
            <span>Fitness</span>
            <svg id="fitnessMegaMenuChevron" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
            </svg>
        </a>
    </div>

    {{-- UNIFIED COACHING & PROGRAMS HUB --}}
    <a href="{{ route('coaching-programs.index') }}" class="theme-navbar-link transition">
        {{ $setting?->coaching_programs_label ?: 'Coaching & Programs' }}
    </a>

    <a href="/transformations" class="theme-navbar-link transition">
        {{ $setting?->transformation_label ?: 'Transformations' }}
    </a>

    <a href="/blog" class="theme-navbar-link transition">
        {{ $setting?->blog_label ?: 'Blog' }}
    </a>

    <a href="/about" class="theme-navbar-link transition">
        {{ $setting?->about_label ?: 'About' }}
    </a>

    <a href="{{ route('products.index') }}" class="theme-navbar-link transition">
        {{ $setting?->product_label ?: 'Products' }}
    </a>

    <a href="/contact" class="theme-navbar-link transition">
        {{ $setting?->contact_label ?: 'Contact' }}
    </a>

</div>
