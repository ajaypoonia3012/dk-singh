@extends('layouts.app')

@section('content')

<section class="bg-[#f6f3eb] py-24 min-h-screen">

    <div class="max-w-5xl mx-auto px-6">

        <div class="mb-16 text-center">

            <p class="uppercase tracking-[3px] text-yellow-500 font-bold mb-4">
                Premium Nutrition Plan
            </p>

            <h1 class="text-5xl font-black text-black mb-6">
                {{ $dietPlan->title }}
            </h1>

            <span class="bg-yellow-500 text-black px-5 py-2 rounded-full font-black">

                {{ $dietPlan->goal }}

            </span>

        </div>

        <div class="bg-white border border-gray-200 rounded-[36px] p-12 shadow-2xl">

            <div class="prose prose-lg max-w-none text-gray-700 leading-9">

                {!! $dietPlan->content !!}

            </div>

        </div>

        @if(session('success'))

<div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6">

    {{ session('success') }}

</div>

@endif

<div class="mt-16 text-center">

    @auth

    <form method="POST"
          action="{{ route('diet.complete', $dietPlan->id) }}">

        @csrf

        <button
            class="bg-green-600 hover:bg-green-500 text-white px-10 py-5 rounded-2xl font-black">

            Mark Diet Completed

        </button>

    </form>

    <div class="mt-6">

        <a
            href="/contact"
            class="bg-yellow-500 hover:bg-yellow-400 text-black px-10 py-5 rounded-2xl font-black inline-block">

            Get Personalized Diet Plan

        </a>

    </div>

    @else

    <a
        href="/login"
        class="bg-yellow-500 text-black px-10 py-5 rounded-2xl font-black">

        Login To Continue

    </a>

    @endauth

</div>

    </div>

</section>

@endsection