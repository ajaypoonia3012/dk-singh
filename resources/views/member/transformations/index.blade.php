@extends('layouts.app')

@section('content')

<section class="min-h-screen bg-[#f6f3eb] py-20">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-16">

            <p class="uppercase tracking-[3px] text-yellow-500 font-bold mb-4">
                My Transformation Journey
            </p>

            <h1 class="text-5xl font-black">
                Progress Timeline
            </h1>

        </div>

        <div class="grid lg:grid-cols-5 gap-6 mb-16">

    {{-- STARTING POINT --}}
    <div class="bg-white p-8 rounded-3xl shadow-xl">

        <p class="uppercase text-xs tracking-[2px] text-gray-500 mb-3">
            Starting Point
        </p>

        <h3 class="text-xl font-black mb-2">
            {{ $oldest?->weight }} kg
        </h3>

        <p class="text-gray-600">
            BMI {{ $oldest?->bmi }}
        </p>

        <p class="text-sm text-gray-400 mt-3">
            {{ $oldest?->created_at?->format('d/m/Y') }}
        </p>

    </div>

    {{-- CURRENT PROGRESS --}}
    <div class="bg-white p-8 rounded-3xl shadow-xl">

        <p class="uppercase text-xs tracking-[2px] text-gray-500 mb-3">
            Current Progress
        </p>

        <h3 class="text-xl font-black mb-2">
            {{ $latest?->weight }} kg
        </h3>

        <p class="text-gray-600">
            BMI {{ $latest?->bmi }}
        </p>

        <p class="text-sm text-gray-400 mt-3">
            {{ $latest?->created_at?->format('d/m/Y') }}
        </p>

    </div>

    {{-- WEIGHT LOST --}}
    <div class="bg-white p-8 rounded-3xl shadow-xl">

        <p class="uppercase text-xs tracking-[2px] text-gray-500 mb-3">
            Weight Lost
        </p>

        <h3 class="text-4xl font-black text-green-600">
            {{ number_format($weightLost,1) }} kg
        </h3>

    </div>

    {{-- BMI IMPROVEMENT --}}
    <div class="bg-white p-8 rounded-3xl shadow-xl">

        <p class="uppercase text-xs tracking-[2px] text-gray-500 mb-3">
            BMI Improvement
        </p>

        <h3 class="text-4xl font-black text-blue-600">
            {{ number_format($bmiImprovement,1) }}
        </h3>

    </div>

    {{-- JOURNEY DURATION --}}
    <div class="bg-white p-8 rounded-3xl shadow-xl">

        <p class="uppercase text-xs tracking-[2px] text-gray-500 mb-3">
            Journey Duration
        </p>

        <h3 class="text-4xl font-black text-yellow-500">

            {{ $oldest && $latest
    ? (int) $oldest->created_at->startOfDay()->diffInDays(
        $latest->created_at->startOfDay()
    )
    : 0 }}

        </h3>

        <p class="text-gray-500 mt-2">
            Days
        </p>

    </div>

</div>

{{-- BEFORE VS CURRENT --}}

@if($logs->count() >= 2)

<div class="bg-white rounded-3xl p-10 shadow-xl mb-12">

    <h2 class="text-4xl font-black mb-8 text-center">
        Before vs Current
    </h2>

    <div class="grid md:grid-cols-2 gap-10">

        <div>

            <h3 class="text-xl font-bold mb-4 text-center">
                Starting Point
            </h3>

            @if($oldest?->front_photo)

                <img
                    src="{{ asset('storage/'.$oldest->front_photo) }}"
                    class="rounded-3xl w-full shadow-xl">

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
                    class="rounded-3xl w-full shadow-xl">

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

<div class="grid md:grid-cols-4 gap-6 mb-12">

    <div class="bg-white p-8 rounded-3xl shadow-xl">

        <p class="uppercase text-xs tracking-[2px] text-gray-500 mb-3">
            Total Check-Ins
        </p>

        <h3 class="text-5xl font-black">
            {{ $logs->count() }}
        </h3>

    </div>

    <div class="bg-white p-8 rounded-3xl shadow-xl">

        <p class="uppercase text-xs tracking-[2px] text-gray-500 mb-3">
            Weight Change
        </p>

        <h3 class="text-5xl font-black text-green-600">
            {{ number_format($weightLost,1) }}
        </h3>

        <p class="text-gray-500 mt-2">
            kg
        </p>

    </div>

    <div class="bg-white p-8 rounded-3xl shadow-xl">

        <p class="uppercase text-xs tracking-[2px] text-gray-500 mb-3">
            BMI Change
        </p>

        <h3 class="text-5xl font-black text-blue-600">
            {{ number_format($bmiImprovement,1) }}
        </h3>

    </div>

    <div class="bg-white p-8 rounded-3xl shadow-xl">

        <p class="uppercase text-xs tracking-[2px] text-gray-500 mb-3">
            Journey Days
        </p>

        <h3 class="text-5xl font-black text-yellow-500">

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
    $milestones[] = '🏆 First Check-In';
}

if ($logs->count() >= 5) {
    $milestones[] = '⭐ 5 Check-Ins Completed';
}

if ($logs->count() >= 10) {
    $milestones[] = '👑 10 Check-Ins Completed';
}

if ($weightLost >= 5) {
    $milestones[] = '💪 5kg Lost';
}

if ($weightLost >= 10) {
    $milestones[] = '🔥 10kg Lost';
}

$journeyDays = $oldest && $latest
    ? (int) $oldest->created_at
        ->startOfDay()
        ->diffInDays(
            $latest->created_at->startOfDay()
        )
    : 0;

if ($journeyDays >= 30) {
    $milestones[] = '🚀 30 Day Journey';
}

if ($journeyDays >= 90) {
    $milestones[] = '🏅 90 Day Journey';
}

@endphp

@if(count($milestones))

<div class="bg-white rounded-3xl p-10 shadow-xl mb-12">

    <h2 class="text-3xl font-black mb-8">
        Achievements & Milestones
    </h2>

    <div class="flex flex-wrap gap-4">

        @foreach($milestones as $milestone)

            <div class="px-5 py-3 bg-yellow-50 border border-yellow-200 rounded-2xl font-semibold">

                {{ $milestone }}

            </div>

        @endforeach

    </div>

</div>

@endif

        <div class="space-y-10">

            @foreach($logs as $log)

                <div class="bg-white rounded-3xl p-8 shadow-xl">

                    <div class="flex justify-between items-center mb-8">

                        <h2 class="text-3xl font-black">

                            Check-In #{{ $loop->iteration }}

                        </h2>

                        <span class="text-gray-500">

                            {{ $log->created_at->format('d M Y') }}

                        </span>

                    </div>

                    <div class="grid md:grid-cols-3 gap-6 mb-8">

                        @if($log->front_photo)

                            <img
                                src="{{ asset('storage/'.$log->front_photo) }}"
                                class="rounded-2xl shadow-lg w-full">

                        @endif

                        @if($log->side_photo)

                            <img
                                src="{{ asset('storage/'.$log->side_photo) }}"
                                class="rounded-2xl shadow-lg w-full">

                        @endif

                        @if($log->back_photo)

                            <img
                                src="{{ asset('storage/'.$log->back_photo) }}"
                                class="rounded-2xl shadow-lg w-full">

                        @endif

                    </div>

                    <div class="grid md:grid-cols-4 gap-6">

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