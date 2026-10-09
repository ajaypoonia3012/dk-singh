@extends('layouts.app')

@section('title', 'Transformational Fitness Programs | DK Singh Fitness')
@section('meta_description', 'Browse science-based transformation programs for fat loss, muscle building, and athletic conditioning crafted by elite coach DK Singh.')

@section('content')

<section class="theme-section theme-surface-muted">

    <div class="theme-page-container">

        <!-- HEADING -->

        <div class="text-center mb-20">

            <p class="theme-text-primary font-bold uppercase tracking-[4px] mb-4">

                {{ $setting->programs_page_label ?? 'Premium Programs' }}

            </p>

            <h1 class="text-5xl md:text-7xl font-black theme-text-secondary mb-8">

                {{ $setting->program_label ?? 'Transform Your Body' }}

            </h1>

            <p class="text-xl theme-text-neutral max-w-3xl mx-auto leading-relaxed">

                {{ $setting->programs_page_description ?? 'Professionally designed transformation programs for fat loss, muscle building, strength, and total fitness improvement.' }}

            </p>

        </div>

        <!-- PROGRAMS GRID -->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">

            @foreach($programs as $program)

            <div class="theme-card theme-radius overflow-hidden theme-shadow hover:-translate-y-3 hover:theme-shadow transition duration-500">

                <!-- IMAGE -->

                @if($program->image)

                <img
                    src="{{ asset('storage/' . $program->image) }}"
                    alt="{{ $program->title }}"
                    class="w-full h-[320px] object-cover"
                >

                @endif

                <!-- CONTENT -->

                <div class="theme-card-padding">

                    <p class="theme-text-primary font-bold uppercase tracking-[2px] text-sm mb-3">

                        {{ $program->category }}

                    </p>

                    <h2 class="text-3xl font-black theme-text-secondary mb-4">

                        {{ $program->title }}

                    </h2>

                    <p class="theme-text-neutral leading-relaxed mb-6">

                        {{ Str::limit($program->description, 100) }}

                    </p>

                    <div class="flex justify-between items-center mb-8">

                        <span class="theme-text-neutral font-medium">

                            {{ $program->duration }}

                        </span>

                        <span class="text-3xl font-black theme-text-primary">

                            &#8377;{{ number_format($program->price) }}

                        </span>

                    </div>

                    <a href="{{ route('programs.show', $program->slug) }}"
                       class="block text-center theme-status-warning hover:theme-status-warning theme-text-secondary font-black py-4 theme-radius transition duration-300">

                        View Program

                    </a>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>

@endsection
