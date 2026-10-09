@extends('layouts.app')

@section('content')

<section class="min-h-screen theme-surface-muted theme-section">

    <div class="theme-page-container">

        <div class="text-center mb-16">

            <p class="uppercase tracking-[3px] theme-text-primary font-bold mb-4">
                My Transformation Journey
            </p>

            <h1 class="text-5xl font-black">
                Progress Timeline
            </h1>

        </div>

        <div class="grid lg:grid-cols-5 theme-content-gap mb-16">

    {{-- STARTING POINT --}}
    <div class="theme-card theme-card-padding theme-radius theme-shadow">

        <p class="uppercase text-xs tracking-[2px] theme-text-neutral mb-3">
            Starting Point
        </p>

        <h3 class="text-xl font-black mb-2">
            {{ $oldest?->weight }} kg
        </h3>

        <p class="theme-text-neutral">
            BMI {{ $oldest?->bmi }}
        </p>

        <p class="text-sm theme-text-neutral mt-3">
            {{ $oldest?->created_at?->format('d/m/Y') }}
        </p>

    </div>

    {{-- CURRENT PROGRESS --}}
    <div class="theme-card theme-card-padding theme-radius theme-shadow">

        <p class="uppercase text-xs tracking-[2px] theme-text-neutral mb-3">
            Current Progress
        </p>

        <h3 class="text-xl font-black mb-2">
            {{ $latest?->weight }} kg
        </h3>

        <p class="theme-text-neutral">
            BMI {{ $latest?->bmi }}
        </p>

        <p class="text-sm theme-text-neutral mt-3">
            {{ $latest?->created_at?->format('d/m/Y') }}
        </p>

    </div>

    {{-- WEIGHT LOST --}}
    <div class="theme-card theme-card-padding theme-radius theme-shadow">

        <p class="uppercase text-xs tracking-[2px] theme-text-neutral mb-3">
            Weight Lost
        </p>

        <h3 class="text-4xl font-black theme-text-success">
            {{ number_format($weightLost,1) }} kg
        </h3>

    </div>

    {{-- BMI IMPROVEMENT --}}
    <div class="theme-card theme-card-padding theme-radius theme-shadow">

        <p class="uppercase text-xs tracking-[2px] theme-text-neutral mb-3">
            BMI Improvement
        </p>

        <h3 class="text-4xl font-black theme-text-info">
            {{ number_format($bmiImprovement,1) }}
        </h3>

    </div>

    {{-- JOURNEY DURATION --}}
    <div class="theme-card theme-card-padding theme-radius theme-shadow">

        <p class="uppercase text-xs tracking-[2px] theme-text-neutral mb-3">
            Journey Duration
        </p>

        <h3 class="text-4xl font-black theme-text-primary">

            {{ $oldest && $latest
    ? (int) $oldest->created_at->startOfDay()->diffInDays(
        $latest->created_at->startOfDay()
    )
    : 0 }}

        </h3>

        <p class="theme-text-neutral mt-2">
            Days
        </p>

    </div>

</div>

{{-- BEFORE VS CURRENT --}}

@if($logs->count() >= 2)

<div class="theme-card theme-radius theme-card-padding-lg theme-shadow mb-12">

    <h2 class="text-4xl font-black mb-8 text-center">
        Before vs Current
    </h2>

    <div class="grid md:grid-cols-2 theme-grid-gap">

        <div>

            <h3 class="text-xl font-bold mb-4 text-center">
                Starting Point
            </h3>

            @if($oldest?->front_photo)

                <img
                    src="{{ asset('storage/'.$oldest->front_photo) }}"
                    class="theme-radius w-full theme-shadow">

            @endif

            <div class="mt-4 text-center">

                <strong>
                    {{ $oldest?->weight }} kg
                </strong>

            </div>

        </div>

        <div>

            <h3 class="text-xl font-bold mb-4 text-center">
                Current
            </h3>

            @if($latest?->front_photo)

                <img
                    src="{{ asset('storage/'.$latest->front_photo) }}"
                    class="theme-radius w-full theme-shadow">

            @endif

            <div class="mt-4 text-center">

                <strong>
                    {{ $latest?->weight }} kg
                </strong>

            </div>

        </div>

    </div>

</div>

@endif


{{-- TRANSFORMATION SCOREBOARD --}}

