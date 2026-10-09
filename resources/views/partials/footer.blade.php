<footer class="theme-footer">
    <x-theme.page-container>
        <div class="theme-footer-divider grid md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-14 pb-16 border-b">
            <div>
                <div class="flex items-center theme-content-gap mb-6">
                    @if(! empty($setting?->dark_logo) || ! empty($setting?->logo))
                        <img
                            src="{{ asset('storage/'.($setting->dark_logo ?: $setting->logo)) }}"
                            alt="{{ $setting->site_name }}"
                            class="w-14 h-14 object-contain"
                        >
                    @endif

                    <div>
                        <h2 class="theme-footer-heading theme-text-primary text-2xl">
                            {{ $setting?->site_name ?: config('app.name') }}
                        </h2>
                        <p class="theme-footer-muted text-xs uppercase tracking-[3px]">
                            {{ $setting?->site_tagline }}
                        </p>
                    </div>
                </div>

                <p class="theme-footer-muted mb-6">
                    {{ $setting->footer_text ?? 'Transform your body and mindset with elite coaching and premium fitness programs.' }}
                </p>

                <div class="flex items-center gap-3">
                    @if(filled($setting?->instagram))
                        <a
                            href="{{ $setting->instagram }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Follow DK Singh on Instagram"
                            class="theme-footer-social"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    @if(filled($setting?->youtube))
                        <a
                            href="{{ $setting->youtube }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Subscribe to DK Singh on YouTube"
                            class="theme-footer-social"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    @if(filled($setting?->facebook))
                        <a
                            href="{{ $setting->facebook }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Follow DK Singh on Facebook"
                            class="theme-footer-social"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    @if(filled($setting?->linkedin))
                        <a
                            href="{{ $setting->linkedin }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Connect with DK Singh on LinkedIn"
                            class="theme-footer-social"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="theme-footer-heading text-xl mb-6">{{ $setting->footer_links_heading ?? 'Explore' }}</h3>
                <nav class="theme-stack-md" aria-label="Footer navigation">
                    @foreach([
                        ['url' => '/', 'label' => $setting->home_label ?? 'Home'],
                        ['url' => '/fitness', 'label' => 'Fitness & Wellness'],
                        ['url' => '/programs', 'label' => $setting->program_label ?? 'Programs'],
                        ['url' => '/transformations', 'label' => $setting->transformation_label ?? 'Transformations'],
                        ['url' => '/blog', 'label' => $setting->blog_label ?? 'Blog'],
                        ['url' => '/fitness-hub', 'label' => $setting?->fitness_hub_label ?: 'Workout Hub'],
                        ['url' => '/about', 'label' => $setting->about_label ?? 'About'],
                        ['url' => '/contact', 'label' => $setting->contact_label ?? 'Contact'],
                    ] as $link)
                        <a href="{{ $link['url'] }}" class="theme-footer-link block">{{ $link['label'] }}</a>
                    @endforeach
                </nav>
            </div>

            <div>
                <h3 class="theme-footer-heading text-xl mb-6">Coaching & Training</h3>
                <nav class="theme-stack-md" aria-label="Services navigation">
                    @foreach(\App\Models\Service::where('status', 1)->take(5)->get() as $service)
                        <a href="{{ route('services.show', $service->slug) }}" class="theme-footer-link block">
                            {{ $service->title }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <div>
                <h3 class="theme-footer-heading text-xl mb-6">{{ $setting->contact_title ?? 'Get In Touch' }}</h3>
                <div class="theme-footer-muted theme-stack-md">
                    @if(filled($setting?->phone))
                        <p>
                            <a href="tel:{{ $setting->phone }}" class="theme-footer-link inline-flex items-center gap-2">
                                <span>☎</span>
                                <span>+91 {{ $setting->phone }}</span>
                            </a>
                        </p>
                    @endif
                    @if(filled($setting?->email))
                        <p>
                            <a href="mailto:{{ $setting->email }}" class="theme-footer-link inline-flex items-center gap-2">
                                <span>✉</span>
                                <span>{{ $setting->email }}</span>
                            </a>
                        </p>
                    @endif
                    @if(filled($setting?->address))
                        <p class="inline-flex items-start gap-2">
                            <span>⌖</span>
                            <span>{{ $setting->address }}</span>
                        </p>
                    @endif
                </div>

                <x-theme.button :href="$setting->cta_button_link ?? '/plans'" class="mt-8">
                    {{ $setting->cta_button_text ?? 'Join Now' }}
                </x-theme.button>
            </div>
        </div>

        <div class="theme-footer-muted pt-8 flex flex-col md:flex-row items-center justify-between theme-content-gap text-sm">
            <p class="text-center md:text-left">
                © {{ date('Y') }} {{ $setting?->copyright_text ?: $setting?->legal_business_name ?: $setting?->site_name ?: config('app.name') }}.
            </p>
            <p>{{ $setting?->site_tagline }}</p>
        </div>
    </x-theme.page-container>
</footer>
