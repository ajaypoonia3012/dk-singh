@extends('layouts.app')

@section('title', ($q ? 'Search results for "' . $q . '"' : 'Search Fitness & Exercises') . ' | DK Singh Fitness')
@section('meta_description', 'Search the DK Singh Fitness library for exercises, workout programs, nutritional advice, and holistic recovery guides.')

@push('meta')
    <meta name="robots" content="noindex, follow">
@endpush

@section('content')
<div class="container py-4 py-lg-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fitness.index') }}" class="text-decoration-none">Fitness</a></li>
            <li class="breadcrumb-item active" aria-current="page">Search</li>
        </ol>
    </nav>

    {{-- Search Bar Header --}}
    <div class="mb-5">
        <h1 class="display-5 fw-bold mb-3">Fitness & Exercise Search</h1>
        <form method="GET" action="{{ route('fitness.search') }}" class="d-flex gap-2" style="max-width: 680px;">
            <div class="input-group input-group-lg shadow-sm">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-primary"></i></span>
                <input type="text" name="q" class="form-control border-start-0" placeholder="Search articles, guides, exercises, or muscles..." value="{{ $q }}" autofocus>
                <button type="submit" class="btn btn-primary px-4 fw-bold">Search</button>
            </div>
        </form>

        {{-- Popular Query Suggestions --}}
        <div class="d-flex flex-wrap gap-2 align-items-center mt-3">
            <span class="small text-muted fw-bold text-uppercase">Popular Searches:</span>
            @foreach(['push ups', 'weight loss', 'protein', 'home workout', 'cardio', 'yoga', 'chest workout'] as $suggestion)
                <a href="{{ route('fitness.search', ['q' => $suggestion]) }}" class="badge bg-light text-dark border text-decoration-none py-2 px-3 hover-shadow">
                    {{ $suggestion }}
                </a>
            @endforeach
        </div>
    </div>

    @if($q !== '')
        <div class="mb-4 pb-2 border-bottom d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">Results for <span class="text-primary">"{{ $q }}"</span></h4>
            <span class="text-muted small">
                {{ $exercises->count() }} {{ Str::plural('exercise', $exercises->count()) }} &bull;
                {{ $articles->count() }} {{ Str::plural('article', $articles->count()) }}
            </span>
        </div>

        {{-- Matched Exercises --}}
        @if($exercises->count() > 0)
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-shield-shaded text-primary me-2"></i>Matching Exercises ({{ $exercises->count() }})</h5>
                <a href="{{ route('fitness.exercise-library', ['search' => $q]) }}" class="small text-decoration-none fw-bold">
                    View in Exercise Library &rarr;
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
                                <span class="badge bg-primary text-white small mb-1">{{ $ex->primary_muscle }}</span>
                                <h6 class="fw-bold text-dark mb-1">{{ $ex->name }}</h6>
                                <p class="text-muted small mb-0">{{ Str::limit($ex->short_description, 60) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Matched Articles --}}
        @if($articles->count() > 0)
        <section class="mb-5">
            <h5 class="fw-bold mb-3"><i class="bi bi-journal-text text-primary me-2"></i>Matching Articles & Guides ({{ $articles->count() }})</h5>
            <div class="row g-4">
                @foreach($articles as $post)
                    <div class="col-md-6 col-lg-4">
                        <article class="card h-100 border rounded-4 overflow-hidden bg-white hover-shadow transition-card">
                            <div style="height: 180px; overflow: hidden;" class="position-relative">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-100 h-100" style="object-fit: cover;">
                                @if($post->category)
                                    <span class="position-absolute top-0 start-0 m-3 badge bg-dark bg-opacity-75 text-white">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <span class="text-muted small mb-1">{{ $post->reading_time ?? 5 }} min read</span>
                                <h5 class="fw-bold mb-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none">
                                        {{ $post->title }}
                                    </a>
                                </h5>
                                <p class="text-muted small mb-3 flex-grow-1">
                                    {{ Str::limit(strip_tags($post->excerpt ?? $post->content), 95) }}
                                </p>
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-primary small fw-bold text-decoration-none align-self-start">
                                    Read Article &rarr;
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- No results state --}}
        @if($exercises->count() === 0 && $articles->count() === 0)
            <div class="text-center py-5 bg-light rounded-4">
                <i class="bi bi-emoji-neutral fs-1 text-muted"></i>
                <h4 class="fw-bold mt-3">No matching articles or exercises found</h4>
                <p class="text-muted" style="max-width: 500px; margin: 0 auto;">
                    We couldn't find anything matching "{{ $q }}". Try checking your spelling, using broader terms, or explore our curated sections.
                </p>
                <div class="d-flex justify-content-center gap-2 mt-4">
                    <a href="{{ route('fitness.index') }}" class="btn btn-primary rounded-pill px-4">Fitness Hub</a>
                    <a href="{{ route('fitness.exercise-library') }}" class="btn btn-outline-secondary rounded-pill px-4">Exercise Library</a>
                </div>
            </div>
        @endif

    @else
        {{-- Empty Search Prompt --}}
        <div class="text-center py-5 bg-light rounded-4">
            <i class="bi bi-search fs-1 text-primary"></i>
            <h4 class="fw-bold mt-3">Discover Practical Fitness Wisdom</h4>
            <p class="text-muted" style="max-width: 550px; margin: 0 auto;">
                Search across our 249+ evidence-based fitness articles and 25+ biomechanically vetted exercise guides.
            </p>
        </div>
    @endif

</div>
@endsection
