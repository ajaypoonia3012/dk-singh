@extends('layouts.app')
@section('title', 'Custom Nutrition & Diet Plans | DK Singh Fitness')
@section('meta_description', 'Customized nutrition and meal plans designed for fat loss, muscle gain, athletic performance, and sustainable lifestyle.')

@section('content')

<section class="theme-section theme-surface-muted min-h-screen">

    <div class="theme-page-container">

        <div class="text-center mb-20">

            <p class="theme-text-primary uppercase tracking-[3px] font-bold mb-4">
                Nutrition Plans
            </p>

            <h1 class="text-5xl font-black theme-text-secondary mb-6">
                Diet Plans
            </h1>

            <p class="text-xl theme-text-neutral max-w-3xl mx-auto">
                Customized meal plans designed for fat loss,
                muscle gain, performance, and healthy lifestyle.
            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">

            @foreach($dietPlans as $dietPlan)

                <a
                    href="{{ route('diet-plans.show', $dietPlan->id) }}"
                    class="theme-card theme-radius theme-shadow hover:theme-shadow hover:-translate-y-2 transition theme-card-padding block">

                    <div class="mb-5">

                        <span class="theme-status-warning theme-text-secondary px-4 py-2 rounded-full text-sm font-black">

                            {{ $dietPlan->goal }}

                        </span>

                    </div>

                    <h3 class="text-3xl font-black theme-text-secondary mb-4">
                        {{ $dietPlan->title }}
                    </h3>

                    <p class="theme-text-neutral leading-8">
                        {{ $dietPlan->description }}
                    </p>

                </a>

            @endforeach

        </div>

    </div>

</section>

@endsection
