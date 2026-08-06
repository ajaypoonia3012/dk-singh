<div class="hidden lg:flex items-center gap-8 text-sm font-semibold">

    <a href="/" class="hover:text-[var(--primary-color)] transition">
        {{ $setting?->home_label ?: 'Home' }}
    </a>

    <a href="/programs"
       class="hover:text-[var(--primary-color)] transition">
        {{ $setting->program_label ?? 'Programs' }}
    </a>

    <a href="/services"
       class="hover:text-[var(--primary-color)] transition">
        {{ $setting->service_label ?? 'Services' }}
    </a>

    <a href="/transformations"
       class="hover:text-[var(--primary-color)] transition">
        {{ $setting->transformation_label ?? 'Transformations' }}
    </a>

    <a href="/blog"
       class="hover:text-[var(--primary-color)] transition">
        {{ $setting->blog_label ?? 'Blog' }}
    </a>

    <a href="/fitness-hub"
       class="hover:text-[var(--primary-color)] transition">
        {{ $setting?->fitness_hub_label ?: 'Fitness Hub' }}
    </a>

    <a href="/about"
       class="hover:text-[var(--primary-color)] transition">
        {{ $setting?->about_label ?: 'About' }}
    </a>

    <a href="/plans"
       class="hover:text-[var(--primary-color)] transition">
        {{ $setting?->plan_label ?: 'Plans' }}
    </a>

    <a href="{{ route('products.index') }}"
       class="hover:text-[var(--primary-color)] transition">
        {{ $setting->product_label ?? 'Products' }}
    </a>

    <a href="/contact"
       class="hover:text-[var(--primary-color)] transition">
        {{ $setting?->contact_label ?: 'Contact' }}
    </a>

</div>
