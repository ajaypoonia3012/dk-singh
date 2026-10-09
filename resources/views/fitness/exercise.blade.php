@extends('layouts.app')

@section('title', 'Exercise Guides & Movement Tutorials | DK Singh Fitness')
@section('meta_description', 'Master proper lifting technique, joint mechanics, and safe exercise execution across free weights, machines, and bodyweight movements.')

@section('content')
<div class="container py-4 py-lg-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fitness.index') }}" class="text-decoration-none">Fitness</a></li>
            <li class="breadcrumb-item active" aria-current="page">Exercise</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-2">DK Singh Editorial</span>
                <h1 class="display-4 fw-bold mb-2">Exercise Techniques & Tutorials</h1>
                <p class="lead text-muted" style="max-width: 720px;">
                    Detailed exercise mechanics, form checks, muscle targeting, and progressive variations for gym and home training.
                </p>
            </div>
            <a href="{{ route('fitness.exercise-library') }}" class="btn btn-warning fw-bold px-4 py-2 mt-2 mt-md-0">
                <i class="bi bi-collection-play-fill me-1"></i> Browse Exercise Library &rarr;
            </a>
        </div>

        {{-- Subgroup Quick Filters --}}
        <div class="d-flex flex-wrap gap-2 pt-2">
            @foreach($subgroups as $sg)
                <a href="{{ route('fitness.exercise-library', ['category' => $sg['filter']]) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                    {{ $sg['title'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Featured Exercises Strip --}}
    @if($exercises->count() > 0)
    <section class="mb-5 p-4 rounded-4 bg-light border">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold mb-0">Featured Movement Standards</h3>
            <a href="{{ route('fitness.exercise-library') }}" class="text-dark small fw-bold text-decoration-none">
                See all &rarr;
            </a>
        </div>
        <div class="row g-3">
            @foreach($exercises as $ex)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('fitness.exercise-detail', $ex->slug) }}" class="card h-100 border text-decoration-none rounded-3 overflow-hidden bg-white hover-shadow transition-card">
                        <div style="height: 110px; overflow: hidden;">
                            <img src="{{ $ex->image_url }}" alt="{{ $ex->name }}" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        <div class="p-2 text-center">
                            <span class="badge bg-light text-dark border small mb-1">{{ $ex->primary_muscle }}</span>
                            <h6 class="fw-bold text-dark small mb-0">{{ $ex->name }}</h6>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Exercise Articles Grid --}}
    <section class="mb-5">
        <h3 class="fw-bold mb-4">Latest Exercise & Movement Guides</h3>
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
                                <span class="badge bg-warning text-dark small">{{ $post->category ? $post->category->name : 'Exercise' }}</span>
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
                                    Read Guide &rarr;
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

    {{-- DK Singh CTA --}}
    <div class="card bg-dark text-white p-4 p-md-5 rounded-4 my-5 border-0 shadow">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold text-white mb-2">Want Correct Exercise Form on Every Lift?</h3>
                <p class="text-white-50 mb-3">Join DK Singh coaching for video form analysis, customized training volume, and structured progressive overload tailored to your biomechanics.</p>
                <a href="/programs" class="btn btn-warning fw-bold px-4">Start Coaching Programs</a>
            </div>
        </div>
    </div>

</div>
@endsection
