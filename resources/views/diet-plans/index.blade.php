@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f8f5ef] min-h-screen">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-20">

            <p class="text-yellow-500 uppercase tracking-[3px] font-bold mb-4">
                Nutrition Plans
            </p>

            <h1 class="text-5xl font-black text-black mb-6">
                Diet Plans
            </h1>

            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Customized meal plans designed for fat loss,
                muscle gain, performance, and healthy lifestyle.
            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

            @foreach($dietPlans as $dietPlan)

                <a
                    href="{{ route('diet-plans.show', $dietPlan->id) }}"
                    class="bg-white rounded-3xl shadow-xl hover:shadow-2xl hover:-translate-y-2 transition p-8 block">

                    <div class="mb-5">

                        <span class="bg-yellow-500 text-black px-4 py-2 rounded-full text-sm font-black">

                            {{ $dietPlan->goal }}

                        </span>

                    </div>

                    <h3 class="text-3xl font-black text-black mb-4">
                        {{ $dietPlan->title }}
                    </h3>

                    <p class="text-gray-600 leading-8">
                        {{ $dietPlan->description }}
                    </p>

                </a>

            @endforeach

        </div>

    </div>

</section>

@endsection