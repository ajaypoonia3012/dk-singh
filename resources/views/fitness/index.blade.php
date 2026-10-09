@extends('layouts.app')

@section('title', 'Fitness & Nutrition Knowledge Hub | DK Singh Fitness')
@section('meta_description', 'Explore practical, evidence-based fitness and nutrition guidance from DK Singh. Build strength, improve endurance, move better, and create a sustainable healthy lifestyle.')

@section('content')
<div class="container py-4 py-lg-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Fitness Hub</li>
        </ol>
    </nav>

    {{-- Hero Section --}}
    <div class="card bg-dark text-white border-0 rounded-4 overflow-hidden shadow-lg mb-5">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3">
                        <i class="bi bi-compass-fill me-1"></i> DK Singh Fitness Knowledge Platform
                    </span>
                    <h1 class="display-4 fw-bold mb-3 text-white">
                        Practical Fitness & Nutrition Guidance
                    </h1>
                    <p class="lead text-white-50 mb-4" style="max-width: 680px;">
                        Evidence-based fitness guidance for building strength, improving endurance, moving better, and creating a sustainable healthy lifestyle adapted for real life in India.
                    </p>
                    
                    {{-- Search Bar --}}
                    <form action="{{ route('fitness.search') }}" method="GET" class="mb-4" style="max-width: 580px;">
                        <div class="input-group input-group-lg shadow-sm">
                            <input type="text" name="q" class="form-control border-0" placeholder="Search guides, exercises, workouts, nutrition..." aria-label="Search">
                            <button class="btn btn-warning fw-bold px-4" type="submit">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                    </form>

                    {{-- Platform Stats --}}
                    <div class="d-flex flex-wrap gap-4 text-white-50 small">
                        <div><strong class="text-warning fs-5">{{ $totalArticles }}+</strong> Published Guides</div>
                        <div><strong class="text-warning fs-5">{{ $totalExercises }}+</strong> Formatted Exercises</div>
                        <div><strong class="text-warning fs-5">100%</strong> Ad-Free & Evidence-Based</div>
                        <div><strong class="text-warning fs-5">1-on-1</strong> Coaching Support</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Pillar Navigation Grid --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-1">Browse by Pillar</h3>
        <p class="text-muted small mb-0">Explore our core training, movement, and wellness disciplines.</p>
    </div>
    <div class="row g-3 g-md-4 mb-5">
        @php
            $navPillars = [
                ['title' => 'Exercise', 'url' => route('fitness.exercise'), 'icon' => 'bi-universal-access', 'badge' => 'Movements', 'color' => 'primary'],
                ['title' => 'Cardio & Conditioning', 'url' => route('fitness.cardio'), 'icon' => 'bi-heart-pulse-fill', 'badge' => 'Endurance', 'color' => 'danger'],
                ['title' => 'Strength Training', 'url' => route('fitness.strength-training'), 'icon' => 'bi-lightning-charge-fill', 'badge' => 'Hypertrophy', 'color' => 'warning'],
                ['title' => 'Yoga & Mobility', 'url' => route('fitness.yoga'), 'icon' => 'bi-flower1', 'badge' => 'Mobility', 'color' => 'success'],
                ['title' => 'Wellness Hub', 'url' => route('fitness.wellness'), 'icon' => 'bi-heart-half', 'badge' => 'Mind & Sleep', 'color' => 'success'],
                ['title' => 'Holistic Fitness', 'url' => route('fitness.holistic-fitness'), 'icon' => 'bi-moon-stars-fill', 'badge' => 'Recovery', 'color' => 'info'],
                ['title' => 'Nutrition & Diet', 'url' => route('blog.category', 'nutrition'), 'icon' => 'bi-egg-fried', 'badge' => 'Fuel', 'color' => 'warning'],
                ['title' => 'Exercise Library', 'url' => route('fitness.exercise-library'), 'icon' => 'bi-collection-play-fill', 'badge' => 'Directory', 'color' => 'dark'],
            ];
        @endphp
        @foreach($navPillars as $p)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ $p['url'] }}" class="card h-100 border text-decoration-none shadow-sm rounded-4 p-3 transition-card bg-white hover-shadow">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="rounded-circle p-2 bg-light text-dark fs-4">
                            <i class="bi {{ $p['icon'] }}"></i>
                        </div>
                        <span class="badge bg-light text-muted small border">{{ $p['badge'] }}</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $p['title'] }}</h5>
                    <span class="text-muted small">Explore Guides &rarr;</span>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Featured Editorial Pillar --}}
    @if($featuredPost)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-warning fw-bold text-uppercase small">Editorial Spotlight</span>
                <h2 class="fw-bold mb-0">Featured Comprehensive Guide</h2>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
            <div class="row g-0">
                <div class="col-lg-6">
                    <div style="height: 100%; min-height: 340px; position: relative;">
                        @if($featuredPost->media)
                            <img src="{{ asset('storage/' . $featuredPost->media->path) }}" alt="{{ $featuredPost->media->alt ?: $featuredPost->title }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        @else
                            <img src="{{ asset('images/dk-hero.jpg') }}" alt="{{ $featuredPost->title }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center">
                    <div class="card-body p-4 p-lg-5">
                        <div class="mb-3">
                            @if($featuredPost->category)
                                <span class="badge bg-warning text-dark me-2">{{ $featuredPost->category->name }}</span>
                            @endif
                            <span class="badge bg-light text-muted border">{{ $featuredPost->type_label }}</span>
                        </div>
                        <h3 class="display-6 fw-bold mb-3">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="text-decoration-none text-dark hover-primary">
                                {{ $featuredPost->title }}
                            </a>
                        </h3>
                        <p class="text-muted mb-4 lead fs-6">
                            {{ $featuredPost->excerpt }}
                        </p>
                        <div class="text-muted small mb-4">
                            By <strong>{{ $featuredPost->author }}</strong> &bull; {{ $featuredPost->reading_time }} min read &bull; Updated {{ optional($featuredPost->published_at)->format('M Y') }}
                        </div>
                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="btn btn-warning fw-bold px-4 py-2">
                            Read Complete Guide &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Exercise Library Highlight --}}
    <section class="mb-5 p-4 p-md-5 rounded-4 bg-light border">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
            <div>
                <span class="text-warning fw-bold text-uppercase small">DK Singh Movement Database</span>
                <h2 class="fw-bold mb-1">Featured Exercises from the Library</h2>
                <p class="text-muted mb-0">Technique breakdowns, target muscle groups, setup cues, and injury prevention.</p>
            </div>
            <a href="{{ route('fitness.exercise-library') }}" class="btn btn-outline-dark fw-bold mt-3 mt-md-0">
                View Full Library ({{ $totalExercises }} Exercises) &rarr;
            </a>
        </div>

        <div class="row g-4">
            @foreach($featuredExercises as $ex)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                        <div style="height: 200px; overflow: hidden; position: relative;">
                            <img src="{{ $ex->image_url }}" alt="{{ $ex->name }}" class="w-100 h-100" style="object-fit: cover;">
                            <span class="badge bg-dark position-absolute top-0 end-0 m-3">{{ $ex->difficulty }}</span>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-warning text-dark small">{{ $ex->exercise_category }}</span>
                                <span class="badge bg-light text-dark small border">{{ $ex->equipment }}</span>
                            </div>
                            <h4 class="fw-bold mb-2">
                                <a href="{{ route('fitness.exercise-detail', $ex->slug) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $ex->name }}
                                </a>
                            </h4>
                            <p class="text-muted small mb-3 flex-grow-1">
                                {{ Str::limit($ex->short_description, 95) }}
                            </p>
                            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                <span class="small text-muted"><strong>Target:</strong> {{ $ex->primary_muscle }}</span>
                                <a href="{{ route('fitness.exercise-detail', $ex->slug) }}" class="text-warning fw-bold text-decoration-none small">
                                    Guide &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Content Hub Sections by Topic --}}
    @foreach($sections as $sec)
        @if($sec['posts']->count() > 0)
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <div>
                    <h3 class="fw-bold mb-1">{{ $sec['title'] }}</h3>
                    <p class="text-muted small mb-0">{{ $sec['description'] }}</p>
                </div>
                <a href="{{ $sec['url'] }}" class="btn btn-sm btn-outline-dark fw-bold">
                    Explore {{ $sec['title'] }} &rarr;
                </a>
            </div>

            <div class="row g-4">
                @foreach($sec['posts'] as $post)
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
                                    @if($post->category)
                                        <span class="badge bg-warning text-dark small">{{ $post->category->name }}</span>
                                    @endif
                                    <span class="badge bg-light text-muted small border">{{ $post->type_label }}</span>
                                </div>
                                <h5 class="fw-bold mb-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                        {{ $post->title }}
                                    </a>
                                </h5>
                                <p class="text-muted small mb-3 flex-grow-1">
                                    {{ Str::limit($post->excerpt, 90) }}
                                </p>
                                <div class="text-muted small d-flex justify-content-between align-items-center pt-2 border-top">
                                    <span>{{ $post->reading_time }} min read</span>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-dark fw-bold text-decoration-none">
                                        Read &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif
    @endforeach

    {{-- Clean DK Singh CTA Section (100% Ad-Free) --}}
    <div class="card bg-dark text-white p-4 p-md-5 rounded-4 my-5 border-0 shadow-lg">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-2">
                    <i class="bi bi-star-fill me-1"></i> Elite Coaching
                </span>
                <h2 class="display-6 fw-bold mb-3 text-white">Need a Tailored Workout & Diet Plan?</h2>
                <p class="lead text-white-50 mb-4" style="max-width: 650px;">
                    Take the guesswork out of your transformation. Work directly with DK Singh for 100% customized Indian diet charts, progressive strength routines, and weekly habit check-ins.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="/programs" class="btn btn-warning btn-lg fw-bold px-4">Explore Coaching Programs</a>
                    <a href="/transformations" class="btn btn-outline-light btn-lg px-4">View Client Results</a>
                    <a href="/contact" class="btn btn-link text-white text-decoration-none">Free Assessment &rarr;</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
