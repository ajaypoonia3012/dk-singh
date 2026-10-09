@extends('layouts.app')
@section('title', $diet->title . ' Nutrition Plan | DK Singh Fitness')
@section('meta_description', Str::limit(strip_tags($diet->content ?? 'Nutrition guidelines, macronutrient breakdown, and meal schedule for ' . $diet->title), 155))

@section('content')

<section class="theme-surface-muted theme-section min-h-screen">

<div class="theme-page-container">

<div class="grid lg:grid-cols-3 theme-grid-gap">

<div class="lg:col-span-2">

<h1 class="text-5xl font-black mb-6">
{{ $diet->title }}
</h1>

<div class="mb-6">

<span class="theme-status-warning theme-text-secondary px-4 py-2 rounded-full font-bold">
{{ $diet->goal }}
</span>

</div>

<div class="theme-card theme-radius theme-card-padding-lg theme-shadow">

{!! $diet->content !!}

</div>

</div>

<div>

<div class="theme-card theme-radius theme-card-padding theme-shadow sticky top-32">

<span class="theme-text-primary uppercase tracking-[2px] font-bold text-xs mb-2 block">
PERSONALIZED DIET
</span>

<h3 class="text-2xl font-black mb-3">
Need a Custom Indian Meal Plan?
</h3>

<p class="theme-text-neutral text-sm mb-6 leading-relaxed">
Everyone's metabolic rate and food tolerances differ. Get an authentic Indian meal plan tailored to your exact weight, food choices (pure veg, eggetarian, or non-veg), and schedule with Coach DK Singh.
</p>

<a href="{{ route('services.show', 'diet-nutrition-coaching') }}"
class="block theme-status-warning theme-text-secondary text-center py-3.5 theme-radius font-black mb-3 text-sm">
Explore Diet & Nutrition Coaching &rarr;
</a>

<a href="{{ route('contact', ['service' => 'diet-nutrition-coaching']) }}"
class="block theme-surface-strong theme-text-on-strong text-center py-3.5 theme-radius font-bold mb-3 text-sm">
Ask Coach a Nutrition Question
</a>

<a href="{{ route('programs.show', '12-week-fat-loss-transformation') }}"
class="block border theme-border text-center py-3 theme-radius font-semibold text-sm theme-text-neutral hover:theme-text-primary transition">
View 12-Week Transformation Program
</a>

</div>

</div>

</div>

</div>

</section>

@endsection
