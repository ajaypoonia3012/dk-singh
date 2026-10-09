@extends('layouts.app')

@section('title', 'Yoga, Mobility & Flexibility Protocols | DK Singh Fitness')
@section('meta_description', 'Improve your active range of motion, joint longevity, and muscular flexibility with structured yoga and mobility routines.')

@section('content')
<div class="container py-4 py-lg-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fitness.index') }}" class="text-decoration-none">Fitness</a></li>
            <li class="breadcrumb-item active" aria-current="page">Yoga & Flexibility</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-5">
        <span class="badge bg-success text-white px-3 py-2 fw-bold text-uppercase mb-2">Mobility & Restoration</span>
        <h1 class="display-4 fw-bold mb-2">Yoga, Mobility & Functional Flexibility</h1>
        <p class="lead text-muted" style="max-width: 740px;">
            Harmonize strength with functional range of motion. Explore foundational asanas, restorative stretching, parasympathetic breathing, and mobility drills to protect joints and enhance recovery.
        </p>

        {{-- Disciplines --}}
        <div class="d-flex flex-wrap gap-2 pt-2">
            @foreach($disciplines as $item)
                <span class="badge bg-light text-dark border px-3 py-2">
                    <i class="bi bi-flower1 text-success me-1"></i> {{ $item }}
                </span>
            @endforeach
        </div>

        {{-- Health Disclaimer --}}
        <div class="alert alert-light border d-flex align-items-center gap-3 py-3 px-4 rounded-3 mt-3" style="max-width: 760px;">
            <i class="bi bi-info-circle text-success fs-3 flex-shrink-0"></i>
            <div class="small text-muted">
                <strong class="text-dark d-block">Educational Guidance Notice:</strong>
                Yoga practices and mobility routines are provided for general physical conditioning and flexibility enhancement. They are not intended as medical treatment or clinical physical therapy for acute spinal injuries.
            </div>
        </div>
    </div>

    {{-- Foundational Asanas & Stretches --}}
    @if($exercises->count() > 0)
    <section class="mb-5 p-4 rounded-4 bg-light border">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="fw-bold mb-0"><i class="bi bi-person-arms-up text-success me-2"></i>Restorative Movements</h4>
                <p class="text-muted small mb-0">Active mobility drills and restorative postures for daily joint lubrication.</p>
            </div>
            <a href="{{ route('fitness.exercise-library', ['category' => 'Yoga & Flexibility']) }}" class="text-dark small fw-bold text-decoration-none">
                All Mobility Movements &rarr;
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
                            <span class="badge bg-success text-white small mb-1">{{ $ex->difficulty }}</span>
                            <h6 class="fw-bold text-dark mb-1">{{ $ex->name }}</h6>
                            <p class="text-muted small mb-0">{{ Str::limit($ex->short_description, 60) }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Main Articles Grid --}}
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Yoga & Recovery Articles</h3>
                <p class="text-muted mb-0 small">Evidence-informed routines for stiffness, posture, and nervous system regulation.</p>
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
                                <span class="position-absolute top-0 end-0 m-3 badge bg-success text-white">
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
                <i class="bi bi-flower1 fs-1 text-muted"></i>
                <h5 class="fw-bold mt-3">No yoga articles found</h5>
                <p class="text-muted">Check back soon for new mobility routines and breathwork protocols.</p>
            </div>
        @endif
    </section>

    {{-- Yoga for Lifters Guide --}}
    <section class="mb-5 p-4 p-lg-5 rounded-4 bg-light border">
        <h3 class="fw-bold mb-3"><i class="bi bi-arrows-angle-expand text-success me-2"></i>Integrating Yoga with Heavy Strength Training</h3>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <h6 class="fw-bold text-dark mb-2">Thoracic Spine Mobility</h6>
                    <p class="text-muted small mb-0">Improves overhead shoulder positioning for overhead presses and reduces neck strain during squats.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <h6 class="fw-bold text-dark mb-2">Hip Capsule Opening</h6>
                    <p class="text-muted small mb-0">Prevents lumbar flexion ("butt wink") in deep squats and protects the SI joint during conventional deadlifts.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-white rounded-3 border h-100">
                    <h6 class="fw-bold text-dark mb-2">Downregulation & Sleep</h6>
                    <p class="text-muted small mb-0">Post-workout slow exhalation drills shift autonomic tone from sympathetic fight-or-flight to parasympathetic recovery.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <div class="p-4 p-lg-5 rounded-4 text-white bg-dark d-flex flex-column flex-md-row align-items-center justify-content-between gap-4">
        <div>
            <span class="badge bg-success text-white mb-2">Holistic Fitness</span>
            <h3 class="fw-bold mb-2">Balance Heavy Lifting with Restorative Mobility</h3>
            <p class="text-white-50 mb-0" style="max-width: 600px;">
                DK Singh coaching incorporates pre-lift dynamic mobility sequences and post-session restorative flows to keep you moving pain-free.
            </p>
        </div>
        <a href="/programs" class="btn btn-success btn-lg rounded-pill px-4 text-nowrap fw-bold text-white">
            Start Personalized Coaching &rarr;
        </a>
    </div>

</div>
@endsection
