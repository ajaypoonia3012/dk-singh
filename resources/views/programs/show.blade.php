@extends('layouts.app')

@section('title', $program->title . ' | DK Singh Fitness')
@section('meta_description', Str::limit(strip_tags($program->description), 155))
@section('meta_keywords', $program->title . ', fitness program, workout plan, DK Singh Fitness')


@section('content')

<section class="relative theme-section theme-surface-muted overflow-hidden min-h-screen">

    <!-- BACKGROUND EFFECT -->

    <div class="absolute top-0 right-0 w-[500px] h-[500px] theme-surface-muted opacity-20 blur-3xl rounded-full"></div>

    <div class="theme-page-container relative z-10">

        <div class="grid lg:grid-cols-2 gap-20 items-center">

            <!-- IMAGE -->

            <div data-aos="fade-right">

                @if($program->image)

                    <div class="overflow-hidden theme-radius theme-shadow">

                        <img
                            src="{{ asset('storage/' . $program->image) }}"
                            alt="{{ $program->title }}"
                            class="w-full h-[700px] object-cover hover:scale-105 transition duration-700"
                        >

                    </div>

                @else

                    <div class="w-full h-[700px] theme-radius theme-card flex items-center justify-center theme-shadow">

                        <span class="text-8xl">
                            <svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg>
                        </span>

                    </div>

                @endif

            </div>

            <!-- CONTENT -->

            <div data-aos="fade-left">

                <!-- BADGE -->

                <div class="inline-flex items-center gap-3 theme-status-warning theme-text-secondary px-6 py-3 rounded-full font-bold text-sm uppercase tracking-[2px] mb-8 theme-shadow">

                    Premium Fitness Program

                </div>

                <!-- TITLE -->

                <h1 class="text-5xl md:text-7xl font-black theme-text-secondary leading-tight mb-8">

                    {{ $program->title }}

                </h1>

                <!-- PRICE -->

                <div class="flex items-end gap-4 mb-10">

                    <span class="text-6xl font-black theme-text-primary">

                        &#8377;{{ number_format($program->price) }}

                    </span>

                    @if($program->duration)

                    <span class="text-2xl theme-text-neutral mb-2">

                        /{{ $program->duration }}

                    </span>

                    @endif

                </div>

                <!-- DESCRIPTION -->

                <p class="text-xl theme-text-neutral leading-[42px] mb-12">

                    {{ $program->description }}

                </p>

                <!-- BUTTONS -->

                <div class="flex flex-wrap theme-content-gap mb-14">

                    <a href="{{ url('/plans?program=' . $program->slug) }}"
                       class="theme-status-warning hover:theme-status-warning hover:scale-105 theme-text-secondary font-black px-10 py-5 theme-radius transition duration-300 theme-shadow">

                        Enroll Now &rarr;

                    </a>

                    <a href="/transformations"
                       class="border-2 theme-border hover:theme-surface-strong hover:theme-text-on-strong theme-text-secondary font-black px-10 py-5 theme-radius transition duration-300">

                        View Transformations

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

@endsection
