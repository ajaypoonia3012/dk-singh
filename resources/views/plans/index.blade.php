@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb]">

    <div class="max-w-7xl mx-auto px-6">

        {{-- HEADING --}}
        <div class="text-center mb-20">

            <p class="text-yellow-500 font-bold uppercase tracking-[4px] mb-4">
                Membership Plans
            </p>

            <h1 class="text-5xl md:text-6xl font-black text-[#111111] mb-6">
                Choose Your
                <span class="text-yellow-500">Fitness Plan</span>
            </h1>

            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-[38px]">
                Select the perfect plan designed to help you achieve your
                transformation goals faster and smarter.
            </p>

        </div>

        {{-- PLANS GRID --}}
        <div class="grid lg:grid-cols-3 gap-10">

            @foreach($plans as $plan)

                <div class="relative rounded-[36px] overflow-hidden shadow-xl transition duration-500 hover:-translate-y-3 hover:shadow-2xl

                    {{ $plan->featured
                        ? 'bg-black text-white scale-105 border-4 border-yellow-500'
                        : 'bg-white text-black border border-gray-200'
                    }}
                ">

                    {{-- BADGE --}}
                    @if($plan->badge)

                        <div class="absolute top-0 right-0 bg-yellow-500 text-black px-6 py-2 rounded-bl-3xl font-black text-sm uppercase tracking-[1px] shadow-lg">

                            {{ $plan->badge }}

                        </div>

                    @endif

                    {{-- IMAGE --}}
                    @if($plan->thumbnail)

                        <img
                            src="{{ asset('storage/' . $plan->thumbnail) }}"
                            class="w-full h-[220px] object-cover"
                        >

                    @endif

                    <div class="p-10">

                        {{-- ACCESS TYPE --}}
                        <p class="uppercase tracking-[3px] text-sm font-bold mb-5

                            {{ $plan->featured
                                ? 'text-yellow-400'
                                : 'text-yellow-600'
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
                                ? 'text-gray-300'
                                : 'text-gray-600'
                            }}
                        ">

                            {{ $plan->description }}

                        </p>

                        {{-- PRICING --}}
                        <div class="flex items-end gap-3 mb-10">

                            @if($plan->discount_price)

                                <span class="text-6xl font-black text-yellow-500">

                                    ₹{{ number_format($plan->discount_price) }}

                                </span>

                                <span class="line-through text-xl text-gray-400">

                                    ₹{{ number_format($plan->price) }}

                                </span>

                            @else

                                <span class="text-6xl font-black text-yellow-500">

                                    ₹{{ number_format($plan->price) }}

                                </span>

                            @endif

                            <span class="mb-2 text-lg

                                {{ $plan->featured
                                    ? 'text-gray-300'
                                    : 'text-gray-500'
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

                                        <div class="mt-1 text-yellow-500 text-xl">
                                            ✔
                                        </div>

                                        <span class="text-[16px] leading-[30px]

                                            {{ $plan->featured
                                                ? 'text-gray-200'
                                                : 'text-gray-700'
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


<a href="{{ url('/checkout/'.$plan->id) }}">

    <button class="w-full py-5 rounded-2xl font-black text-lg transition duration-300

        {{ $plan->featured
            ? 'bg-yellow-500 text-black hover:bg-yellow-400'
            : 'border-2 border-black hover:bg-black hover:text-white'
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