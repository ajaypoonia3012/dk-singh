@extends('layouts.app')

@section('title', 'Holistic Fitness, Sleep, Recovery & Lifestyle | DK Singh Fitness')
@section('meta_description', 'Explore sleep hygiene, nervous system regulation, recovery strategies, and holistic health practices to optimize your training and daily life.')

@section('content')
<div class="container py-4 py-lg-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fitness.index') }}" class="text-decoration-none">Fitness</a></li>
            <li class="breadcrumb-item active" aria-current="page">Holistic Fitness</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-5">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-2">Sustainable Lifestyle</span>
        <h1 class="display-4 fw-bold mb-2">Holistic Fitness, Recovery & Daily Habits</h1>
        <p class="lead text-muted" style="max-width: 760px;">
            True physical transformation extends far beyond the 60 minutes spent in the gym. Discover how restorative sleep, daily NEAT movement, hydration, and stress regulation anchor lasting fat loss and longevity.
        </p>

        {{-- Lifestyle Pillars --}}
        <div class="d-flex flex-wrap gap-2 pt-2">
            @foreach($lifestyleHabits as $habit)
                <span class="badge bg-light text-dark border px-3 py-2">
                    <i class="bi bi-heart-pulse text-warning me-1"></i> {{ $habit }}
                </span>
            @endforeach
        </div>
    </div>

    {{-- Daily Movement & Habits Strip --}}
    @if($exercises->count() > 0)
    <section class="mb-5 p-4 rounded-4 bg-light border">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0"><i class="bi bi-sun text-warning me-2"></i>Daily Micro-Movement Routines</h4>
                <p class="text-muted small mb-0">Low-intensity mobility and core activations you can perform during breaks at home or office.</p>
            </div>
            <a href="{{ route('fitness.exercise-library') }}" class="text-dark small fw-bold text-decoration-none">
                Explore Library &rarr;
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
                            <span class="badge bg-warning text-dark small mb-1">{{ $ex->difficulty }}</span>
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
                <h3 class="fw-bold mb-1">Wellness & Lifestyle Articles</h3>
                <p class="text-muted mb-0 small">Evidence-informed guidance on metabolic health, restorative sleep, and sustainable habits.</p>
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
                                <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark">
                                    {{ $post->type_label }}
                                </span>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="text-muted small mb-2 d-flex align-items-center gap-2">
                                    <span><i class="bi bi-calendar3 me-1"></i>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
                                    <span>&bull;</span>
                                    <span><i class="bi bi-clock me-1"></i>{{ $post->reading_time ?? 5 }} min read</span>
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
                <i class="bi bi-heart-pulse fs-1 text-muted"></i>
                <h5 class="fw-bold mt-3">No holistic articles found</h5>
                <p class="text-muted">Check back soon for new sleep, recovery, and daily wellness strategies.</p>
            </div>
        @endif
    </section>

    {{-- The 4 Foundations of Holistic Recovery --}}
    <section class="mb-5 p-4 p-lg-5 rounded-4 bg-light border">
        <h3 class="fw-bold mb-3"><i class="bi bi-compass text-warning me-2"></i>The 4 Pillars of Sustainable Health</h3>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <span class="badge bg-primary mb-2">Sleep Science</span>
                    <h6 class="fw-bold text-dark mb-1">7–8.5 Hours of Rest</h6>
                    <p class="text-muted small mb-0">Deep delta-wave sleep regulates growth hormone release, insulin sensitivity, and leptin/ghrelin appetite hormones.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <span class="badge bg-success mb-2">Non-Exercise Activity</span>
                    <h6 class="fw-bold text-dark mb-1">8,000–10,000 Steps</h6>
                    <p class="text-muted small mb-0">NEAT accounts for up to 15% of daily energy expenditure—vastly out-burning typical 45-minute gym cardio sessions.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <span class="badge bg-info text-white mb-2">Hydration Balance</span>
                    <h6 class="fw-bold text-dark mb-1">3–4 Litres Daily</h6>
                    <p class="text-muted small mb-0">Adequate fluid intake with modest electrolytes keeps neuromuscular firing rates sharp and prevents false hunger cues.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <span class="badge bg-danger mb-2">Stress Management</span>
                    <h6 class="fw-bold text-dark mb-1">Cortisol Modulation</h6>
                    <p class="text-muted small mb-0">Chronic psychosocial stress impairs protein synthesis and promotes visceral fat accumulation around the midsection.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <div class="p-4 p-lg-5 rounded-4 text-white bg-dark d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div>
            <span class="badge bg-warning text-dark mb-2">Transform Your Life</span>
            <h3 class="fw-bold mb-2">Build Habits That Last For Decades</h3>
            <p class="text-white-50 mb-0" style="max-width: 600px;">
                Our coaching doesn't just hand you a workout sheet—we engineer lifestyle systems around your family meals, office travel, and sleep patterns.
            </p>
        </div>
        <a href="/contact" class="btn btn-warning btn-lg rounded-pill px-4 text-nowrap fw-bold text-dark">
            Schedule a Consultation &rarr;
        </a>
    </div>

</div>
@endsection
