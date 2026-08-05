<nav id="navbar"
    class="fixed top-0 left-0 w-full z-50 transition-all duration-500 backdrop-blur-xl border-b"
style="
background: color-mix(in srgb, var(--accent-color) 85%, transparent);
border-color: rgba(0,0,0,.08);
">

    <div class="max-w-7xl mx-auto px-6">

        <div class="flex items-center justify-between h-24">

            <!-- LOGO -->

            <a href="/" class="flex items-center gap-4">

                @if(!empty($setting?->logo))

                    <img
                        src="{{ asset('storage/' . $setting->logo) }}"
                        alt="{{ $setting->site_name }}"
                        class="h-14 w-auto"
                    >

                @else

                    <div>

                        <h2 class="text-2xl font-black leading-tight"
style="color: var(--secondary-color);">
                           {{ $setting->site_name }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            {{ $setting->site_tagline }}
                        </p>

                    </div>

                @endif

            </a>

            <!-- DESKTOP MENU -->
@include('partials.navbar.desktop-menu')

            <!-- RIGHT -->

@include('partials.navbar.right-menu')
            

            <!-- MOBILE BUTTON -->

            <button id="mobileMenuButton"
                class="lg:hidden flex items-center justify-center w-12 h-12 rounded-2xl text-2xl font-bold"
style="
background: var(--primary-color);
color: var(--secondary-color);
">

                ☰

            </button>

        </div>

    </div>

    <!-- MOBILE MENU -->

    <div id="mobileMenu"
         class="hidden lg:hidden border-t shadow-lg"
style="
background: var(--accent-color);
border-color: rgba(0,0,0,.08);
">

        <div class="px-6 py-8 space-y-5 flex flex-col">

            <a href="/" class="font-semibold text-lg">
                Home
            </a>

            <a href="/programs" class="font-semibold text-lg">
    {{ $setting->program_label ?? 'Programs' }}
</a>

<a href="/services" class="font-semibold text-lg">
    {{ $setting->service_label ?? 'Services' }}
</a>

<a href="/transformations" class="font-semibold text-lg">
    {{ $setting->transformation_label ?? 'Transformations' }}
</a>

<a href="/blog" class="font-semibold text-lg">
    {{ $setting->blog_label ?? 'Blog' }}
</a>

         <a href="/fitness-hub" class="font-semibold text-lg">
    Fitness Hub
</a>   

            <a href="/about" class="font-semibold text-lg">
                About
            </a>

            <a href="/plans" class="font-semibold text-lg">
                Plans
            </a>

<a href="{{ route('products.index') }}"
   class="font-semibold text-lg">
    {{ $setting->product_label ?? 'Products' }}
</a>
            <a href="/contact" class="font-semibold text-lg">
                Contact
            </a>

            @auth

@if(auth()->user()->account_type === 'admin')

<a href="/admin"
   class="btn-primary text-center mt-4">
    Admin Panel
</a>

@elseif(auth()->user()->activeMembership)

<a href="/member/dashboard"
   class="btn-primary text-center mt-4">
    My Plan
</a>

@else

<a href="{{ route('account.orders') }}"
   class="btn-primary text-center mt-4">
    My Orders
</a>

@endif

            @else

                <a href="/register"
                   class="btn-primary text-center mt-4">

                    Join Now

                </a>

            @endauth

        </div>

    </div>

</nav>

<!-- SPACER -->

<div class="h-24"></div>

<!-- MOBILE MENU SCRIPT -->

<script>

const mobileButton = document.getElementById('mobileMenuButton');
const mobileMenu = document.getElementById('mobileMenu');

if (mobileButton && mobileMenu) {

    mobileButton.addEventListener('click', () => {

        mobileMenu.classList.toggle('hidden');

    });

}

window.addEventListener('scroll', function () {

    const navbar = document.getElementById('navbar');

    if(window.scrollY > 20) {

        navbar.classList.add('shadow-xl');

    } else {

        navbar.classList.remove('shadow-xl');

    }

});

</script>