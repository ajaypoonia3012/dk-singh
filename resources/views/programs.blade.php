@extends('layouts.app')

@section('content')

<section class="py-20 bg-[#f8f6f1] min-h-screen">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-16">

            <p class="uppercase tracking-[4px] text-yellow-500 font-semibold mb-4">
                {{ $setting->site_name }}
            </p>

            <h1 class="text-5xl font-black text-black mb-6">
                Fitness Programs
            </h1>

            <p class="text-gray-600 text-lg">
                Choose the right transformation plan for your fitness journey.
            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach($programs as $program)

                <div class="bg-white rounded-[30px] overflow-hidden shadow-sm">

                    <img
                        src="{{ asset('storage/' . $program->image) }}"
                        alt="{{ $program->title }}"
                        class="w-full h-72 object-cover"
                    >

                    <div class="p-8">

                        <div class="flex items-center justify-between mb-4">

                            <span class="text-sm font-semibold text-yellow-500 uppercase">
                                {{ $program->category }}
                            </span>

                            <span class="text-sm text-gray-500">
                                {{ $program->duration }}
                            </span>

                        </div>

                        <h2 class="text-2xl font-bold mb-4">
                            {{ $program->title }}
                        </h2>

                        <p class="text-gray-600 mb-6">
                            {{ $program->description }}
                        </p>

                        <div class="flex items-center justify-between">

                            <span class="text-2xl font-black text-black">
                                ₹{{ $program->price }}
                            </span>

                            <button class="bg-yellow-500 text-black px-6 py-3 rounded-xl font-semibold">
                                Join Now
                            </button>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endsection