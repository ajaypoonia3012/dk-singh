@extends('layouts.app')

@section('title', 'Strength Training, Muscle Building & Workout Splits | DK Singh Fitness')
@section('meta_description', 'Science-backed strength training routines, progressive overload strategies, and hypertrophy workout splits designed for lasting physical strength.')

@section('content')
<div class="container py-4 py-lg-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fitness.index') }}" class="text-decoration-none">Fitness</a></li>
            <li class="breadcrumb-item active" aria-current="page">Strength Training</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-5">
        <span class="badge bg-primary text-white px-3 py-2 fw-bold text-uppercase mb-2">Hypertrophy & Strength</span>
        <h1 class="display-4 fw-bold mb-2">Strength Training & Muscle Building Protocols</h1>
        <p class="lead text-muted" style="max-width: 740px;">
            Evidence-backed programming for hypertrophy, strength adaptation, and injury prevention. Master progressive overload, training splits, and biomechanically sound lifting mechanics.
        </p>

        {{-- Core Pillars --}}
        <div class="d-flex flex-wrap gap-2 pt-2">
            @foreach($pillars as $pillar)
                <span class="badge bg-light text-dark border px-3 py-2">
                    <i class="bi bi-lightning-charge-fill text-primary me-1"></i> {{ $pillar }}
                </span>
            @endforeach
        </div>
    </div>

    {{-- Foundational Lifts Strip --}}
    @if($exercises->count() > 0)
    <section class="mb-5 p-4 rounded-4 bg-light border">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0"><i class="bi bi-shield-shaded text-primary me-2"></i>Compound Compound Exercises</h4>
                <p class="text-muted small mb-0">Master these primary multi-joint movements for maximum neuromuscular recruitment.</p>
            </div>
            <a href="{{ route('fitness.exercise-library') }}" class="text-dark small fw-bold text-decoration-none">
                Exercise Library &rarr;
            </a>
        </div>
        <div class="row g-3">
            @foreach($exercises as $ex)
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('fitness.exercise-detail', $ex->slug) }}" class="card h-100 border text-decoration-none rounded-3 overflow-hidden bg-white hover-shadow transition-card">
                        <div style="height: 140px; overflow: hidden;">
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

    {{-- Main Editorial Articles Grid --}}
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Strength & Hypertrophy Articles</h3>
                <p class="text-muted mb-0 small">Scientific training volume, frequency, and rep scheme blueprints.</p>
            </div>
        </div>

        @if($posts->count() > 0)
            <div class="row g-4">
                @foreach($posts as $post)
                    <div class="col-md-6 col-lg-4">
                        <article class="card h-100 border rounded-4 overflow-hidden bg-white hover-shadow transition-card">
                            <div style="height: 200px; overflow: hidden;" class="position-relative">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-100 h-100" style="object-fit: cover;">
                                @if($post->category)
                                    <span class="position-absolute top-0 start-0 m-3 badge bg-dark bg-opacity-75 text-white">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                <span class="position-absolute top-0 end-0 m-3 badge bg-primary text-white">
                                    {{ $post->type_label }}
                                </span>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="text-muted small mb-2 d-flex align-items-center gap-2">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                                    <span>&bull;</span>
                                    <span><i class="bi bi-clock me-1"></i>{{ $post->reading_time ?? 6 }} min read</span>
                                </div>
                                <h5 class="fw-bold mb-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none">
                                        {{ $post->title }}
                                    </a>
                                </h5>
                                <p class="text-muted small mb-4 flex-grow-1">
                                    {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 120) }}
                                </p>
                                <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-dark btn-sm rounded-pill align-self-start fw-bold">
                                    Read Article &rarr;
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-5 bg-light rounded-4">
                <i class="bi bi-journal-text fs-1 text-muted"></i>
                <h5 class="fw-bold mt-3">No strength articles found</h5>
                <p class="text-muted">Check back soon for new lifting guides and workout splits.</p>
            </div>
        @endif
    </section>

    {{-- Workout Splits Comparison Matrix --}}
    <section class="mb-5 p-4 p-lg-5 rounded-4 bg-light border">
        <h3 class="fw-bold mb-3">Popular Training Splits Compared</h3>
        <p class="text-muted mb-4">Choose the structure that matches your weekly availability, recovery tolerance, and training age.</p>

        <div class="table-responsive">
            <table class="table table-bordered bg-white rounded-3 overflow-hidden mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" style="width: 25%;">Split Routine</th>
                        <th scope="col" style="width: 20%;">Days Per Week</th>
                        <th scope="col" style="width: 30%;">Ideal Candidate</th>
                        <th scope="col" style="width: 25%;">Primary Advantage</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Full Body</strong></td>
                        <td>3 Days</td>
                        <td>Beginners, busy working professionals</td>
                        <td>High frequency per muscle group (3x/week)</td>
                    </tr>
                    <tr>
                        <td><strong>Upper / Lower</strong></td>
                        <td>4 Days</td>
                        <td>Intermediate lifters, balanced recovery</td>
                        <td>Optimal balance between intensity and rest</td>
                    </tr>
                    <tr>
                        <td><strong>Push / Pull / Legs (PPL)</strong></td>
                        <td>5–6 Days</td>
                        <td>Advanced trainees, hypertrophy focus</td>
                        <td>Specialized volume per movement pattern</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- CTA --}}
    <div class="p-4 p-lg-5 rounded-4 text-white bg-dark d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div>
            <span class="badge bg-primary text-white mb-2">1-on-1 Coaching</span>
            <h3 class="fw-bold mb-2">Ready for a Periodized Strength Program?</h3>
            <p class="text-white-50 mb-0" style="max-width: 600px;">
                DK Singh provides custom 12-week progressive overload roadmaps, complete with video form reviews and weekly progression adjustments.
            </p>
        </div>
        <a href="/programs" class="btn btn-primary btn-lg rounded-pill px-4 text-nowrap fw-bold">
            Get Custom Program &rarr;
        </a>
    </div>

</div>
@endsection
