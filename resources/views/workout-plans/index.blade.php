@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb] min-h-screen">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-20">

            <p class="text-yellow-500 uppercase tracking-[3px] font-bold mb-4">
                Premium Fitness
            </p>

            <h1 class="text-5xl font-black text-black mb-6">
                Workout Plans
            </h1>

            <p class="text-gray-600 text-xl max-w-3xl mx-auto">
                Scientifically designed workout programs for fat loss,
                muscle gain, strength, and body transformation.
            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

            @foreach($workouts as $workout)

                <a
                    href="{{ route('workout-plans.show', $workout->id) }}"
                    class="bg-white border border-gray-200 rounded-3xl p-8 hover:border-yellow-500 hover:shadow-2xl transition block">

                    <div class="mb-6">

                        <span class="bg-yellow-500 text-black px-4 py-2 rounded-full text-sm font-black">

                            {{ $workout->difficulty }}

                        </span>

                    </div>

                    <h3 class="text-3xl font-black text-black mb-4">
                        {{ $workout->title }}
                    </h3>

                    <p class="text-gray-600 leading-8">
                        {{ $workout->description }}
                    </p>

                </a>

            @endforeach

        </div>

    </div>

</section>

@endsection