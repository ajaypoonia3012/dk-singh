@extends('layouts.app')
@section('title', $workout->title . ' Workout Routine | DK Singh Fitness')
@section('meta_description', Str::limit(strip_tags($workout->description ?? $workout->content ?? 'Complete workout breakdown and routine details for ' . $workout->title), 155))

@section('content')

<section class="theme-surface-muted theme-section min-h-screen">

<div class="theme-page-container">

<div class="grid lg:grid-cols-3 theme-grid-gap">

<div class="lg:col-span-2">

@if($workout->thumbnail)

<img
src="{{ asset('storage/'.$workout->thumbnail) }}"
alt="{{ $workout->title }}"
class="w-full theme-radius mb-8">

@endif

<h1 class="text-5xl font-black mb-6">
{{ $workout->title }}
</h1>

<div class="mb-6">

<span class="theme-status-warning theme-text-secondary px-4 py-2 rounded-full font-bold">
{{ $workout->difficulty }}
</span>

</div>

@if($workout->video_url)

<div class="aspect-video mb-10">

<iframe
src="{{ str_replace('watch?v=','embed/',$workout->video_url) }}"
class="w-full h-full theme-radius"
allowfullscreen>
</iframe>

</div>

@endif

<div class="theme-card theme-radius theme-card-padding-lg theme-shadow">

{!! $workout->content !!}

</div>

</div>

<div>

<div class="theme-card theme-radius theme-card-padding theme-shadow sticky top-32">

<span class="theme-text-primary uppercase tracking-[2px] font-bold text-xs mb-2 block">
ACCELERATE RESULTS
</span>

<h3 class="text-2xl font-black mb-3">
Ready for a Complete Transformation?
</h3>

<p class="theme-text-neutral text-sm mb-6 leading-relaxed">
This free routine is a great foundation. For a periodized 12-week training split, custom Indian calorie/macro targets, and weekly accountability with Coach DK Singh, explore the full program.
</p>

<a href="{{ route('programs.show', '12-week-fat-loss-transformation') }}"
class="block theme-status-warning theme-text-secondary text-center py-3.5 theme-radius font-black mb-3 text-sm">
Explore 12-Week Fat Loss &rarr;
</a>

<a href="{{ route('contact', ['service' => 'personal-online-coaching']) }}"
class="block theme-surface-strong theme-text-on-strong text-center py-3.5 theme-radius font-bold mb-3 text-sm">
Apply for 1-on-1 Coaching
</a>

<a href="{{ route('fitness.exercise-library') }}"
class="block border theme-border text-center py-3 theme-radius font-semibold text-sm theme-text-neutral hover:theme-text-primary transition">
Browse Exercise Library (25+)
</a>

</div>

</div>

</div>

</div>

</section>

@endsection
