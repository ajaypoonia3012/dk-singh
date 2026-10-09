@extends('layouts.app')

@section('title', 'Cardio, Walking & Conditioning Guides | DK Singh Fitness')
@section('meta_description', 'Discover evidence-based cardiovascular protocols, zone 2 conditioning, walking habits, and HIIT routines curated by DK Singh Fitness.')

@section('content')
<div class="container py-4 py-lg-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fitness.index') }}" class="text-decoration-none">Fitness</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cardio</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-5">
        <span class="badge bg-danger text-white px-3 py-2 fw-bold text-uppercase mb-2">Cardiovascular Health</span>
        <h1 class="display-4 fw-bold mb-2">Cardio, Walking & Fat-Loss Protocols</h1>
        <p class="lead text-muted" style="max-width: 720px;">
            Evidence-based cardiovascular training strategies. Learn how walking, running, HIIT, and low-impact conditioning accelerate fat loss while protecting joint health and muscle mass.
        </p>

        {{-- Subtopics Pill List --}}
        <div class="d-flex flex-wrap gap-2 pt-2">
            @foreach($subtopics as $topic)
                <span class="badge bg-light text-dark border px-3 py-2">
                    <i class="bi bi-check2-circle text-danger me-1"></i> {{ $topic }}
                </span>
            @endforeach
        </div>
    </div>

    {{-- Conditioning Exercises Strip --}}
    @if($exercises->count() > 0)
    <section class="mb-5 p-4 rounded-4 bg-light border">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><i class="bi bi-fire text-danger me-2"></i>Calorie-Torching Exercises</h4>
            <a href="{{ route('fitness.exercise-library', ['category' => 'Cardio']) }}" class="text-dark small fw-bold text-decoration-none">
                View Cardio Movements &rarr;
            </a>
        </div>
        <div class="row g-3">
            @foreach($exercises as $ex)
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('fitness.exercise-detail', $ex->slug) }}" class="card h-100 border text-decoration-none rounded-3 overflow-hidden bg-white hover-shadow transition-card">
                        <div style="height: 130px; overflow: hidden;">
                            <img src="{{ $ex->image_url }}" alt="{{ $ex->name }}" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="p-3">
                            <span class="badge bg-danger text-white small mb-1">{{ $ex->difficulty }}</span>
                            <h6 class="fw-bold text-dark mb-1">{{ $ex->name }}</h6>
                            <p class="text-muted small mb-0">{{ Str::limit($ex->short_description, 60) }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Articles Grid --}}
    <section class="mb-5">
        <h3 class="fw-bold mb-4">Latest Cardio & Fat Loss Articles</h3>
        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                        <div style="height: 180px; overflow: hidden;">
                            @if($post->media)
                                <img src="{{ asset('storage/' . $post->media->path) }}" alt="{{ $post->media->alt ?: $post->title }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <img src="{{ asset('images/dk-hero.jpg') }}" alt="{{ $post->title }}" class="w-100 h-100" style="object-fit: cover;">
                            @endif
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-warning text-dark small">{{ $post->category ? $post->category->name : 'Cardio' }}</span>
                                <span class="badge bg-light text-muted small border">{{ $post->type_label }}</span>
                            </div>
                            <h5 class="fw-bold mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            <p class="text-muted small mb-3 flex-grow-1">
                                {{ Str::limit($post->excerpt, 95) }}
                            </p>
                            <div class="text-muted small d-flex justify-content-between align-items-center pt-2 border-top">
                                <span>{{ $post->reading_time }} min read</span>
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark fw-bold text-decoration-none">
                                    Read Article &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </section>

    {{-- CTA --}}
    <div class="card bg-dark text-white p-4 p-md-5 rounded-4 my-5 border-0 shadow">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold text-white mb-2">Struggling to Lose Stubborn Body Fat?</h3>
                <p class="text-white-50 mb-3">Learn how to combine daily step counts, strategic cardio sessions, and Indian meal plans for lasting fat loss without extreme burnout.</p>
                <a href="/programs" class="btn btn-warning fw-bold px-4">Explore Fat Loss Programs</a>
            </div>
        </div>
    </div>

</div>
@endsection