<div class="grid md:grid-cols-4 theme-content-gap mb-12">

    <div class="theme-card theme-card-padding theme-radius theme-shadow">

        <p class="uppercase text-xs tracking-[2px] theme-text-neutral mb-3">
            Total Check-Ins
        </p>

        <h3 class="text-5xl font-black">
            {{ $logs->count() }}
        </h3>

    </div>

    <div class="theme-card theme-card-padding theme-radius theme-shadow">

        <p class="uppercase text-xs tracking-[2px] theme-text-neutral mb-3">
            Weight Change
        </p>

        <h3 class="text-5xl font-black theme-text-success">
            {{ number_format($weightLost,1) }}
        </h3>

        <p class="theme-text-neutral mt-2">
            kg
        </p>

    </div>

    <div class="theme-card theme-card-padding theme-radius theme-shadow">

        <p class="uppercase text-xs tracking-[2px] theme-text-neutral mb-3">
            BMI Change
        </p>

        <h3 class="text-5xl font-black theme-text-info">
            {{ number_format($bmiImprovement,1) }}
        </h3>

    </div>

    <div class="theme-card theme-card-padding theme-radius theme-shadow">

        <p class="uppercase text-xs tracking-[2px] theme-text-neutral mb-3">
            Journey Days
        </p>

        <h3 class="text-5xl font-black theme-text-primary">

            {{ $oldest && $latest
                ? (int) $oldest->created_at->startOfDay()->diffInDays(
                    $latest->created_at->startOfDay()
                )
                : 0 }}

        </h3>

    </div>

</div>


{{-- TRANSFORMATION MILESTONES --}}

@php

$milestones = [];

if ($logs->count() >= 1) {
    $milestones[] = 'First Check-In';
}

if ($logs->count() >= 5) {
    $milestones[] = '5 Check-Ins Completed';
}

if ($logs->count() >= 10) {
    $milestones[] = '10 Check-Ins Completed';
}

if ($weightLost >= 5) {
    $milestones[] = '5kg Lost';
}

if ($weightLost >= 10) {
    $milestones[] = '10kg Lost';
}

$journeyDays = $oldest && $latest
    ? (int) $oldest->created_at
        ->startOfDay()
        ->diffInDays(
            $latest->created_at->startOfDay()
        )
    : 0;

if ($journeyDays >= 30) {
    $milestones[] = '30 Day Journey';
}

if ($journeyDays >= 90) {
    $milestones[] = '90 Day Journey';
}

@endphp

@if(count($milestones))

<div class="theme-card theme-radius theme-card-padding-lg theme-shadow mb-12">

    <h2 class="text-3xl font-black mb-8">
        Achievements & Milestones
    </h2>

    <div class="flex flex-wrap gap-4">

        @foreach($milestones as $milestone)

            <div class="px-5 py-3 theme-surface-muted border theme-border theme-radius font-semibold">

                <svg class="inline-block w-5 h-5 me-2 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15l-3.5 2 1-4-3-2.5 4.1-.3L12 6.5l1.4 3.7 4.1.3-3 2.5 1 4z" /></svg>
                {{ $milestone }}

            </div>

        @endforeach

    </div>

</div>

@endif

        <div class="space-y-10">

            @foreach($logs as $log)

                <div class="theme-card theme-radius theme-card-padding theme-shadow">

                    <div class="flex justify-between items-center mb-8">

                        <h2 class="text-3xl font-black">

                            Check-In #{{ $loop->iteration }}

                        </h2>

                        <span class="theme-text-neutral">

                            {{ $log->created_at->format('d M Y') }}

                        </span>

                    </div>

                    <div class="grid md:grid-cols-3 theme-content-gap mb-8">

                        @if($log->front_photo)

                            <img
                                src="{{ asset('storage/'.$log->front_photo) }}"
                                class="theme-radius theme-shadow w-full">

                        @endif

                        @if($log->side_photo)

                            <img
                                src="{{ asset('storage/'.$log->side_photo) }}"
                                class="theme-radius theme-shadow w-full">

                        @endif

                        @if($log->back_photo)

                            <img
                                src="{{ asset('storage/'.$log->back_photo) }}"
                                class="theme-radius theme-shadow w-full">

                        @endif

                    </div>

                    <div class="grid md:grid-cols-4 theme-content-gap">

                        <div>
                            <strong>Weight</strong>
                            <br>
                            {{ $log->weight }} kg
                        </div>

                        <div>
                            <strong>BMI</strong>
                            <br>
                            {{ $log->bmi }}
                        </div>

                        <div>
                            <strong>Body Fat</strong>
                            <br>
                            {{ $log->body_fat }}%
                        </div>

                        <div>
                            <strong>Waist</strong>
                            <br>
                            {{ $log->waist }}
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endsection
