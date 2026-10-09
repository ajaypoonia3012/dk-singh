@extends('layouts.app')

@section('content')

<section class="theme-section theme-surface-muted min-h-screen">

    <div class="theme-page-container text-center">

        <p class="uppercase tracking-[3px] theme-text-primary font-bold mb-4">
            Premium Access
        </p>

        <h1 class="text-6xl font-black theme-text-secondary mb-8">
            Premium Diet Plans
        </h1>

        <p class="text-xl theme-text-neutral leading-[40px] max-w-3xl mx-auto mb-16">
            Access advanced nutrition systems, customized
            meal structures, and premium transformation diets.
        </p>

        <a href="/diet-plans"
           class="theme-status-warning hover:theme-status-warning theme-text-secondary px-10 py-5 theme-radius font-black text-lg transition">

            Explore Diet Plans

        </a>

    </div>

</section>

@endsection
