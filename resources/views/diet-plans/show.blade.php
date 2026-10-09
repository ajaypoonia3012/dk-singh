@extends('layouts.app')
@section('title', $dietPlan->title . ' Nutrition Guide | DK Singh Fitness')
@section('meta_description', Str::limit(strip_tags($dietPlan->content ?? 'Comprehensive nutrition protocol and meal strategy for ' . $dietPlan->title), 155))

@section('content')

<section class="theme-surface-muted theme-section min-h-screen">

    <div class="theme-page-container">

        <div class="mb-16 text-center">

            <p class="uppercase tracking-[3px] theme-text-primary font-bold mb-4">
                Premium Nutrition Plan
            </p>

            <h1 class="text-5xl font-black theme-text-secondary mb-6">
                {{ $dietPlan->title }}
            </h1>

            <span class="theme-status-warning theme-text-secondary px-5 py-2 rounded-full font-black">

                {{ $dietPlan->goal }}

            </span>

        </div>

        <div class="theme-card border theme-border theme-radius p-12 theme-shadow">

            <div class="prose prose-lg max-w-none theme-text-neutral leading-9">

                {!! $dietPlan->content !!}

            </div>

        </div>

        @if(session('success'))

<div class="theme-status-success theme-text-success p-4 theme-radius mb-6">

    {{ session('success') }}

</div>

@endif

<div class="mt-16 text-center">

    @auth

    <form method="POST"
          action="{{ route('diet.complete', $dietPlan->id) }}">

        @csrf

        <button
            class="theme-status-success hover:theme-status-success theme-text-on-strong px-10 py-5 theme-radius font-black">

            Mark Diet Completed

        </button>

    </form>

    <div class="mt-6">

        <a
            href="/contact"
            class="theme-status-warning hover:theme-status-warning theme-text-secondary px-10 py-5 theme-radius font-black inline-block">

            Get Personalized Diet Plan

        </a>

    </div>

    @else

    <a
        href="/login"
        class="theme-status-warning theme-text-secondary px-10 py-5 theme-radius font-black">

        Login To Continue

    </a>

    @endauth

</div>

    </div>

</section>

@endsection
