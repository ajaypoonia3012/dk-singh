@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb] min-h-screen">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-16">

            <p class="text-yellow-500 font-bold uppercase tracking-[3px] mb-4">
               {{ $setting->transformations_page_label ?? 'Real Client Results' }}
            </p>

            <h1 class="text-5xl font-black text-black mb-6">
               {{ $setting->transformations_page_title ?? 'Body Transformations' }}
            </h1>

            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ $setting->transformations_page_description ?? 'Real transformations achieved through discipline, coaching, and customized fitness programs.' }}
            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

            @forelse($transformations as $transformation)

                <a
                    href="{{ route('transformations.show', $transformation->id) }}"
                    data-aos="zoom-in"
                    class="group bg-white rounded-[32px] overflow-hidden shadow-xl hover:shadow-2xl hover:-translate-y-2 transition duration-500 block">

                    <div class="relative overflow-hidden">

                        @if($transformation->image)

                            <img
                                src="{{ asset('storage/' . $transformation->image) }}"
                                class="w-full h-[420px] object-cover group-hover:scale-110 transition duration-700"
                            >

                        @else

                            <div class="w-full h-[420px] bg-gray-200 flex items-center justify-center text-gray-500 font-bold">

                                No Image

                            </div>

                        @endif

                        @if($transformation->goal)

                            <div class="absolute top-5 left-5 bg-yellow-500 text-black px-4 py-2 rounded-full text-sm font-black shadow-lg">

                                {{ $transformation->goal }}

                            </div>

                        @endif

                    </div>

                    <div class="p-8 bg-white">

                        <div class="flex text-yellow-500 text-[20px] mb-5 tracking-[2px]">
                            ★★★★★
                        </div>

                        <h3 class="text-[28px] leading-tight font-black text-[#111111] mb-4">
                            {{ $transformation->name }}
                        </h3>

                        @if($transformation->story)

                            <p class="text-[16px] leading-[32px] text-gray-600 mb-8 font-medium">

                                "{{ Str::limit($transformation->story, 120) }}"

                            </p>

                        @endif

                        <div class="flex items-center justify-between border-t border-gray-200 pt-5">

                            @if($transformation->duration)

                                <span class="text-[14px] font-bold text-gray-500 uppercase tracking-[1px]">
                                    {{ $transformation->duration }}
                                </span>

                            @endif

                            <span class="text-yellow-600 font-black text-[14px] uppercase tracking-[1px]">
                                Verified Client
                            </span>

                        </div>

                    </div>

                </a>

            @empty

                <div class="col-span-3 text-center py-20">

                    <h2 class="text-3xl font-black text-black mb-4">
                        No Transformations Found
                    </h2>

                    <p class="text-gray-600">
                        Transformation stories will appear here soon.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>

@endsection