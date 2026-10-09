@extends('layouts.app')

@section('title', 'Coaching & Programs | DK Singh Fitness')
@section('meta_description', 'Explore DK Singh\'s specialized transformation programs, 1-on-1 personal coaching, and flexible membership tiers designed for sustainable results.')

@section('content')

{{-- HERO / HUB HEADER --}}
<section class="theme-section theme-surface-muted border-b" style="border-color: color-mix(in srgb, var(--navbar-text) 10%, transparent);">
    <div class="theme-page-container text-center">
        <p class="theme-text-primary font-bold uppercase tracking-[4px] text-sm mb-4">
            Elite Training & Mentorship
        </p>

        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black theme-text-secondary leading-tight mb-6">
            Coaching & <span class="theme-text-primary">Programs</span>
        </h1>

        <p class="text-lg md:text-xl theme-text-neutral max-w-3xl mx-auto leading-relaxed mb-10">
            Choose the path that fits your goals: explore structured 12-week transformation curriculums, apply for elite 1-on-1 personal mentorship, or join our all-access membership plans.
        </p>

        {{-- QUICK JUMP NAVIGATION --}}
        <div class="inline-flex flex-wrap items-center justify-center gap-3 p-2 theme-card theme-radius theme-shadow max-w-4xl mx-auto">
            <a href="#programs" class="theme-navbar-link text-xs md:text-sm font-bold uppercase tracking-wider px-4 py-2 rounded-lg hover:theme-surface-strong transition">
                1. Transformation Programs
            </a>
            <span class="opacity-30 hidden sm:inline">&bull;</span>
            <a href="#coaching" class="theme-navbar-link text-xs md:text-sm font-bold uppercase tracking-wider px-4 py-2 rounded-lg hover:theme-surface-strong transition">
                2. 1-on-1 Coaching
            </a>
            <span class="opacity-30 hidden sm:inline">&bull;</span>
            <a href="#plans" class="theme-navbar-link text-xs md:text-sm font-bold uppercase tracking-wider px-4 py-2 rounded-lg hover:theme-surface-strong transition">
                3. Membership Plans
            </a>
        </div>
    </div>
</section>

