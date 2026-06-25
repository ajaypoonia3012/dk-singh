@section('meta_title', $program->title . ' | ' . $setting->site_name)

@section('meta_description', Str::limit(strip_tags($program->description), 150))

@section('meta_keywords', $program->title . ', fitness program, workout plan, ' . $setting->site_name)

@extends('layouts.app')

@section('content')

<section class="relative py-24 bg-[#f6f3eb] overflow-hidden min-h-screen">

    <!-- BACKGROUND EFFECT -->

    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-yellow-200 opacity-20 blur-3xl rounded-full"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">

        <div class="grid lg:grid-cols-2 gap-20 items-center">

            <!-- IMAGE -->

            <div data-aos="fade-right">

                @if($program->image)

                    <div class="overflow-hidden rounded-[40px] shadow-2xl">

                        <img
                            src="{{ asset('storage/' . $program->image) }}"
                            alt="{{ $program->title }}"
                            class="w-full h-[700px] object-cover hover:scale-105 transition duration-700"
                        >

                    </div>

                @else

                    <div class="w-full h-[700px] rounded-[40px] bg-white flex items-center justify-center shadow-2xl">

                        <span class="text-8xl">
                            💪
                        </span>

                    </div>

                @endif

            </div>

            <!-- CONTENT -->

            <div data-aos="fade-left">

                <!-- BADGE -->

                <div class="inline-flex items-center gap-3 bg-yellow-500 text-black px-6 py-3 rounded-full font-bold text-sm uppercase tracking-[2px] mb-8 shadow-lg">

                    Premium Fitness Program

                </div>

                <!-- TITLE -->

                <h1 class="text-5xl md:text-7xl font-black text-[#111111] leading-tight mb-8">

                    {{ $program->title }}

                </h1>

                <!-- PRICE -->

                <div class="flex items-end gap-4 mb-10">

                    <span class="text-6xl font-black text-yellow-500">

                        ₹{{ number_format($program->price) }}

                    </span>

                    @if($program->duration)

                    <span class="text-2xl text-gray-500 mb-2">

                        /{{ $program->duration }}

                    </span>

                    @endif

                </div>

                <!-- DESCRIPTION -->

                <p class="text-xl text-gray-600 leading-[42px] mb-12">

                    {{ $program->description }}

                </p>

                <!-- BUTTONS -->

                <div class="flex flex-wrap gap-5 mb-14">

                    <a href="/plans"
                       class="bg-yellow-500 hover:bg-yellow-400 hover:scale-105 text-black font-black px-10 py-5 rounded-2xl transition duration-300 shadow-2xl">

                        Enroll Now

                    </a>

                    <a href="/transformations"
                       class="border-2 border-black hover:bg-black hover:text-white text-black font-black px-10 py-5 rounded-2xl transition duration-300">

                        View Transformations

                    </a>

                </div>

                <!-- TRUST STATS -->

                <div class="grid grid-cols-3 gap-6">

                    <div class="bg-white rounded-2xl p-6 shadow-lg text-center">

                        <h3 class="text-4xl font-black text-yellow-500">
                            15K+
                        </h3>

                        <p class="text-gray-600 mt-2">
                            Transformations
                        </p>

                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg text-center">

                        <h3 class="text-4xl font-black text-yellow-500">
                            15+
                        </h3>

                        <p class="text-gray-600 mt-2">
                            Years Experience
                        </p>

                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg text-center">

                        <h3 class="text-4xl font-black text-yellow-500">
                            24/7
                        </h3>

                        <p class="text-gray-600 mt-2">
                            Support
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection