@section('meta_title', $blog->title . ' | ' . $setting->site_name)

@section('meta_description', Str::limit(strip_tags($blog->content), 150))

@section('meta_keywords', $blog->title . ', fitness blog, ' . $setting->site_name)
@extends('layouts.app')

@section('content')

<section class="bg-[#f6f3eb] py-24">

    <div class="max-w-5xl mx-auto px-6">

        <!-- IMAGE -->

        <div class="overflow-hidden rounded-[40px] shadow-2xl mb-12">

            <img
                src="{{ $blog->featured_image ? asset('storage/' . $blog->featured_image) : asset('images/blog-placeholder.jpg') }}"
                alt="{{ $blog->title }}"
                class="w-full h-[500px] object-cover hover:scale-105 transition duration-700"
            >

        </div>

        <!-- META -->

        <div class="flex flex-wrap items-center gap-5 mb-8">

            <span class="bg-yellow-500 text-black px-5 py-2 rounded-full font-bold text-sm">
                Fitness & Nutrition
            </span>

            <span class="text-gray-500">
                {{ $blog->created_at->format('d M Y') }}
            </span>

        </div>

        <!-- TITLE -->

        <h1 class="text-5xl lg:text-6xl font-black leading-tight text-[#111111] mb-10">

            {{ $blog->title }}

        </h1>

        <!-- CONTENT -->

        <div class="prose prose-lg max-w-none prose-headings:font-black prose-headings:text-[#111111] prose-a:text-yellow-600 prose-strong:text-black">

            {!! $blog->content !!}

        </div>

    </div>

</section>

<!-- CTA -->

<section class="bg-[#111111] py-24 text-white">

    <div class="max-w-5xl mx-auto px-6 text-center">

        <p class="uppercase tracking-[5px] text-yellow-500 font-bold mb-4">
            Start Your Transformation
        </p>

        <h2 class="text-5xl lg:text-6xl font-black mb-8">
            Ready To Achieve
            Real Fitness Results?
        </h2>

        <p class="text-xl text-gray-300 leading-relaxed mb-10 max-w-3xl mx-auto">
           {{ $setting->contact_page_description ?? 'Join our transformation journey today.' }}
        </p>

        <div class="flex flex-wrap justify-center gap-5">

            <a href="/plans"
               class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold px-10 py-5 rounded-2xl transition duration-300">

               {{ $setting->view_programs_text }}

            </a>

            <a href="/contact"
               class="border border-white hover:bg-white hover:text-black text-white font-bold px-10 py-5 rounded-2xl transition duration-300">

                {{ $setting->contact_cta_text }}

            </a>

        </div>

    </div>

</section>

@endsection