{{-- SECTION 1: PROGRAMS --}}
<section id="programs" class="theme-section">
    <div class="theme-page-container">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-6">
            <div>
                <span class="theme-text-primary font-bold uppercase tracking-[3px] text-sm block mb-2">
                    Section 1 &bull; Structured Curriculums
                </span>
                <h2 class="text-3xl md:text-5xl font-black theme-text-secondary">
                    Transformation Programs
                </h2>
                <p class="theme-text-neutral mt-3 max-w-2xl text-base md:text-lg leading-relaxed">
                    Goal-oriented, self-paced workout and nutrition blueprints designed for specific physiological outcomes: fat loss, muscle hypertrophy, or athletic conditioning.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('programs.index') }}" class="theme-navbar-link font-bold text-sm uppercase tracking-wider inline-flex items-center gap-1.5 hover:theme-text-primary transition">
                    <span>View Dedicated Programs Page</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        @if($programs->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">
                @foreach($programs as $program)
                    <div class="theme-card theme-radius overflow-hidden theme-shadow flex flex-col justify-between hover:-translate-y-2 hover:theme-shadow transition duration-300">
                        <div>
                            {{-- IMAGE --}}
                            @if($program->image)
                                <img
                                    src="{{ asset('storage/' . $program->image) }}"
                                    alt="{{ $program->title }}"
                                    class="w-full h-60 object-cover"
                                >
                            @else
                                <div class="w-full h-60 theme-surface-strong flex items-center justify-center">
                                    <span class="theme-text-primary font-black text-2xl uppercase tracking-wider">
                                        {{ $program->category ?? 'Training' }}
                                    </span>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="theme-text-primary font-bold uppercase tracking-[2px] text-xs">
                                        {{ $program->category ?? 'Training Protocol' }}
                                    </span>
                                    @if($program->duration)
                                        <span class="text-xs theme-text-neutral font-semibold px-2.5 py-1 rounded-full theme-surface-muted">
                                            {{ $program->duration }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-2xl font-black theme-text-secondary mb-3 leading-snug">
                                    {{ $program->title }}
                                </h3>

                                <p class="theme-text-neutral text-sm leading-relaxed mb-6">
                                    {{ Str::limit(strip_tags($program->description), 110) }}
                                </p>
                            </div>
                        </div>

                        <div class="p-6 pt-0 border-t theme-border">
                            <div class="flex items-center justify-between py-4">
                                <span class="text-xs theme-text-neutral uppercase tracking-wider font-semibold">
                                    Program Fee
                                </span>
                                <span class="text-2xl font-black theme-text-primary">
                                    &#8377;{{ number_format($program->price) }}
                                </span>
                            </div>

                            <a
                                href="{{ route('programs.show', $program->slug) }}"
                                class="btn-primary block text-center w-full !py-3.5 !text-sm font-bold uppercase tracking-wider"
                            >
                                View Program Curriculum &rarr;
                            </a>

                            <p class="text-[11px] theme-text-neutral text-center mt-2 opacity-80">
                                Detailed curriculum & optional plan enrollment
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="theme-text-neutral text-center py-10">No transformation programs are currently published.</p>
        @endif
    </div>
</section>

{{-- SECTION 2: SERVICES / 1-ON-1 COACHING --}}
<section id="coaching" class="theme-section theme-surface-muted border-y" style="border-color: color-mix(in srgb, var(--navbar-text) 10%, transparent);">
    <div class="theme-page-container">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-6">
            <div>
                <span class="theme-text-primary font-bold uppercase tracking-[3px] text-sm block mb-2">
                    Section 2 &bull; Direct Mentorship
                </span>
                <h2 class="text-3xl md:text-5xl font-black theme-text-secondary">
                    1-on-1 Personal Coaching
                </h2>
                <p class="theme-text-neutral mt-3 max-w-2xl text-base md:text-lg leading-relaxed">
                    Bespoke online mentorship directly with Coach DK Singh. Includes custom macronutrient planning, bi-weekly physique reviews, form analysis, and direct messaging support.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('services.index') }}" class="theme-navbar-link font-bold text-sm uppercase tracking-wider inline-flex items-center gap-1.5 hover:theme-text-primary transition">
                    <span>View Dedicated Coaching Page</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        @if($services->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">
                @foreach($services as $service)
                    <div class="theme-card theme-radius overflow-hidden theme-shadow flex flex-col justify-between hover:-translate-y-2 hover:theme-shadow transition duration-300">
                        <div>
                            {{-- IMAGE --}}
                            @if($service->image)
                                <img
                                    src="{{ asset('storage/' . $service->image) }}"
                                    alt="{{ $service->title }}"
                                    class="w-full h-60 object-cover"
                                >
                            @else
                                <div class="w-full h-60 theme-surface-strong flex items-center justify-center">
                                    <span class="theme-text-primary font-black text-2xl uppercase tracking-wider">
                                        Personal Coaching
                                    </span>
                                </div>
                            @endif

                            <div class="p-6">
                                <span class="theme-text-primary font-bold uppercase tracking-[2px] text-xs block mb-3">
                                    1-on-1 Mentorship
                                </span>

                                <h3 class="text-2xl font-black theme-text-secondary mb-3 leading-snug">
                                    {{ $service->title }}
                                </h3>

                                <p class="theme-text-neutral text-sm leading-relaxed mb-6">
                                    {{ Str::limit(strip_tags($service->description), 110) }}
                                </p>

                                {{-- KEY FEATURES IF AVAILABLE --}}
                                @if($service->features)
                                    @php
                                        $featuresList = is_array($service->features) ? $service->features : json_decode($service->features ?? '[]', true);
                                    @endphp
                                    @if(is_array($featuresList) && count($featuresList) > 0)
                                        <div class="space-y-2 mb-6 pt-2 border-t theme-border">
                                            @foreach(array_slice($featuresList, 0, 3) as $f)
                                                <div class="flex items-start gap-2 text-xs theme-text-neutral">
                                                    <span class="theme-text-primary font-bold">&check;</span>
                                                    <span>{{ is_array($f) ? ($f['feature'] ?? '') : $f }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="p-6 pt-0 border-t theme-border">
                            @if($service->price)
                                <div class="flex items-center justify-between py-4">
                                    <span class="text-xs theme-text-neutral uppercase tracking-wider font-semibold">
                                        Mentorship Investment
                                    </span>
                                    <span class="text-2xl font-black theme-text-primary">
                                        &#8377;{{ number_format($service->price) }}
                                        @if($service->duration)
                                            <span class="text-xs theme-text-neutral font-normal">/{{ $service->duration }}</span>
                                        @endif
                                    </span>
                                </div>
                            @endif

                            <div class="flex flex-col gap-2">
                                <a
                                    href="{{ route('contact', ['service' => $service->slug]) }}"
                                    class="btn-primary block text-center w-full !py-3.5 !text-sm font-bold uppercase tracking-wider"
                                >
                                    {{ $service->button_text ?? 'Apply for 1-on-1 Coaching' }} &rarr;
                                </a>

                                <a
                                    href="{{ route('services.show', $service->slug) }}"
                                    class="theme-navbar-link text-center text-xs font-semibold py-1.5 hover:underline"
                                >
                                    View Full Service Breakdown &rarr;
                                </a>
                            </div>

                            <p class="text-[11px] theme-text-neutral text-center mt-2 opacity-80">
                                Direct enquiry form &bull; Requires coach consultation
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="theme-text-neutral text-center py-10">No coaching services are currently listed.</p>
        @endif
    </div>
</section>

{{-- SECTION 3: MEMBERSHIP PLANS --}}
<section id="plans" class="theme-section">
    <div class="theme-page-container">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-6">
            <div>
                <span class="theme-text-primary font-bold uppercase tracking-[3px] text-sm block mb-2">
                    Section 3 &bull; All-Access Tiers
                </span>
                <h2 class="text-3xl md:text-5xl font-black theme-text-secondary">
                    Membership Plans
                </h2>
                <p class="theme-text-neutral mt-3 max-w-2xl text-base md:text-lg leading-relaxed">
                    Ongoing recurring access to DK Singh workout routines, exercise video libraries, digital resources, and client dashboard features.
                </p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('plans.index') }}" class="theme-navbar-link font-bold text-sm uppercase tracking-wider inline-flex items-center gap-1.5 hover:theme-text-primary transition">
                    <span>View Dedicated Plans Page</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        @if(!empty($selectedProgram))
            <div class="mb-12 p-6 md:p-8 theme-radius theme-card border-2 theme-border theme-shadow flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider theme-status-warning theme-text-secondary mb-2">
                        Selected Program Curriculum
                    </span>
                    <h3 class="text-2xl md:text-3xl font-black theme-text-secondary">
                        {{ $selectedProgram->title }} ({{ $selectedProgram->duration }})
                    </h3>
                    <p class="theme-text-neutral mt-2 max-w-2xl text-sm md:text-base leading-relaxed">
                        To unlock this curriculum, choose your preferred membership access tier below. Basic unlocks training routines; Pro and Elite unlock customized nutrition and coach feedback.
                    </p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('programs.show', $selectedProgram->slug) }}" class="inline-block text-xs font-bold uppercase tracking-wider underline hover:theme-text-primary transition">
                        Change Program &rarr;
                    </a>
                </div>
            </div>
        @endif

        @if($plans->count() > 0)
            <div class="grid lg:grid-cols-3 theme-grid-gap items-stretch">
                @foreach($plans as $plan)
                    <div class="relative theme-radius overflow-hidden theme-shadow flex flex-col justify-between transition duration-300 hover:-translate-y-2
                        {{ $plan->featured
                            ? 'theme-surface-strong theme-text-on-strong border-2 theme-border lg:-translate-y-2'
                            : 'theme-card theme-text-secondary border theme-border'
                        }}
                    ">
                        @if($plan->badge)
                            <div class="absolute top-0 right-0 theme-status-warning theme-text-secondary px-5 py-1.5 theme-radius font-black text-xs uppercase tracking-[1px] theme-shadow z-10">
                                {{ $plan->badge }}
                            </div>
                        @endif

                        @if($plan->thumbnail)
                            <img
                                src="{{ asset('storage/' . $plan->thumbnail) }}"
                                alt="{{ $plan->name }} Coaching Plan"
                                class="w-full h-48 object-cover"
                            >
                        @endif

                        <div class="p-6 md:p-8 flex flex-col flex-grow">
                            <p class="uppercase tracking-[3px] text-xs font-bold mb-3 theme-text-primary">
                                {{ $plan->access_type ?? 'Membership' }}
                            </p>

                            <h3 class="text-3xl font-black mb-3 leading-tight">
                                {{ $plan->name }}
                            </h3>

                            <p class="text-sm leading-relaxed mb-6 theme-text-neutral">
                                {{ $plan->description }}
                            </p>

                            {{-- PRICING --}}
                            <div class="flex items-baseline gap-3 mb-8">
                                @if($plan->discount_price)
                                    <span class="text-4xl md:text-5xl font-black theme-text-primary">
                                        &#8377;{{ number_format($plan->discount_price) }}
                                    </span>
                                    <span class="line-through text-lg theme-text-neutral">
                                        &#8377;{{ number_format($plan->price) }}
                                    </span>
                                @else
                                    <span class="text-4xl md:text-5xl font-black theme-text-primary">
                                        &#8377;{{ number_format($plan->price) }}
                                    </span>
                                @endif
                                <span class="text-sm theme-text-neutral">
                                    /{{ strtolower($plan->billing_cycle ?? 'month') }}
                                </span>
                            </div>

                            {{-- FEATURES --}}
                            @if($plan->features)
                                <div class="space-y-3 mb-8 pt-4 border-t theme-border flex-grow">
                                    @foreach($plan->features as $feature)
                                        <div class="flex items-start gap-2.5 text-xs md:text-sm">
                                            <span class="theme-text-primary font-bold">&check;</span>
                                            <span class="theme-text-neutral">
                                                {{ is_array($feature) ? ($feature['feature'] ?? '') : $feature }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- CHECKOUT CTA --}}
                            <div class="pt-4 border-t theme-border mt-auto">
                                <a
                                    href="{{ url('/checkout/' . $plan->id . (!empty($selectedProgram) ? '?program=' . $selectedProgram->slug : '')) }}"
                                    class="block w-full"
                                >
                                    <button class="w-full py-4 theme-radius font-black text-sm uppercase tracking-wider transition duration-300
                                        {{ $plan->featured
                                            ? 'theme-status-warning theme-text-secondary hover:theme-status-warning'
                                            : 'border-2 theme-border hover:theme-surface-strong hover:theme-text-on-strong'
                                        }}
                                    ">
                                        {{ $plan->button_text ?? 'Select Plan' }} &rarr;
                                    </button>
                                </a>

                                <p class="text-[11px] theme-text-neutral text-center mt-2 opacity-80">
                                    Instant checkout via Razorpay &bull; Active membership grant
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="theme-text-neutral text-center py-10">No membership plans are currently published.</p>
        @endif
    </div>
