@extends('layouts.app')
@section('title', 'Free Diet & Nutrition Plans | DK Singh Fitness')
@section('meta_description', 'Science-based free meal plans and nutrition guidelines tailored for fat loss, muscle building, and peak energy.')

@section('content')

<section class="theme-section theme-surface-muted min-h-screen">

<div class="theme-page-container">

<div class="text-center mb-20">

<p class="theme-text-primary uppercase tracking-[4px] font-bold mb-4">
FREE DIET PLANS
</p>

<h1 class="text-6xl font-black theme-text-secondary mb-6">
Diet Library
</h1>

<p class="text-xl theme-text-neutral">
Free nutrition plans from {{ $setting?->site_name ?: config('app.name') }}.
</p>

</div>

<div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">

@foreach($diets as $diet)

<a href="{{ route('fitness-hub.diets.show',$diet->slug) }}"
class="theme-card theme-radius theme-shadow theme-card-padding block hover:theme-shadow">

<h2 class="text-3xl font-black mb-4">
{{ $diet->title }}
</h2>

<p class="theme-text-primary font-bold mb-4">
{{ $diet->goal }}
</p>

<p class="theme-text-neutral">
{{ Str::limit($diet->description,120) }}
</p>

</a>

@endforeach

</div>

</div>

</section>

@endsection
