@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb]">

    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADING -->

        <div class="text-center mb-20">

            <p class="text-yellow-500 font-bold uppercase tracking-[4px] mb-4">
               {{ $setting->services_page_label ?? 'Our Services' }}
            </p>

            <h1 class="text-5xl md:text-7xl font-black text-[#111111] mb-8">
                {{ $setting->service_label }}
            </h1>

            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                {{ $setting->services_page_description ?? 'Premium fitness coaching and transformation services designed to help you achieve real results.' }}
            </p>

        </div>

        <!-- SERVICES GRID -->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

            @foreach($services as $service)

            <div class="bg-white rounded-[35px] overflow-hidden shadow-xl hover:-translate-y-3 hover:shadow-2xl transition duration-500">

                @if($service->image)

                <img
                    src="{{ asset('storage/' . $service->image) }}"
                    alt="{{ $service->title }}"
                    class="w-full h-[320px] object-cover"
                >

                @endif

                <div class="p-8">

                    <h2 class="text-3xl font-black text-[#111111] mb-4">

                        {{ $service->title }}

                    </h2>

                    <p class="text-gray-600 leading-relaxed mb-6">

                        {{ Str::limit($service->description, 100) }}

                    </p>

                    @if($service->price)

                    <div class="mb-6">

                        <span class="text-3xl font-black text-yellow-500">

                            ₹{{ number_format($service->price) }}

                        </span>

                    </div>

                    @endif

                    <a href="{{ route('services.show', $service->slug) }}"
                       class="block text-center bg-yellow-500 hover:bg-yellow-400 text-black font-black py-4 rounded-2xl transition duration-300">

                        View Service

                    </a>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>

@endsection