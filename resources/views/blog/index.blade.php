@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb]">

    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADING -->

        <div class="text-center mb-20">

            <p class="text-yellow-500 font-bold uppercase tracking-[4px] mb-4">

                Latest Articles

            </p>

            <h1 class="text-5xl md:text-7xl font-black text-[#111111] mb-8">

                {{ $setting->blog_label }}

            </h1>

            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">

                Expert fitness advice, nutrition tips, workout strategies,
                and transformation guidance from {{ $setting->site_name }}.

            </p>

        </div>

        <!-- BLOG GRID -->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

            @foreach($blogs as $blog)

            <div class="bg-white rounded-[35px] overflow-hidden shadow-xl hover:-translate-y-3 hover:shadow-2xl transition duration-500">

                <!-- IMAGE -->

                @if($blog->image)

                <img
                    src="{{ asset('storage/' . $blog->image) }}"
                    alt="{{ $blog->title }}"
                    class="w-full h-[320px] object-cover"
                >

                @endif

                <!-- CONTENT -->

                <div class="p-8">

                    <h2 class="text-3xl font-black text-[#111111] mb-4">

                        {{ $blog->title }}

                    </h2>

                    <p class="text-gray-600 leading-relaxed mb-8">

                        {{ Str::limit(strip_tags($blog->content), 120) }}

                    </p>

                    <a href="{{ route('blog.show', $blog->slug) }}"
                       class="block text-center bg-yellow-500 hover:bg-yellow-400 text-black font-black py-4 rounded-2xl transition duration-300">

                        Read Article

                    </a>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>

@endsection