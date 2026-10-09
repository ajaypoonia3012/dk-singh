@extends('layouts.app')
@section('title', 'Free Fitness & Nutrition Hub | DK Singh Fitness')
@section('meta_description', 'Access free workout routines, diet plans, fitness calculators, and transformation guides from DK Singh Fitness & Nutrition.')

@section('content')

<section class="theme-section theme-surface-muted min-h-screen">

<div class="theme-page-container">

<div class="text-center mb-20">

<p class="theme-text-primary uppercase tracking-[4px] font-bold mb-4">
FITNESS HUB
</p>

<h1 class="text-6xl font-black theme-text-secondary mb-6">
Free Fitness Resources
</h1>

<p class="text-xl theme-text-neutral max-w-3xl mx-auto">
Free workouts, diet plans, articles and transformation stories.
Learn first. Join coaching when you're ready.
</p>

</div>

{{-- WORKOUTS --}}

<div class="mb-24">

<h2 class="text-4xl font-black mb-10">
<svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg> Free Workout Plans
</h2>

<div class="grid md:grid-cols-3 theme-grid-gap">

@foreach($workouts as $workout)

<a href="{{ route('fitness-hub.workouts.show',$workout->slug) }}"
class="theme-card theme-radius overflow-hidden theme-shadow hover:theme-shadow transition block">

@if($workout->thumbnail)

<img
src="{{ asset('storage/'.$workout->thumbnail) }}"
alt="{{ $workout->title }}"
class="w-full h-64 object-cover">

@endif

<div class="p-6">

<h3 class="text-2xl font-black mb-3">
{{ $workout->title }}
</h3>

<p class="theme-text-neutral">
{{ Str::limit($workout->description,120) }}
</p>

</div>

</a>

@endforeach

</div>

<div class="text-center mt-10">

    <a href="{{ route('fitness-hub.workouts.index') }}"
       class="theme-surface-strong theme-text-on-strong px-8 py-4 theme-radius font-bold">

        View All Workouts

    </a>

</div>


</div>

{{-- DIETS --}}

<div class="mb-24">

<h2 class="text-4xl font-black mb-10">
<svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg> Free Diet Plans
</h2>

<div class="grid md:grid-cols-3 theme-grid-gap">

@foreach($diets as $diet)

<a href="{{ route('fitness-hub.diets.show',$diet->slug) }}"
class="theme-card theme-radius theme-card-padding theme-shadow hover:theme-shadow transition block">

<h3 class="text-2xl font-black mb-3">
{{ $diet->title }}
</h3>

<p class="theme-text-neutral">
{{ Str::limit($diet->description,120) }}
</p>

</a>

@endforeach

</div>

<div class="text-center mt-10">

    <a href="{{ route('fitness-hub.diets.index') }}"
       class="theme-surface-strong theme-text-on-strong px-8 py-4 theme-radius font-bold">

        View All Diet Plans

    </a>

</div>



</div>

{{-- BLOGS --}}

<div class="mb-24">

<h2 class="text-4xl font-black mb-10">
<svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg> Fitness Articles
</h2>

<div class="grid md:grid-cols-3 theme-grid-gap">

@foreach($blogs as $blog)

<a href="/blog/{{ $blog->slug }}"
class="theme-card theme-radius theme-card-padding theme-shadow hover:theme-shadow transition block">

<h3 class="text-2xl font-black mb-3">
{{ $blog->title }}
</h3>

</a>

@endforeach

</div>

</div>

{{-- TRANSFORMATIONS --}}

<div>

<h2 class="text-4xl font-black mb-10">
<svg class="inline-block w-5 h-5 theme-text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path stroke-linecap="round" stroke-linejoin="round" d="m8 12 2.5 2.5L16 9" /></svg> Success Stories
</h2>

<div class="grid md:grid-cols-3 theme-grid-gap">

@foreach($transformations as $transformation)

<a href="{{ route('transformations.show', $transformation->slug ?: $transformation->id) }}"
class="theme-card theme-radius overflow-hidden theme-shadow hover:theme-shadow transition block">

@if($transformation->image)

<img
src="{{ asset('storage/'.$transformation->image) }}"
alt="{{ $transformation->name ?? 'Client' }} Transformation"
class="w-full h-64 object-cover">

@endif

<div class="p-6">

<h3 class="text-2xl font-black">
{{ $transformation->name }}
</h3>

</div>

</a>

@endforeach

</div>

</div>

</div>

<div class="mt-24">

    <div class="theme-surface-strong theme-radius p-16 text-center">

        <h2 class="text-5xl font-black theme-text-on-strong mb-6">

            Ready For Faster Results?

        </h2>

        <p class="text-xl theme-text-neutral max-w-3xl mx-auto mb-10">

            Free resources are a great start.
            Get personalized coaching, nutrition guidance,
            and transformation programs from {{ $setting?->site_name ?: config('app.name') }}.

        </p>

        <div class="flex flex-wrap justify-center theme-content-gap">

            <a href="/programs"
               class="theme-status-warning theme-text-secondary px-8 py-4 theme-radius font-black">

                View Programs

            </a>

            <a href="/services"
               class="border theme-border theme-text-on-strong px-8 py-4 theme-radius font-black">

                View Services

            </a>

            <a href="/plans"
               class="border theme-border theme-text-primary px-8 py-4 theme-radius font-black">

                View Membership Plans

            </a>

        </div>

    </div>

</div>


</section>

@endsection
