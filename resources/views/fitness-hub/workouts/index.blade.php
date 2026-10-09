@extends('layouts.app')
@section('title', 'Free Workout Plans & Routines | DK Singh Fitness')
@section('meta_description', 'Explore free structured workout routines for strength, fat loss, and muscle hypertrophy designed by DK Singh.')

@section('content')

<section class="theme-section theme-surface-muted min-h-screen">

<div class="theme-page-container">

<div class="text-center mb-20">

<p class="theme-text-primary uppercase tracking-[4px] font-bold mb-4">
FREE WORKOUTS
</p>

<h1 class="text-6xl font-black theme-text-secondary mb-6">
Workout Library
</h1>

<p class="text-xl theme-text-neutral max-w-3xl mx-auto">
Free workout plans designed by {{ $setting?->site_name ?: config('app.name') }}.
</p>

</div>

<div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">

@foreach($workouts as $workout)

<a href="{{ route('fitness-hub.workouts.show',$workout->slug) }}"
class="theme-card theme-radius overflow-hidden theme-shadow hover:theme-shadow transition block">

@if($workout->thumbnail)

<img
src="{{ asset('storage/'.$workout->thumbnail) }}"
alt="{{ $workout->title }}"
class="w-full h-64 object-cover">

@endif

<div class="theme-card-padding">

<p class="theme-text-primary font-bold mb-3">
{{ $workout->difficulty }}
</p>

<h2 class="text-3xl font-black mb-4">
{{ $workout->title }}
</h2>

<p class="theme-text-neutral">
{{ Str::limit($workout->description,120) }}
</p>

</div>

</a>

@endforeach

</div>

</div>

</section>

@endsection
