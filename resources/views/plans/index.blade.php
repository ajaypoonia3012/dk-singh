@extends('layouts.app')

@section('title', 'Membership & Coaching Plans | DK Singh Fitness')
@section('meta_description', 'Choose your fitness plan: personalized workout splits, macro-tailored nutrition, and direct 1-on-1 accountability with DK Singh.')

@section('content')

<section class="theme-section theme-surface-muted">

    <div class="theme-page-container">

        {{-- HEADING --}}
        <div class="text-center mb-20">

            <p class="theme-text-primary font-bold uppercase tracking-[4px] mb-4">
                Membership Plans
            </p>

            <h1 class="text-5xl md:text-6xl font-black theme-text-secondary mb-6">
                Choose Your
                <span class="theme-text-primary">Fitness Plan</span>
            </h1>

            <p class="text-xl theme-text-neutral max-w-3xl mx-auto leading-[38px]">
                Select the perfect plan designed to help you achieve your
                transformation goals faster and smarter.
            </p>

        </div>

        @if(!empty($selectedProgram))
            <div class="mb-14 p-6 md:p-8 theme-radius theme-card border-2 theme-border theme-shadow flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider theme-status-warning theme-text-secondary mb-2">
                        Selected Program Curriculum
                    </span>
                    <h2 class="text-2xl md:text-3xl font-black theme-text-secondary">
                        {{ $selectedProgram->title }} ({{ $selectedProgram->duration }})
                    </h2>
                    <p class="theme-text-neutral mt-2 max-w-2xl text-sm md:text-base leading-relaxed">
                        To enroll in this transformation curriculum, choose your preferred membership access tier below. Basic unlocks workout routines, while Pro and Elite unlock personalized nutrition and 1:1 coaching.
                    </p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('programs.show', $selectedProgram->slug) }}" class="inline-block text-xs font-bold uppercase tracking-wider underline hover:theme-text-primary transition">
                        Change Program
                    </a>
                </div>
            </div>
        @endif

        {{-- PLANS GRID --}}
        <div class="grid lg:grid-cols-3 theme-grid-gap">

            @foreach($plans as $plan)

                <div class="relative theme-radius overflow-hidden theme-shadow transition duration-500 hover:-translate-y-3 hover:theme-shadow

                    {{ $plan->featured
                        ? 'theme-surface-strong theme-text-on-strong scale-105 border-4 theme-border'
                        : 'theme-card theme-text-secondary border theme-border'
                    }}
                ">

                    {{-- BADGE --}}
                    @if($plan->badge)

                        <div class="absolute top-0 right-0 theme-status-warning theme-text-secondary px-6 py-2 theme-radius font-black text-sm uppercase tracking-[1px] theme-shadow">

                            {{ $plan->badge }}

                        </div>

                    @endif

                    {{-- IMAGE --}}
                    @if($plan->thumbnail)

                        <img
                            src="{{ asset('storage/' . $plan->thumbnail) }}"
                            alt="{{ $plan->name }} Coaching Plan"
                            class="w-full h-[220px] object-cover"
                        >

                    @endif

                    <div class="theme-card-padding-lg">

                        {{-- ACCESS TYPE --}}
                        <p class="uppercase tracking-[3px] text-sm font-bold mb-5

                            {{ $plan->featured
                                ? 'theme-text-primary'
                                : 'theme-text-primary'
                            }}
                        ">

                            {{ $plan->access_type }}

                        </p>

                        {{-- PLAN NAME --}}
                        <h2 class="text-4xl font-black mb-4 leading-tight">

                            {{ $plan->name }}

                        </h2>

                        {{-- DESCRIPTION --}}
                        <p class="text-[16px] leading-[32px] mb-8

                            {{ $plan->featured
                                ? 'theme-text-neutral'
                                : 'theme-text-neutral'
                            }}
                        ">

                            {{ $plan->description }}

                        </p>

                        {{-- PRICING --}}
                        <div class="flex items-end gap-3 mb-10">

                            @if($plan->discount_price)

                                <span class="text-6xl font-black theme-text-primary">

                                    &#8377;{{ number_format($plan->discount_price) }}

                                </span>

                                <span class="line-through text-xl theme-text-neutral">

                                    &#8377;{{ number_format($plan->price) }}

                                </span>

                            @else

                                <span class="text-6xl font-black theme-text-primary">

                                    &#8377;{{ number_format($plan->price) }}

                                </span>

                            @endif

                            <span class="mb-2 text-lg

                                {{ $plan->featured
                                    ? 'theme-text-neutral'
                                    : 'theme-text-neutral'
                                }}
                            ">

                                /{{ strtolower($plan->billing_cycle) }}

                            </span>

                        </div>

                        {{-- FEATURES --}}
                        @if($plan->features)

                            <div class="space-y-5 mb-12">

                                @foreach($plan->features as $feature)

                                    <div class="flex items-start gap-4">

                                        <div class="mt-1 theme-text-primary text-xl">
                                            <svg class="inline-block w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>
                                        </div>

                                        <span class="text-[16px] leading-[30px]

                                            {{ $plan->featured
                                                ? 'theme-text-neutral'
                                                : 'theme-text-neutral'
                                            }}
                                        ">

                                            {{ is_array($feature)
                                                ? $feature['feature']
                                                : $feature
                                            }}

                                        </span>

                                    </div>

                                @endforeach

                            </div>

                        @endif

                        {{-- BUTTON --}}


<a href="{{ url('/checkout/'.$plan->id . (!empty($selectedProgram) ? '?program='.$selectedProgram->slug : '')) }}">

    <button class="w-full py-5 theme-radius font-black text-lg transition duration-300

        {{ $plan->featured
            ? 'theme-status-warning theme-text-secondary hover:theme-status-warning'
            : 'border-2 theme-border hover:theme-surface-strong hover:theme-text-on-strong'
        }}
    ">

        {{ $plan->button_text ?? 'Get Started' }}

    </button>

</a>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endsection
