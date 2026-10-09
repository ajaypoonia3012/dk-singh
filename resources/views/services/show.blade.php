@extends('layouts.app')

@section('title', $service->title . ' | DK Singh Fitness')
@section('meta_description', Str::limit(strip_tags($service->description), 155))
@section('meta_keywords', $service->title . ', fitness coaching, DK Singh Fitness')


@section('content')

<section class="relative theme-section theme-surface-muted overflow-hidden min-h-screen">

    <!-- BACKGROUND EFFECT -->

    <div class="absolute top-0 right-0 w-[500px] h-[500px] theme-surface-muted opacity-20 blur-3xl rounded-full"></div>

    <div class="theme-page-container relative z-10">

        <div class="grid lg:grid-cols-2 gap-20 items-center">

            <!-- IMAGE -->

            <div data-aos="fade-right">

                @if($service->image)

                    <div class="overflow-hidden theme-radius theme-shadow">

                        <img
                            src="{{ asset('storage/' . $service->image) }}"
                            alt="{{ $service->title }}"
                            class="w-full h-[700px] object-cover hover:scale-105 transition duration-700"
                        >

                    </div>

                @else

                    <div class="w-full h-[700px] theme-radius theme-card flex items-center justify-center theme-shadow">

                        <span class="text-8xl">🔥</span>

                    </div>

                @endif

            </div>

            <!-- CONTENT -->

            <div data-aos="fade-left">

                <!-- BADGE -->

                <div class="inline-flex items-center gap-3 theme-status-warning theme-text-secondary px-6 py-3 rounded-full font-bold text-sm uppercase tracking-[2px] mb-8 theme-shadow">

                    Premium Fitness Service

                </div>

                <!-- TITLE -->

                <h1 class="text-5xl md:text-7xl font-black theme-text-secondary leading-tight mb-8">

                    {{ $service->title }}

                </h1>

                <!-- PRICE -->

                <div class="flex items-end gap-4 mb-10">

                    <span class="text-6xl font-black theme-text-primary">

                        ₹{{ number_format($service->price) }}

                    </span>

                    @if($service->duration)

                    <span class="text-2xl theme-text-neutral mb-2">

                        /{{ $service->duration }}

                    </span>

                    @endif

                </div>

                <!-- DESCRIPTION -->

                <p class="text-xl theme-text-neutral leading-[42px] mb-12">

                    {{ $service->description }}

                </p>

                <!-- FEATURES -->

                @if($service->features)

                <div class="grid sm:grid-cols-2 theme-content-gap mb-12">

                    @foreach((is_array($service->features) ? $service->features : json_decode($service->features ?? '[]', true)) as $feature)

                    <div class="flex items-start gap-4 theme-card theme-radius p-5 theme-shadow hover:-translate-y-1 hover:theme-shadow transition duration-300">

                        <div class="w-8 h-8 rounded-full theme-status-warning flex items-center justify-center theme-text-secondary font-black text-sm mt-1"><svg class="inline-block w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg></div>

                        <div class="w-8 h-8 rounded-full theme-status-warning flex items-center justify-center theme-text-secondary font-black text-sm mt-1">✓</div>

                            {{ is_array($feature) ? ($feature["feature"] ?? "") : $feature }}

                        </span>

                    </div>

                    @endforeach

                </div>

                @endif

                <!-- BUTTONS -->

                <div class="flex flex-wrap theme-content-gap mb-14">

                    <a href="{{ route('contact', ['service' => $service->slug]) }}"
                       class="theme-status-warning hover:theme-status-warning hover:scale-105 theme-text-secondary font-black px-10 py-5 theme-radius transition duration-300 theme-shadow">

                        {{ $service->button_text ?? 'Get Started' }}
                    </a>

                    <a href="/plans"
                       class="border-2 theme-border hover:theme-surface-strong hover:theme-text-on-strong theme-text-secondary font-black px-10 py-5 theme-radius transition duration-300">

                        {{ $setting->view_programs_text }}

                    </a>

                </div>

                <!-- TRUST STATS -->

                <div class="grid grid-cols-3 theme-content-gap">

                    <div class="theme-card theme-radius p-6 theme-shadow text-center">

                        <h3 class="text-4xl font-black theme-text-primary">
                            15K+
                        </h3>

                        <p class="theme-text-neutral mt-2">
                            Transformations
                        </p>

                    </div>

                    <div class="theme-card theme-radius p-6 theme-shadow text-center">

                        <h3 class="text-4xl font-black theme-text-primary">
                            15+
                        </h3>

                        <p class="theme-text-neutral mt-2">
                            Years Experience
                        </p>

                    </div>

                    <div class="theme-card theme-radius p-6 theme-shadow text-center">

                        <h3 class="text-4xl font-black theme-text-primary">
                            24/7
                        </h3>

                        <p class="theme-text-neutral mt-2">
                            Support
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- CTA SECTION -->

<section class="theme-section theme-surface-strong relative overflow-hidden">

    <div class="absolute top-0 left-0 w-full h-full opacity-10">

        <div class="absolute totheme-card-padding-lg left-10 w-72 h-72 theme-status-warning rounded-full blur-3xl"></div>

        <div class="absolute bottom-10 right-10 w-72 h-72 theme-status-warning rounded-full blur-3xl"></div>

    </div>

    <div class="theme-page-container relative z-10 text-center">

        <p class="theme-text-primary uppercase tracking-[4px] font-bold mb-6">

            Start Your Transformation

        </p>

        <h2 class="text-5xl md:text-6xl font-black theme-text-on-strong leading-tight mb-8">

            Ready To Achieve
            <br>
            Real Fitness Results?

        </h2>

        <p class="text-xl theme-text-neutral leading-relaxed mb-12 max-w-3xl mx-auto">

            Join {{ $setting->site_name }} programs and get expert coaching,
            structured guidance, and a transformation-focused system
            designed for sustainable results.

        </p>

        <div class="flex flex-wrap justify-center theme-content-gap">

            <a href="/plans"
               class="theme-status-warning hover:theme-status-warning hover:scale-105 theme-text-secondary font-black px-10 py-5 theme-radius transition duration-300 theme-shadow">

                Book Consultation

            </a>

            <a href="/transformations"
               class="border theme-border theme-text-on-strong hover:theme-card hover:theme-text-secondary hover:scale-105 px-10 py-5 theme-radius font-black transition duration-300">

                {{ $setting->view_transformations_text }}

            </a>

        </div>

    </div>

</section>

@endsection
