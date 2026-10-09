@extends('layouts.app')
@section('title', 'Premium Workout Programs | DK Singh Fitness')
@section('meta_description', 'Scientifically designed workout programs for fat loss, muscle gain, strength, and complete body transformation by DK Singh.')

@section('content')

<section class="theme-section theme-surface-muted min-h-screen">

    <div class="theme-page-container">

        <div class="text-center mb-20">

            <p class="theme-text-primary uppercase tracking-[3px] font-bold mb-4">
                Premium Fitness
            </p>

            <h1 class="text-5xl font-black theme-text-secondary mb-6">
                Workout Plans
            </h1>

            <p class="theme-text-neutral text-xl max-w-3xl mx-auto">
                Scientifically designed workout programs for fat loss,
                muscle gain, strength, and body transformation.
            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">

            @foreach($workouts as $workout)

                <a
                    href="{{ route('workout-plans.show', $workout->id) }}"
                    class="theme-card border theme-border theme-radius theme-card-padding hover:theme-border hover:theme-shadow transition block">

                    <div class="mb-6">

                        <span class="theme-status-warning theme-text-secondary px-4 py-2 rounded-full text-sm font-black">

                            {{ $workout->difficulty }}

                        </span>

                    </div>

                    <h3 class="text-3xl font-black theme-text-secondary mb-4">
                        {{ $workout->title }}
                    </h3>

                    <p class="theme-text-neutral leading-8">
                        {{ $workout->description }}
                    </p>

                </a>

            @endforeach

        </div>

    </div>

</section>

@endsection
