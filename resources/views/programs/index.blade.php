@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb]">

    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADING -->

        <div class="text-center mb-20">

            <p class="text-yellow-500 font-bold uppercase tracking-[4px] mb-4">

                Premium Programs{{ $setting->programs_page_label ?? 'Premium Programs' }}

            </p>

            <h1 class="text-5xl md:text-7xl font-black text-[#111111] mb-8">

                {{ $setting->program_label }}

            </h1>

            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">

                Professionally designed transformation programs for fat loss,
                muscle building, strength, and total fitness improvement.{{ $setting->programs_page_description ?? 'Professionally designed transformation programs for fat loss, muscle building, strength, and total fitness improvement.' }}

            </p>

        </div>

        <!-- PROGRAMS GRID -->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

            @foreach($programs as $program)

            <div class="bg-white rounded-[35px] overflow-hidden shadow-xl hover:-translate-y-3 hover:shadow-2xl transition duration-500">

                <!-- IMAGE -->

                @if($program->image)

                <img
                    src="{{ asset('storage/' . $program->image) }}"
                    alt="{{ $program->title }}"
                    class="w-full h-[320px] object-cover"
                >

                @endif

                <!-- CONTENT -->

                <div class="p-8">

                    <p class="text-yellow-500 font-bold uppercase tracking-[2px] text-sm mb-3">

                        {{ $program->category }}

                    </p>

                    <h2 class="text-3xl font-black text-[#111111] mb-4">

                        {{ $program->title }}

                    </h2>

                    <p class="text-gray-600 leading-relaxed mb-6">

                        {{ Str::limit($program->description, 100) }}

                    </p>

                    <div class="flex justify-between items-center mb-8">

                        <span class="text-gray-500 font-medium">

                            {{ $program->duration }}

                        </span>

                        <span class="text-3xl font-black text-yellow-500">

                            ₹{{ number_format($program->price) }}

                        </span>

                    </div>

                    <a href="{{ route('programs.show', $program->slug) }}"
                       class="block text-center bg-yellow-500 hover:bg-yellow-400 text-black font-black py-4 rounded-2xl transition duration-300">

                        View Program

                    </a>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>

@endsection