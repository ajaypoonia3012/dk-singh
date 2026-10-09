@extends('layouts.app')

@section('content')
    @include('home.sections.hero')

    @if($theme?->show_programs)
        <x-theme.section class="theme-surface-default" data-theme-section="programs">
            <header class="theme-section-header">
                <p class="theme-eyebrow mb-4">{{ $setting->program_label }}</p>
                <x-theme.section-heading class="text-4xl md:text-5xl mb-5">
                    {{ $setting->programs_heading ?? 'Featured Fitness Programs' }}
                </x-theme.section-heading>
                <x-theme.section-subtitle class="text-xl max-w-3xl mx-auto">
                    {{ $setting->programs_description }}
                </x-theme.section-subtitle>
            </header>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">
                @foreach($programs as $program)
                    <x-theme.card data-aos="zoom-in" class="group overflow-hidden hover:-translate-y-2 transition duration-500">
                        <div class="overflow-hidden">
                            <img
                                src="{{ $program->image ? asset('storage/'.$program->image) : asset('images/placeholder.jpg') }}"
                                alt="{{ $program->title }}"
                                loading="lazy"
                                decoding="async"
                                class="w-full h-72 object-cover group-hover:scale-110 transition duration-700"
                            >
                        </div>

                        <div class="theme-card-padding theme-stack-md">
                            <div class="flex items-center justify-between theme-content-gap">
                                <x-theme.badge>{{ $program->category }}</x-theme.badge>
                                <span class="theme-text-neutral font-semibold">{{ $program->duration }}</span>
                            </div>

                            <x-theme.section-heading level="3" class="text-3xl">
                                {{ $program->title }}
                            </x-theme.section-heading>

                            <x-theme.section-subtitle>
                                {{ Str::limit($program->description, 100) }}
                            </x-theme.section-subtitle>

                            <div class="flex items-center justify-between theme-content-gap">
                                <span class="theme-section-heading theme-text-primary text-2xl">₹{{ $program->price }}</span>
                                <x-theme.button :href="route('programs.show', $program->slug)" variant="outline" class="!shadow-none">
                                    {{ $setting->about_cta_text ?? 'Learn More' }} →
                                </x-theme.button>
                            </div>
                        </div>
                    </x-theme.card>
                @endforeach
            </div>

            @if($products->count())
                <div class="mt-20 pt-16 border-t theme-divider" data-theme-section="products">
                    <header class="theme-section-header">
                        <p class="theme-eyebrow mb-4">{{ $setting?->supplements_label ?: 'Supplements & Nutrition' }}</p>
                        <x-theme.section-heading class="text-4xl md:text-5xl mb-5">
                            {{ $setting?->supplements_heading ?: 'Health & Fitness Products' }}
                        </x-theme.section-heading>
                    </header>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">
                        @foreach($products as $product)
                            <x-theme.card class="overflow-hidden hover:-translate-y-2 transition duration-500">
                                @if($product->image)
                                    <img
                                        src="{{ asset('storage/'.$product->image) }}"
                                        alt="{{ $product->name }}"
                                        loading="lazy"
                                        decoding="async"
                                        class="w-full h-72 object-cover"
                                    >
                                @endif

                                <div class="theme-card-padding theme-stack-md">
                                    <x-theme.section-heading level="3" class="text-2xl">
                                        {{ $product->name }}
                                    </x-theme.section-heading>
                                    <p class="theme-section-heading theme-text-primary text-3xl">
                                        ₹{{ number_format($product->price) }}
                                    </p>
                                    <x-theme.button :href="route('products.show', $product->slug)">
                                        View Product
                                    </x-theme.button>
                                </div>
                            </x-theme.card>
                        @endforeach
                    </div>
                </div>
            @endif
        </x-theme.section>
    @endif

    <x-theme.section class="theme-surface-default" data-theme-section="coaching">
        <header class="theme-section-header">
            <p class="theme-eyebrow mb-4">{{ $setting->service_label }}</p>
            <x-theme.section-heading class="text-4xl md:text-5xl">
                {{ $setting->services_heading ?? 'Fitness Solutions' }}
            </x-theme.section-heading>
            <x-theme.section-subtitle class="text-xl max-w-3xl mx-auto mt-6">
                {{ $setting->services_description }}
            </x-theme.section-subtitle>
        </header>

        <div class="grid md:grid-cols-3 theme-grid-gap">
            @foreach($services as $service)
                <x-theme.card class="theme-card-padding">
                    @if($service->image)
                        <img
                            src="{{ asset('storage/'.$service->image) }}"
                            alt="{{ $service->title }}"
                            loading="lazy"
                            decoding="async"
                            class="theme-media w-full h-56 object-cover mb-6"
                        >
                    @endif

                    <div class="theme-stack-md">
                        <p class="theme-eyebrow">{{ $service->category ?? 'Fitness' }}</p>
                        <x-theme.section-heading level="3" class="text-3xl">{{ $service->title }}</x-theme.section-heading>
                        <x-theme.section-subtitle>
                            {{ \Illuminate\Support\Str::limit($service->description, 120) }}
                        </x-theme.section-subtitle>
                        <div class="flex items-center justify-between theme-content-gap">
                            <span class="theme-section-heading text-2xl">₹{{ number_format($service->price, 0) }}</span>
                            <x-theme.button :href="route('services.show', $service->slug)" variant="outline" class="!shadow-none">
                                Learn More →
                            </x-theme.button>
                        </div>
                    </div>
                </x-theme.card>
            @endforeach
        </div>
    </x-theme.section>

    <x-theme.section class="theme-surface-muted" data-theme-section="bmi">
        <header class="theme-section-header">
            <p class="theme-eyebrow mb-4">{{ $setting->bmi_label }}</p>
            <x-theme.section-heading class="text-4xl md:text-5xl mb-5">BMI Calculator</x-theme.section-heading>
            <x-theme.section-subtitle class="text-xl max-w-3xl mx-auto">
                {{ $setting->bmi_heading }} {{ $setting->bmi_description }}
            </x-theme.section-subtitle>
        </header>

        <x-theme.card data-aos="fade-up" class="theme-surface-strong theme-card-padding-lg">
            <div class="grid lg:grid-cols-2 theme-grid-gap items-center">
                <div class="theme-stack-lg">
                    <div>
                        <x-theme.label for="height" value="Height (cm)" class="theme-text-on-strong block mb-3 text-lg" />
                        <x-theme.input type="number" id="height" placeholder="Enter your height" class="text-lg" />
                    </div>
                    <div>
                        <x-theme.label for="weight" value="Weight (kg)" class="theme-text-on-strong block mb-3 text-lg" />
                        <x-theme.input type="number" id="weight" placeholder="Enter your weight" class="text-lg" />
                    </div>
                    <x-theme.button type="button" onclick="calculateBMI()">Calculate BMI</x-theme.button>
                </div>

                <x-theme.card class="theme-card-padding-lg">
                    <p class="theme-eyebrow mb-3">Your BMI</p>
                    <p id="bmi-result" class="theme-section-heading theme-text-primary text-6xl mb-6">--</p>
                    <x-theme.section-heading id="bmi-status" level="3" class="text-3xl mb-6">
                        Enter your details
                    </x-theme.section-heading>
                    <div class="theme-divider border-t pt-6">
                        <p class="theme-eyebrow mb-3">Recommended Goal</p>
                        <x-theme.section-subtitle id="bmi-goal" class="text-xl">
                            Fill your details to see your recommended fitness path.
                        </x-theme.section-subtitle>
                    </div>
                </x-theme.card>
            </div>
        </x-theme.card>
    </x-theme.section>

    <script>
        function calculateBMI() {
            let height = document.getElementById('height').value;
            let weight = document.getElementById('weight').value;

            if (height === '' || weight === '') {
                alert('Please enter height and weight');
                return;
            }

            height /= 100;

            const bmi = (weight / (height * height)).toFixed(1);
            const result = document.getElementById('bmi-result');
            const statusElement = document.getElementById('bmi-status');
            let status = '';
            let goal = '';
            let statusClass = 'theme-text-info';

            if (bmi < 18.5) {
                status = 'Underweight';
                goal = 'Muscle gain and nutrition optimization program recommended.';
                statusClass = 'theme-text-info';
            } else if (bmi < 25) {
                status = 'Healthy';
                goal = 'Maintain your fitness with performance training programs.';
                statusClass = 'theme-text-success';
            } else if (bmi < 30) {
                status = 'Overweight';
                goal = 'Fat loss transformation program recommended.';
                statusClass = 'theme-text-warning';
            } else {
                status = 'Obese';
                goal = 'Structured weight loss coaching strongly recommended.';
                statusClass = 'theme-text-danger';
            }

            result.innerText = bmi;
            statusElement.innerText = status;
            statusElement.classList.remove('theme-text-info', 'theme-text-success', 'theme-text-warning', 'theme-text-danger');
            statusElement.classList.add(statusClass);
            document.getElementById('bmi-goal').innerText = goal;
        }
    </script>

    <x-theme.section class="theme-surface-default" data-theme-section="about">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div data-aos="fade-right">
                <img
                    src="{{ ! empty($setting?->about_image) ? asset('storage/'.$setting->about_image) : 'https://images.unsplash.com/photo-1534367610401-9f5ed68180aa?q=80&w=1200&auto=format&fit=crop' }}"
                    alt="{{ $setting->about_title ?? $setting->site_name }}"
                    loading="lazy"
                    decoding="async"
                    class="theme-media w-full h-[450px] md:h-[750px] object-cover"
                >
            </div>

            <div data-aos="fade-left" class="theme-stack-lg">
                <p class="theme-eyebrow">{{ $setting->about_title }}</p>
                <x-theme.section-heading class="text-4xl md:text-6xl">
                    {{ $setting->about_title ?? 'Fitness Meets Transformation' }}
                </x-theme.section-heading>
                <x-theme.section-subtitle class="text-lg">{{ $setting->about_description ?? '' }}</x-theme.section-subtitle>
                <x-theme.section-subtitle class="text-lg">{{ $setting->about_description_2 ?? '' }}</x-theme.section-subtitle>

                <div class="grid grid-cols-2 theme-content-gap">
                    @foreach($homepageCards as $card)
                        <x-theme.card class="theme-card-padding hover:-translate-y-2 transition duration-300">
                            @if($card->svg_icon)
                                <div class="w-12 h-12 theme-radius theme-surface-strong flex items-center justify-center mb-3" aria-hidden="true">
                                    {!! $card->svg_icon !!}
                                </div>
                            @elseif($card->icon)
                                <div class="text-3xl mb-3" aria-hidden="true">{{ $card->icon }}</div>
                            @endif
                            <x-theme.section-heading level="3" class="text-xl">{{ $card->title }}</x-theme.section-heading>
                            <x-theme.section-subtitle class="mt-2">{{ $card->subtitle }}</x-theme.section-subtitle>
                        </x-theme.card>
                    @endforeach
                </div>
            </div>
        </div>
    </x-theme.section>

    <x-theme.section class="theme-surface-muted" data-theme-section="transformations">
        <header class="theme-section-header">
            <p class="theme-eyebrow mb-4">{{ $setting?->transformation_label ?: 'Transformations' }}</p>
            <x-theme.section-heading class="text-4xl md:text-5xl mb-5">{{ $setting->transformations_heading }}</x-theme.section-heading>
            <x-theme.section-subtitle class="text-xl max-w-3xl mx-auto">
                {{ $setting->transformations_page_description }}
            </x-theme.section-subtitle>
        </header>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">
            @foreach($transformations as $transformation)
                @php
                    $imgPath = $transformation->image ?: ($transformation->after_image ?: $transformation->before_image);
                    $hasImg = filled($imgPath) && file_exists(public_path('storage/' . $imgPath));
                @endphp
                <x-theme.card data-aos="zoom-in" class="group overflow-hidden hover:-translate-y-1.5 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="relative aspect-[4/3] w-full overflow-hidden theme-surface-strong rounded-t-[inherit]">
                            @if($hasImg)
                                <img
                                    src="{{ asset('storage/' . $imgPath) }}"
                                    alt="{{ $transformation->title ?: ($transformation->name ?: 'DK Singh Fitness Client Transformation') }}"
                                    loading="lazy"
                                    decoding="async"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                >
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center theme-surface-strong theme-text-neutral">
                                    <span class="text-3xl mb-2">⚡</span>
                                    <span class="font-bold text-sm tracking-wider uppercase theme-text-primary">Verified Result</span>
                                    <span class="text-xs theme-text-neutral mt-1">DK Singh Coaching Protocol</span>
                                </div>
                            @endif

                            @if(filled($transformation->goal))
                                <x-theme.badge class="absolute top-4 left-4 text-xs font-bold">{{ $transformation->goal }}</x-theme.badge>
                            @endif
                        </div>

                        <div class="theme-card-padding">
                            <div class="theme-text-primary text-base tracking-[2px] mb-2" aria-label="Five-star transformation">★★★★★</div>
                            <x-theme.section-heading level="3" class="text-xl font-bold mb-2">{{ $transformation->name }}</x-theme.section-heading>
                            <p class="theme-section-subtitle text-sm leading-relaxed line-clamp-3">
                                “{{ Str::limit($transformation->clean_story, 140) }}”
                            </p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-2">
                        <div class="theme-divider flex items-center justify-between border-t pt-4 theme-content-gap">
                            <span class="theme-text-neutral text-xs font-bold uppercase tracking-wider">{{ $transformation->duration ?: '12 Weeks' }}</span>
                            <span class="theme-text-primary text-xs font-bold uppercase tracking-wider">{{ $setting->verified_client_label ?? 'Verified Client' }}</span>
                        </div>
                    </div>
                </x-theme.card>
            @endforeach
        </div>
    </x-theme.section>

    <x-theme.section class="theme-surface-default" data-theme-section="testimonials">
        <header class="theme-section-header">
            <p class="theme-eyebrow mb-4">{{ $setting->testimonials_title }}</p>
            <x-theme.section-heading class="text-4xl md:text-5xl mb-5">{{ $setting->testimonials_heading }}</x-theme.section-heading>
            <x-theme.section-subtitle class="text-xl max-w-3xl mx-auto">
                {{ $setting->testimonials_description }}, confidence, and lifestyle with {{ $setting->site_name }}.
            </x-theme.section-subtitle>
        </header>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">
            @foreach($testimonials as $testimonial)
                @php
                    $hasAvatar = filled($testimonial->image) && file_exists(public_path('storage/' . $testimonial->image));
                    $initials = '';
                    if (!empty($testimonial->name)) {
                        $words = preg_split('/\s+/', trim($testimonial->name));
                        $initials = strtoupper(substr($words[0] ?? '', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    }
                    $initials = $initials ?: 'DK';
                @endphp
                <x-theme.card data-aos="zoom-in" class="theme-card-padding hover:-translate-y-1.5 transition duration-300 flex flex-col justify-between">
                    <div>
                        <div class="theme-text-primary text-base mb-3 tracking-[2px]" aria-label="Five-star testimonial">★★★★★</div>
                        <p class="theme-section-subtitle text-sm md:text-base leading-relaxed mb-6 font-normal">
                            “{{ Str::limit($testimonial->clean_review, 160) }}”
                        </p>
                    </div>

                    <div class="flex items-center gap-3.5 pt-4 border-t theme-divider mt-auto">
                        @if($hasAvatar)
                            <img
                                src="{{ asset('storage/' . $testimonial->image) }}"
                                alt="{{ $testimonial->name ? $testimonial->name . ' - Client Testimonial' : 'DK Singh Fitness Client' }}"
                                loading="lazy"
                                decoding="async"
                                class="w-12 h-12 rounded-full object-cover border-2 shrink-0"
                                style="border-color: var(--primary-color);"
                            >
                        @else
                            <div
                                class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm tracking-wide shrink-0 border-2 theme-surface-strong theme-text-primary"
                                style="border-color: color-mix(in srgb, var(--primary-color) 40%, transparent);"
                                aria-hidden="true"
                            >
                                {{ $initials }}
                            </div>
                        @endif

                        <div class="min-w-0">
                            <x-theme.section-heading level="3" class="text-base font-bold truncate">{{ $testimonial->name }}</x-theme.section-heading>
                            <p class="theme-eyebrow text-xs uppercase tracking-wider truncate">{{ $testimonial->designation ?: 'Verified Member' }}</p>
                        </div>
                    </div>
                </x-theme.card>
            @endforeach
        </div>
    </x-theme.section>

    <x-theme.section class="theme-surface-muted" data-theme-section="contact">
        <header class="theme-section-header">
            <x-theme.section-heading class="text-4xl md:text-5xl mb-4">
                {{ $setting->contact_title ?? 'Get In Touch' }}
            </x-theme.section-heading>
            <x-theme.section-subtitle class="text-lg">
                {{ $setting->contact_description ?? 'Ready to start your fitness journey? Contact us today.' }}
            </x-theme.section-subtitle>
        </header>

        <div class="grid lg:grid-cols-2 theme-grid-gap items-center">
            <x-theme.card data-aos="fade-right" class="overflow-hidden">
                @if($setting->map_embed_url)
                    <iframe
                        src="{{ $setting->map_embed_url }}"
                        width="100%"
                        height="300"
                        style="border: 0;"
                        allowfullscreen
                        loading="lazy"
                        title="{{ $setting->business_display_name }} location"
                    ></iframe>
                @else
                    <div class="h-[220px] w-full theme-surface-strong flex flex-col items-center justify-center p-6 text-center border-b theme-border">
                        <div class="w-12 h-12 rounded-full theme-surface-muted flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 theme-text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-lg mb-1">{{ $setting->business_display_name }}</h4>
                        <p class="theme-text-muted text-sm max-w-sm">{{ $setting->address ?? 'Jaipur, Rajasthan, India' }}</p>
                    </div>
                @endif
                <div class="theme-card-padding theme-stack-md">
                    <x-theme.section-heading level="3" class="text-3xl">{{ $setting->business_display_name }}</x-theme.section-heading>
                    <x-theme.section-subtitle>{{ $setting->contact_map_text }}</x-theme.section-subtitle>
                    <a href="{{ $setting->map_link ?: 'https://maps.google.com/?q=' . urlencode($setting->address ?? 'Jaipur') }}" target="_blank" rel="noopener noreferrer" class="theme-link">
                        Open in Google Maps
                    </a>
                </div>
            </x-theme.card>

            <x-theme.card data-aos="fade-left" class="theme-card-padding-lg">
                @if(session('success'))
                    <div class="theme-status-success theme-radius theme-card-padding mb-6" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="theme-stack-lg">
                    @csrf
                    <div class="grid md:grid-cols-2 theme-content-gap">
                        <div>
                            <x-theme.label for="contact-name" value="Name" class="block mb-2 text-sm" />
                            <x-theme.input id="contact-name" type="text" name="name" placeholder="Enter your full name" />
                        </div>
                        <div>
                            <x-theme.label for="contact-email" value="Email" class="block mb-2 text-sm" />
                            <x-theme.input id="contact-email" type="email" name="email" placeholder="Enter your email address" />
                        </div>
                    </div>
                    <div>
                        <x-theme.label for="contact-phone" value="Phone" class="block mb-2 text-sm" />
                        <x-theme.input id="contact-phone" type="text" name="phone" placeholder="Enter your phone number" />
                    </div>
                    <div>
                        <x-theme.label for="contact-message" value="Message" class="block mb-2 text-sm" />
                        <x-theme.textarea id="contact-message" name="message" rows="6" placeholder="Write your message" />
                    </div>
                    <x-theme.button type="submit" class="w-full">{{ $setting->contact_cta_text ?? 'Send Message' }}</x-theme.button>
                </form>
            </x-theme.card>
        </div>

        <div class="grid md:grid-cols-3 theme-grid-gap mt-10">
            @foreach([
                ['icon' => '☎', 'label' => 'Phone', 'value' => $setting?->phone],
                ['icon' => '✉', 'label' => 'Email', 'value' => $setting->email],
                ['icon' => '◷', 'label' => 'Working Hours', 'value' => $setting->working_hours],
            ] as $contactItem)
                <x-theme.card class="theme-card-padding hover:-translate-y-2 transition duration-300 flex items-center theme-content-gap">
                    <div class="theme-icon-surface w-14 h-14 flex items-center justify-center font-bold text-lg" aria-hidden="true">
                        {{ $contactItem['icon'] }}
                    </div>
                    <div>
                        <x-theme.section-heading level="3" class="text-xl mb-1">{{ $contactItem['label'] }}</x-theme.section-heading>
                        <x-theme.section-subtitle>{{ $contactItem['value'] }}</x-theme.section-subtitle>
                    </div>
                </x-theme.card>
            @endforeach
        </div>
    </x-theme.section>
@endsection