</section>

{{-- COMPARISON / GUIDANCE SECTION --}}
<section class="theme-section theme-surface-strong text-center border-t" style="border-color: color-mix(in srgb, var(--navbar-text) 10%, transparent);">
    <div class="theme-page-container">
        <h2 class="text-2xl md:text-4xl font-black mb-4 theme-text-on-strong">
            Not Sure Which Option You Need?
        </h2>
        <p class="theme-text-neutral max-w-2xl mx-auto text-sm md:text-base leading-relaxed mb-10">
            Here is a fast breakdown of how our three pathways differ so you make the best decision for your fitness journey:
        </p>

        <div class="grid md:grid-cols-3 gap-6 text-left max-w-5xl mx-auto">
            <div class="p-6 theme-card theme-radius theme-shadow">
                <span class="text-2xl block mb-2">&bull;</span>
                <h3 class="text-lg font-black theme-text-secondary mb-2">1. Programs</h3>
                <p class="text-xs theme-text-neutral leading-relaxed">
                    Targeted, structured 12-week protocols (e.g., Fat Loss or Muscle Gain). Best if you want a complete curriculum to follow step-by-step.
                </p>
            </div>
            <div class="p-6 theme-card theme-radius theme-shadow">
                <span class="text-2xl block mb-2">&bull;</span>
                <h3 class="text-lg font-black theme-text-secondary mb-2">2. Personal Coaching</h3>
                <p class="text-xs theme-text-neutral leading-relaxed">
                    Direct 1-on-1 mentorship with Coach DK Singh. Best if you need tailored nutrition macros, weekly accountability, and direct check-ins.
                </p>
            </div>
            <div class="p-6 theme-card theme-radius theme-shadow">
                <span class="text-2xl block mb-2">&bull;</span>
                <h3 class="text-lg font-black theme-text-secondary mb-2">3. Membership Plans</h3>
                <p class="text-xs theme-text-neutral leading-relaxed">
                    Monthly/annual all-access tiers granting immediate portal entry to all exercise libraries, workouts, and member tools.
                </p>
            </div>
        </div>

        <div class="mt-12">
            <a href="{{ route('contact') }}" class="btn-primary inline-block font-bold uppercase tracking-wider text-xs md:text-sm px-8 py-4">
                Still have questions? Speak to our team &rarr;
            </a>
        </div>
    </div>
</section>

@endsection
