@section('meta_title', $service->title . ' | ' . $setting->site_name)

@section('meta_description', Str::limit(strip_tags($service->description), 150))

@section('meta_keywords', $service->title . ', fitness coaching, ' . $setting->site_name)

@extends('layouts.app')

@section('content')

<section class="relative py-24 bg-[#f6f3eb] overflow-hidden min-h-screen">

    <!-- BACKGROUND EFFECT -->

    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-yellow-200 opacity-20 blur-3xl rounded-full"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">

        <div class="grid lg:grid-cols-2 gap-20 items-center">

            <!-- IMAGE -->

            <div data-aos="fade-right">

                @if($service->image)

                    <div class="overflow-hidden rounded-[40px] shadow-2xl">

                        <img
                            src="{{ asset('storage/' . $service->image) }}"
                            alt="{{ $service->title }}"
                            class="w-full h-[700px] object-cover hover:scale-105 transition duration-700"
                        >

                    </div>

                @else

                    <div class="w-full h-[700px] rounded-[40px] bg-white flex items-center justify-center shadow-2xl">

                        <span class="text-8xl">🔥</span>

                    </div>

                @endif

            </div>

            <!-- CONTENT -->

            <div data-aos="fade-left">

                <!-- BADGE -->

                <div class="inline-flex items-center gap-3 bg-yellow-500 text-black px-6 py-3 rounded-full font-bold text-sm uppercase tracking-[2px] mb-8 shadow-lg">

                    Premium Fitness Service

                </div>

                <!-- TITLE -->

                <h1 class="text-5xl md:text-7xl font-black text-[#111111] leading-tight mb-8">

                    {{ $service->title }}

                </h1>

                <!-- PRICE -->

                <div class="flex items-end gap-4 mb-10">

                    <span class="text-6xl font-black text-yellow-500">

                        ₹{{ number_format($service->price) }}

                    </span>

                    @if($service->duration)

                    <span class="text-2xl text-gray-500 mb-2">

                        /{{ $service->duration }}

                    </span>

                    @endif

                </div>

                <!-- DESCRIPTION -->

                <p class="text-xl text-gray-600 leading-[42px] mb-12">

                    {{ $service->description }}

                </p>

                <!-- FEATURES -->

                @if($service->features)

                <div class="grid sm:grid-cols-2 gap-5 mb-12">

                    @foreach((is_array($service->features) ? $service->features : json_decode($service->features ?? '[]', true)) as $feature)

                    <div class="flex items-start gap-4 bg-white rounded-2xl p-5 shadow-lg hover:-translate-y-1 hover:shadow-xl transition duration-300">

                        <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center text-black font-black text-sm mt-1">âœ“</div>

                        <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center text-black font-black text-sm mt-1">✓</div>

                            {{ is_array($feature) ? ($feature["feature"] ?? "") : $feature }}

                        </span>

                    </div>

                    @endforeach

                </div>

                @endif

                <!-- BUTTONS -->

                <div class="flex flex-wrap gap-5 mb-14">

                    <a href="/contact"
                       class="bg-yellow-500 hover:bg-yellow-400 hover:scale-105 text-black font-black px-10 py-5 rounded-2xl transition duration-300 shadow-2xl">

                        {{ $service->button_text ?? 'Get Started' }}

                    </a>

                    <a href="/plans"
                       class="border-2 border-black hover:bg-black hover:text-white text-black font-black px-10 py-5 rounded-2xl transition duration-300">

                        {{ $setting->view_programs_text }}

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


<!-- CTA SECTION -->

<section class="py-24 bg-[#111111] relative overflow-hidden">

    <div class="absolute top-0 left-0 w-full h-full opacity-10">

        <div class="absolute top-10 left-10 w-72 h-72 bg-yellow-500 rounded-full blur-3xl"></div>

        <div class="absolute bottom-10 right-10 w-72 h-72 bg-yellow-500 rounded-full blur-3xl"></div>

    </div>

    <div class="max-w-5xl mx-auto px-6 relative z-10 text-center">

        <p class="text-yellow-500 uppercase tracking-[4px] font-bold mb-6">

            Start Your Transformation

        </p>

        <h2 class="text-5xl md:text-6xl font-black text-white leading-tight mb-8">

            Ready To Achieve
            <br>
            Real Fitness Results?

        </h2>

        <p class="text-xl text-gray-400 leading-relaxed mb-12 max-w-3xl mx-auto">

            Join {{ $setting->site_name }} programs and get expert coaching,
            structured guidance, and a transformation-focused system
            designed for sustainable results.

        </p>

        <div class="flex flex-wrap justify-center gap-5">

            <a href="/plans"
               class="bg-yellow-500 hover:bg-yellow-400 hover:scale-105 text-black font-black px-10 py-5 rounded-2xl transition duration-300 shadow-2xl">

                Book Consultation

            </a>

            <a href="/transformations"
               class="border border-white text-white hover:bg-white hover:text-black hover:scale-105 px-10 py-5 rounded-2xl font-black transition duration-300">

                {{ $setting->view_transformations_text }}

            </a>

        </div>

    </div>

</section>

@endsection
