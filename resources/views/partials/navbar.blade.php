<nav
    id="navbar"
    class="theme-navbar fixed top-0 left-0 w-full z-50 transition-all duration-500 backdrop-blur-xl border-b"
>
    <x-theme.page-container>
        <div class="flex items-center justify-between gap-4" style="height: var(--navbar-height);">
            <a href="/" class="flex items-center theme-content-gap shrink-0">
                @if(filled($setting?->logo))
                    <img
                        src="{{ asset('storage/'.$setting->logo) }}"
                        alt="{{ $setting?->site_name ?: config('app.name') }}"
                        class="h-14 w-auto"
                    >
                @else
                    <div>
                        <h2 class="theme-section-heading text-2xl leading-tight">{{ $setting?->site_name ?: config('app.name') }}</h2>
                        <p class="theme-text-neutral text-sm">{{ $setting?->site_tagline ?: '' }}</p>
                    </div>
                @endif
            </a>

            @include('partials.navbar.desktop-menu')
            @include('partials.navbar.right-menu')

            <button
                id="mobileMenuButton"
                type="button"
                class="theme-navbar-toggle lg:hidden flex items-center justify-center w-12 h-12 text-2xl font-bold"
                aria-controls="mobileMenu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                ☰
            </button>
        </div>
    </x-theme.page-container>

    {{-- DESKTOP FITNESS MEGA-MENU --}}
    @include('partials.navbar.mega-menu')

    {{-- MOBILE MENU DRAWER --}}
    <div id="mobileMenu" class="theme-navbar-mobile hidden lg:hidden border-t">
        <x-theme.page-container class="theme-stack-lg py-8 flex flex-col">
            <a href="/" class="theme-navbar-link font-semibold text-lg">{{ $setting?->home_label ?: 'Home' }}</a>

            {{-- MOBILE FITNESS ACCORDION HUB --}}
            <div class="border-y py-2 my-1" style="border-color: color-mix(in srgb, var(--navbar-text) 15%, transparent);">
                <div class="flex items-center justify-between">
                    <a href="{{ route('fitness.index') }}" class="theme-navbar-link font-semibold text-lg flex-grow">
                        Fitness & Wellness
                    </a>
                    <button
                        type="button"
                        id="mobileFitnessAccordionToggle"
                        class="p-2 focus:outline-none flex items-center justify-center cursor-pointer"
                        aria-expanded="false"
                        aria-controls="mobileFitnessAccordion"
                        aria-label="Toggle Fitness Topics"
                    >
                        <svg id="mobileFitnessAccordionIcon" class="w-5 h-5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
                <div id="mobileFitnessAccordion" class="hidden flex-col gap-2 pt-3 pl-4" style="border-left: 2px solid var(--primary-color);">
                    <a href="{{ route('fitness.index') }}" class="theme-navbar-link font-semibold text-sm">
                        Explore All Fitness &rarr;
                    </a>
                    <a href="{{ route('fitness.exercise') }}" class="theme-navbar-link text-sm">Exercise Guides</a>
                    <a href="{{ route('fitness.cardio') }}" class="theme-navbar-link text-sm">Cardio & Conditioning</a>
                    <a href="{{ route('fitness.strength-training') }}" class="theme-navbar-link text-sm">Strength Training</a>
                    <a href="{{ route('fitness.yoga') }}" class="theme-navbar-link text-sm">Yoga & Mobility</a>
                    <a href="{{ route('fitness.holistic-fitness') }}" class="theme-navbar-link text-sm">Holistic Fitness</a>
                    <a href="{{ route('fitness.wellness') }}" class="theme-navbar-link font-semibold text-sm">Wellness Hub (New)</a>
                    <a href="{{ route('blog.category', 'nutrition') }}" class="theme-navbar-link text-sm">Nutrition Science</a>
                    <a href="{{ route('blog.category', 'indian-diet') }}" class="theme-navbar-link text-sm">Indian Diet & Fuel</a>
                    <a href="{{ route('blog.category', 'healthy-recipes') }}" class="theme-navbar-link text-sm">Healthy Recipes</a>
                    <a href="{{ route('blog.category', 'weight-loss') }}" class="theme-navbar-link text-sm">Weight Loss</a>
                    <a href="{{ route('fitness.exercise-library') }}" class="theme-navbar-link text-sm">Exercise Library (25+)</a>
                </div>
            </div>

            {{-- UNIFIED COACHING & PROGRAMS HUB --}}
            <a href="{{ route('coaching-programs.index') }}" class="theme-navbar-link font-semibold text-lg">{{ $setting?->coaching_programs_label ?: 'Coaching & Programs' }}</a>
            <a href="/transformations" class="theme-navbar-link font-semibold text-lg">{{ $setting?->transformation_label ?: 'Transformations' }}</a>
            <a href="/blog" class="theme-navbar-link font-semibold text-lg">{{ $setting?->blog_label ?: 'Blog' }}</a>
            <a href="/about" class="theme-navbar-link font-semibold text-lg">{{ $setting?->about_label ?: 'About' }}</a>
            <a href="{{ route('products.index') }}"
               class="theme-navbar-link font-semibold text-lg">
                {{ $setting?->product_label ?: 'Products' }}
            </a>
            <a href="/contact" class="theme-navbar-link font-semibold text-lg">{{ $setting?->contact_label ?: 'Contact' }}</a>

            @auth
                @if(auth()->user()->account_type === 'admin')
                    <x-theme.button href="/admin">{{ $setting?->admin_panel_label ?: 'Dashboard' }}</x-theme.button>
                @elseif(auth()->user()->activeMembership)
                    <x-theme.button href="/member/dashboard">{{ $setting?->my_plan_label ?: 'My Plan' }}</x-theme.button>
                @else
                    <x-theme.button :href="route('account.orders')">{{ $setting?->my_orders_label ?: 'My Orders' }}</x-theme.button>
                @endif
            @else
                <div class="flex flex-col gap-3 mt-4">
                    <x-theme.button href="/register">
                        {{ $setting?->register_label ?: $setting?->cta_button_text ?: 'Join Now' }}
                    </x-theme.button>
                    <a href="/login" class="theme-navbar-link text-center font-semibold text-sm hover:underline py-1">
                        {{ $setting?->login_label ?: 'Sign In' }}
                    </a>
                </div>
            @endauth
        </x-theme.page-container>
    </div>
</nav>

<div style="height: var(--navbar-height);"></div>

<script>
    // Mobile Drawer Logic
    const mobileButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileButton && mobileMenu) {
        mobileButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            mobileButton.setAttribute('aria-expanded', String(! mobileMenu.classList.contains('hidden')));
        });
    }

    // Navbar Scroll Shadow
    window.addEventListener('scroll', function () {
        const navbar = document.getElementById('navbar');
        navbar?.classList.toggle('theme-navbar-scrolled', window.scrollY > 20);
    });

    // Desktop Mega Menu Logic
    const megaMenuTrigger = document.getElementById('fitnessMegaMenuTrigger');
    const megaMenuTriggerContainer = document.getElementById('fitnessMegaMenuTriggerContainer');
    const megaMenu = document.getElementById('fitnessMegaMenu');
    const megaChevron = document.getElementById('fitnessMegaMenuChevron');
    let closeTimeout = null;

    function openMegaMenu() {
        if (closeTimeout) {
            clearTimeout(closeTimeout);
            closeTimeout = null;
        }
        megaMenu?.classList.add('is-active');
        megaMenuTrigger?.setAttribute('aria-expanded', 'true');
        if (megaChevron) megaChevron.style.transform = 'rotate(180deg)';
    }

    function closeMegaMenu() {
        closeTimeout = setTimeout(() => {
            megaMenu?.classList.remove('is-active');
            megaMenuTrigger?.setAttribute('aria-expanded', 'false');
            if (megaChevron) megaChevron.style.transform = 'rotate(0deg)';
        }, 180);
    }

    if (megaMenuTriggerContainer && megaMenu) {
        megaMenuTriggerContainer.addEventListener('mouseenter', openMegaMenu);
        megaMenuTriggerContainer.addEventListener('mouseleave', closeMegaMenu);
        megaMenu.addEventListener('mouseenter', openMegaMenu);
        megaMenu.addEventListener('mouseleave', closeMegaMenu);

        // Keyboard Support
        megaMenuTrigger?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                e.preventDefault();
                openMegaMenu();
                const firstLink = megaMenu.querySelector('a');
                firstLink?.focus();
            } else if (e.key === 'Escape') {
                megaMenu.classList.remove('is-active');
                megaMenuTrigger.setAttribute('aria-expanded', 'false');
                if (megaChevron) megaChevron.style.transform = 'rotate(0deg)';
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && megaMenu.classList.contains('is-active')) {
                megaMenu.classList.remove('is-active');
                megaMenuTrigger?.setAttribute('aria-expanded', 'false');
                if (megaChevron) megaChevron.style.transform = 'rotate(0deg)';
                megaMenuTrigger?.focus();
            }
        });

        document.addEventListener('focusin', (e) => {
            if (!megaMenuTriggerContainer.contains(e.target) && !megaMenu.contains(e.target)) {
                megaMenu.classList.remove('is-active');
                megaMenuTrigger?.setAttribute('aria-expanded', 'false');
                if (megaChevron) megaChevron.style.transform = 'rotate(0deg)';
            }
        });
    }

    // Mobile Accordion
    const mobileFitnessToggle = document.getElementById('mobileFitnessAccordionToggle');
    const mobileFitnessAccordion = document.getElementById('mobileFitnessAccordion');
    const mobileFitnessIcon = document.getElementById('mobileFitnessAccordionIcon');

    if (mobileFitnessToggle && mobileFitnessAccordion) {
        mobileFitnessToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isHidden = mobileFitnessAccordion.classList.contains('hidden');
            mobileFitnessAccordion.classList.toggle('hidden');
            mobileFitnessAccordion.classList.toggle('flex');
            mobileFitnessToggle.setAttribute('aria-expanded', String(isHidden));
            if (mobileFitnessIcon) {
                mobileFitnessIcon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        });
    }
</script>
