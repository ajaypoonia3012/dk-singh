@if($theme?->show_hero)
    @php
        $heroImageUrl = match (true) {
            filled($hero?->backgroundMedia?->path) => $hero->backgroundMedia->url,
            filled($setting?->hero_image) => asset('storage/'.ltrim($setting->hero_image, '/')),
            filled($hero?->background) => asset('storage/'.ltrim($hero->background, '/')),
            default => asset('images/dk-hero.jpeg'),
        };
    @endphp

    <x-theme.section class="relative theme-surface-muted overflow-hidden">
        <div class="grid lg:grid-cols-2 theme-grid-gap items-center">
            <div class="theme-stack-lg">
                <p class="theme-eyebrow">
                    {{ $setting->site_name }}
                </p>

                <x-theme.section-heading level="1" class="text-5xl md:text-6xl lg:text-7xl">
                    {!! nl2br(e($hero?->heading ?? $setting->hero_title ?? 'Transform Your Body, Transform Your Life')) !!}
                </x-theme.section-heading>

                <x-theme.section-subtitle class="text-xl max-w-xl">
                    {{ $hero?->subheading ?? $setting->hero_subtitle ?? 'Expert fitness coaching and personalized nutrition plans.' }}
                </x-theme.section-subtitle>

                <div class="flex flex-wrap theme-content-gap">
                    <x-theme.button :href="$hero?->button_link ?? $setting->cta_button_link ?? '/plans'">
                        {{ $hero?->button_text ?? $setting->cta_button_text ?? 'Start Your Journey' }}
                    </x-theme.button>

                    <x-theme.button href="/transformations" variant="outline">
                        {{ $setting->view_transformations_text }}
                    </x-theme.button>
                </div>

                <div class="grid grid-cols-3 theme-grid-gap">
                    <div>
                        <p class="theme-section-heading theme-text-primary text-3xl md:text-5xl">
                            {{ $setting->instagram_followers ?? '3M+' }}
                        </p>
                        <p class="theme-text-neutral mt-2">
                            {{ $hero?->followers_label ?? 'Followers' }}
                        </p>
                    </div>

                    <div>
                        <p class="theme-section-heading theme-text-primary text-3xl md:text-5xl">
                            {{ $setting->years_experience ?? '15+' }}
                        </p>
                        <p class="theme-text-neutral mt-2">
                            {{ $hero?->years_label ?? 'Years' }}
                        </p>
                    </div>

                    <div>
                        <p class="theme-section-heading theme-text-primary text-3xl md:text-5xl">
                            {{ $setting->transformations ?? '15000+' }}
                        </p>
                        <p class="theme-text-neutral mt-2">
                            {{ $hero?->transformations_label ?? 'Transformations' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="relative flex justify-center">
                <div class="absolute inset-0 theme-text-primary blur-3xl opacity-20 rounded-full" style="background: currentColor;"></div>

                <div class="relative z-10 w-full max-w-[550px]">
                    <img
                        src="{{ $heroImageUrl }}"
                        alt="{{ $setting->site_name }}"
                        loading="eager"
                        decoding="async"
                        class="theme-media w-full h-[700px] object-cover"
                    >
                    <div
                        class="theme-hero-overlay theme-media absolute inset-0 pointer-events-none"
                        style="opacity: calc(var(--hero-overlay-opacity) / 100);"
                        aria-hidden="true"
                    ></div>
                </div>

                <x-theme.card class="absolute bottom-10 left-0 theme-card-padding z-30">
                    <x-theme.section-heading level="3" class="text-3xl mb-2">
                        Transformations
                    </x-theme.section-heading>
                    <x-theme.section-subtitle class="max-w-xs">
                        {{ $setting->hero_card_title }}
                    </x-theme.section-subtitle>
                </x-theme.card>
            </div>
        </div>
    </x-theme.section>
@endif
