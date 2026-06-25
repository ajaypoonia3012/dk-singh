@extends('layouts.app')

@section('content')

<!-- HERO -->

<section class="bg-[#f6f3eb] py-24 overflow-hidden">

    <div class="max-w-7xl mx-auto px-6">

        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <!-- IMAGE -->

            <div data-aos="fade-right">

                <img
src="{{ $setting && $setting->about_image
    ? asset('storage/' . $setting->about_image)
    : 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1200&auto=format&fit=crop' }}"
alt="About"
class="w-full h-[650px] object-cover rounded-[40px] shadow-2xl"
>

            </div>

            <!-- CONTENT -->

            <div data-aos="fade-left">

                <p class="uppercase tracking-[5px] text-yellow-500 font-bold mb-4">
                    {{ $setting->about_title ?? 'About DK Singh' }}
                </p>

                <h1 class="text-5xl lg:text-7xl font-black leading-tight text-[#111111] mb-8">
                    Fitness Meets
                    <span class="text-yellow-500">
                        Transformation
                    </span>
                </h1>

  
<p class="text-xl text-gray-600 leading-relaxed mt-8">

    {{ $setting->about_description ?? '' }}

</p>

<p class="text-xl text-gray-600 leading-relaxed mt-6">

    {{ $setting->about_description_2 ?? '' }}

</p>


                <!-- STATS -->

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-5 mb-10">

                    <div class="bg-white rounded-3xl p-6 shadow-lg text-center">

                        <h3 class="text-4xl font-black text-yellow-500 mb-2">
                            3M+
                        </h3>

                        <p class="text-sm text-gray-500">
                            Community
                        </p>

                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-lg text-center">

                        <h3 class="text-4xl font-black text-yellow-500 mb-2">
                            15+
                        </h3>

                        <p class="text-sm text-gray-500">
                            Years Experience
                        </p>

                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-lg text-center">

                        <h3 class="text-4xl font-black text-yellow-500 mb-2">
                            15K+
                        </h3>

                        <p class="text-sm text-gray-500">
                            Transformations
                        </p>

                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-lg text-center">

                        <h3 class="text-4xl font-black text-yellow-500 mb-2">
                            24/7
                        </h3>

                        <p class="text-sm text-gray-500">
                            Support
                        </p>

                    </div>

                </div>

                <!-- BUTTONS -->

                <div class="flex flex-wrap gap-5">

                    <a href="/plans"
                       class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold px-10 py-5 rounded-2xl transition duration-300 shadow-xl">

                        {{ $setting->about_cta_text }}

                    </a>

                    <a href="/contact"
                       class="border-2 border-black hover:bg-black hover:text-white text-black font-bold px-10 py-5 rounded-2xl transition duration-300">

                        {{ $setting->contact_cta_text }}

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- MISSION -->

<section class="bg-white py-24">

    <div class="max-w-6xl mx-auto px-6 text-center">

        <p class="uppercase tracking-[5px] text-yellow-500 font-bold mb-4">
            Our Mission
        </p>

        <h2 class="text-5xl font-black text-[#111111] mb-8">
            Helping People Build
            <span class="text-yellow-500">
                Confidence & Discipline
            </span>
        </h2>

        <p class="max-w-4xl mx-auto text-xl text-gray-600 leading-relaxed">
            Our mission is to help people transform physically and mentally
            through structured fitness programs, proper nutrition guidance,
            mindset coaching, and long-term sustainable lifestyle changes.
        </p>

    </div>

</section>

@endsection