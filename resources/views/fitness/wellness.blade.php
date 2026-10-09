@extends('layouts.app')

@section('title', 'Wellness, Mind & Restorative Recovery Hub | DK Singh Fitness')
@section('meta_description', 'Evidence-based wellness, sleep recovery, stress resilience, mindful nutrition habits, and longevity protocols for sustainable health in India.')
@section('canonical', route('fitness.wellness'))
@if($featuredPost && $featuredPost->media)
    @section('og_image', asset('storage/' . $featuredPost->media->path))
@endif

@push('meta')
    @php
        $wellnessSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'DK Singh Wellness & Restorative Recovery Hub',
            'description' => 'Evidence-based wellness, sleep recovery, stress resilience, mindful nutrition habits, and longevity protocols.',
            'url' => route('fitness.wellness'),
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'DK Singh Fitness & Nutrition',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Home',
                        'item' => url('/'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => 'Fitness',
                        'item' => route('fitness.index'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => 'Wellness',
                        'item' => route('fitness.wellness'),
                    ],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($wellnessSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
<div class="container py-4 py-lg-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fitness.index') }}" class="text-decoration-none">Fitness</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wellness</li>
        </ol>
    </nav>

    {{-- Hero Section --}}
    <div class="card bg-dark text-white border-0 rounded-4 overflow-hidden shadow-lg mb-5" style="background: linear-gradient(135deg, #111827 0%, #1f2937 60%, #0f172a 100%);">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-success text-white px-3 py-2 fw-bold text-uppercase mb-3">
                        <i class="bi bi-heart-pulse-fill me-1"></i> DK Singh Wellness & Lifestyle Ecosystem
                    </span>
                    <h1 class="display-4 fw-bold mb-3 text-white">
                        Mind, Recovery, Habits & Sustainable Vitality
                    </h1>
                    <p class="lead text-white-50 mb-4" style="max-width: 700px;">
                        True fitness extends far beyond the gym floor. Discover evidence-backed protocols for restorative sleep, nervous system regulation, sustainable daily micro-habits, and long-term metabolic health designed for modern Indian lifestyles.
                    </p>

                    {{-- Search Form --}}
                    <form action="{{ route('fitness.search') }}" method="GET" class="mb-4" style="max-width: 540px;">
                        <div class="input-group input-group-lg shadow-sm">
                            <input type="text" name="q" class="form-control border-0" placeholder="Search sleep, stress, habits, recovery..." aria-label="Search wellness">
                            <button class="btn btn-success fw-bold px-4" type="submit">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                    </form>

                    {{-- Wellness Guarantees --}}
                    <div class="d-flex flex-wrap gap-4 text-white-50 small">
                        <div><strong class="text-success fs-5">100%</strong> Evidence-Based Science</div>
                        <div><strong class="text-success fs-5">0%</strong> Fad Trends or Pseudoscience</div>
                        <div><strong class="text-success fs-5">Ad-Free</strong> Focused Editorial</div>
                        <div><strong class="text-success fs-5">Holistic</strong> Mind-Body Integration</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wellness Topic Navigation Grid --}}
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
            <div>
                <span class="text-success fw-bold text-uppercase small">Core Disciplines</span>
                <h3 class="fw-bold mb-0">Explore Wellness Topics</h3>
            </div>
            <p class="text-muted small mb-0">Jump directly to specific well-being protocols.</p>
        </div>

        <div class="row g-3 g-md-4 mb-5">
            @foreach($wellnessPillars as $pillar)
                <div class="col-6 col-md-4 col-lg">
                    <a href="#{{ $pillar['anchor'] }}" class="card h-100 border text-decoration-none shadow-sm rounded-4 p-3 transition-card bg-white hover-shadow text-center">
                        <div class="rounded-circle p-3 bg-light text-success fs-4 mx-auto mb-2 d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi {{ $pillar['icon'] }}"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">{{ $pillar['title'] }}</h6>
                        <span class="badge bg-light text-muted border small">{{ $pillar['count'] }} Guides</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Editorial Spotlight Feature --}}
    @if($featuredPost)
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="text-success fw-bold text-uppercase small">Editorial Spotlight</span>
                <h2 class="fw-bold mb-0">Featured Wellness Guide</h2>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="row g-0">
                <div class="col-lg-6">
                    <div style="height: 100%; min-height: 320px; position: relative;">
                        @if($featuredPost->media)
                            <img src="{{ asset('storage/' . $featuredPost->media->path) }}" alt="{{ $featuredPost->media->alt ?: $featuredPost->title }}" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        @else
                            <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                                <i class="bi bi-heart-pulse fs-1"></i>
                            </div>
                        @endif
                        <span class="badge bg-success position-absolute top-0 start-0 m-3 px-3 py-2 fw-bold">
                            {{ $featuredPost->category?->name ?: 'Wellness' }}
                        </span>
                    </div>
                </div>
                <div class="col-lg-6 p-4 p-md-5 d-flex flex-column justify-content-center">
                    <div class="text-muted small mb-2 d-flex align-items-center gap-2">
                        <span>By {{ $featuredPost->author ?? 'DK Singh' }}</span>
                        <span>&bull;</span>
                        <span>{{ $featuredPost->reading_time ?? 5 }} min read</span>
                        <span>&bull;</span>
                        <span>{{ optional($featuredPost->published_at)->format('M d, Y') }}</span>
                    </div>
                    <h3 class="fw-bold mb-3">
                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="text-dark text-decoration-none hover-primary">
                            {{ $featuredPost->title }}
                        </a>
                    </h3>
                    <p class="text-muted mb-4">
                        {{ Str::limit(strip_tags($featuredPost->excerpt ?: $featuredPost->content), 200) }}
                    </p>
                    <div>
                        <a href="{{ route('blog.show', $featuredPost->slug) }}" class="btn btn-success fw-bold px-4 py-2 rounded-pill">
                            Read Full Guide &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Topic Group 1: Mind & Mental Well-Being --}}
    <section id="mental-wellbeing" class="mb-5 pt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-primary text-white mb-2">Cognitive & Mental Health</span>
                <h3 class="fw-bold mb-0">Mind & Mental Well-Being</h3>
            </div>
            <a href="{{ route('blog.category', 'mindset-and-motivation') }}" class="text-decoration-none fw-bold small text-primary">
                View All Mindset Guides &rarr;
            </a>
        </div>
        <p class="text-muted mb-4">The bi-directional connection between physical exertion and mental clarity, emotional mood regulation, and digital discipline.</p>

        <div class="row g-4">
            @foreach($mindPosts as $post)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition-card">
                        <div style="height: 180px; position: relative;">
                            @if($post->media)
                                <img src="{{ asset('storage/' . $post->media->path) }}" alt="{{ $post->media->alt ?: $post->title }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-brain fs-2"></i>
                                </div>
                            @endif
                            <span class="badge bg-dark text-white position-absolute top-0 start-0 m-2 px-2 py-1 small">
                                {{ $post->category?->name ?: 'Mindset' }}
                            </span>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <span class="text-muted small mb-1">{{ $post->reading_time ?? 5 }} min read</span>
                            <h6 class="fw-bold mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ Str::limit($post->title, 65) }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-3 flex-grow-1">
                                {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 90) }}
                            </p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-primary small fw-bold text-decoration-none mt-auto">
                                Read Guide &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Topic Group 2: Sleep & Restorative Recovery --}}
    <section id="sleep-recovery" class="mb-5 pt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-info text-white mb-2">Restorative Biology</span>
                <h3 class="fw-bold mb-0">Sleep & Recovery</h3>
            </div>
            <a href="{{ route('fitness.holistic-fitness') }}" class="text-decoration-none fw-bold small text-info">
                Explore Recovery Protocols &rarr;
            </a>
        </div>
        <p class="text-muted mb-4">Quality sleep is the primary driver of fat loss, hormone balance, and muscular repair. Master circadian biology and post-exercise recovery.</p>

        <div class="row g-4">
            @foreach($sleepPosts as $post)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition-card">
                        <div style="height: 180px; position: relative;">
                            @if($post->media)
                                <img src="{{ asset('storage/' . $post->media->path) }}" alt="{{ $post->media->alt ?: $post->title }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-moon-stars fs-2"></i>
                                </div>
                            @endif
                            <span class="badge bg-dark text-white position-absolute top-0 start-0 m-2 px-2 py-1 small">
                                {{ $post->category?->name ?: 'Recovery' }}
                            </span>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <span class="text-muted small mb-1">{{ $post->reading_time ?? 5 }} min read</span>
                            <h6 class="fw-bold mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ Str::limit($post->title, 65) }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-3 flex-grow-1">
                                {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 90) }}
                            </p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-info small fw-bold text-decoration-none mt-auto">
                                Read Guide &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Restorative Movement / Mobility Strip --}}
    @if($exercises->count() > 0)
    <section class="mb-5 p-4 rounded-4 bg-light border">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-success text-white mb-1">Restorative Movement</span>
                <h4 class="fw-bold mb-0"><i class="bi bi-flower1 text-success me-2"></i>Mobility & Recovery Exercises</h4>
            </div>
            <a href="{{ route('fitness.exercise-library', ['category' => 'Yoga & Flexibility']) }}" class="text-dark small fw-bold text-decoration-none">
                View Full Exercise Library &rarr;
            </a>
        </div>
        <p class="text-muted small mb-4">Incorporate these low-stress mobility exercises into your daily decompression or active recovery days.</p>
        <div class="row g-3">
            @foreach($exercises as $ex)
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden bg-white">
                        <div style="height: 140px; position: relative;">
                            @if($ex->media)
                                <img src="{{ asset('storage/' . $ex->media->path) }}" alt="{{ $ex->name }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center text-white">
                                    <i class="bi bi-activity fs-2"></i>
                                </div>
                            @endif
                            <span class="badge bg-dark position-absolute bottom-0 start-0 m-2">{{ $ex->primary_muscle }}</span>
                        </div>
                        <div class="p-3">
                            <h6 class="fw-bold mb-1">
                                <a href="{{ route('fitness.exercise-detail', $ex->slug) }}" class="text-dark text-decoration-none">
                                    {{ $ex->name }}
                                </a>
                            </h6>
                            <span class="text-muted small">{{ $ex->difficulty }} &bull; {{ $ex->equipment }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Topic Group 3: Stress Management & Nervous System --}}
    <section id="stress-management" class="mb-5 pt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-danger text-white mb-2">Nervous System Health</span>
                <h3 class="fw-bold mb-0">Stress Management & Resilience</h3>
            </div>
            <a href="{{ route('fitness.yoga') }}" class="text-decoration-none fw-bold small text-danger">
                Yoga & Breathwork &rarr;
            </a>
        </div>
        <p class="text-muted mb-4">Chronic cortisol impedes fat loss and blunts energy. Discover low-impact workouts, breath regulation, and active stress buffers.</p>

        <div class="row g-4">
            @foreach($stressPosts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition-card">
                        <div style="height: 180px; position: relative;">
                            @if($post->media)
                                <img src="{{ asset('storage/' . $post->media->path) }}" alt="{{ $post->media->alt ?: $post->title }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-heart-pulse fs-2"></i>
                                </div>
                            @endif
                            <span class="badge bg-dark text-white position-absolute top-0 start-0 m-2 px-2 py-1 small">
                                {{ $post->category?->name ?: 'Stress Management' }}
                            </span>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <span class="text-muted small mb-1">{{ $post->reading_time ?? 5 }} min read</span>
                            <h6 class="fw-bold mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ Str::limit($post->title, 70) }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-3 flex-grow-1">
                                {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 100) }}
                            </p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-danger small fw-bold text-decoration-none mt-auto">
                                Read Guide &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Topic Group 4: Healthy Habits & Mindfulness --}}
    <section id="healthy-habits" class="mb-5 pt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-warning text-dark mb-2">Behavioral Science</span>
                <h3 class="fw-bold mb-0">Healthy Habits & Mindfulness</h3>
            </div>
            <a href="{{ route('blog.category', 'lifestyle-and-wellness') }}" class="text-decoration-none fw-bold small text-warning">
                Explore Lifestyle Articles &rarr;
            </a>
        </div>
        <p class="text-muted mb-4">Consistency beats intensity every single time. Practical guides to building sustainable micro-habits, mindful eating, and overcoming lifestyle friction.</p>

        <div class="row g-4">
            @foreach($habitPosts as $post)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition-card">
                        <div style="height: 180px; position: relative;">
                            @if($post->media)
                                <img src="{{ asset('storage/' . $post->media->path) }}" alt="{{ $post->media->alt ?: $post->title }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-calendar-check fs-2"></i>
                                </div>
                            @endif
                            <span class="badge bg-dark text-white position-absolute top-0 start-0 m-2 px-2 py-1 small">
                                {{ $post->category?->name ?: 'Habits' }}
                            </span>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <span class="text-muted small mb-1">{{ $post->reading_time ?? 5 }} min read</span>
                            <h6 class="fw-bold mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ Str::limit($post->title, 65) }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-3 flex-grow-1">
                                {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 90) }}
                            </p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-warning small fw-bold text-decoration-none mt-auto">
                                Read Guide &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Topic Group 5: Active Lifestyle & Longevity --}}
    <section id="longevity" class="mb-5 pt-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <span class="badge bg-secondary text-white mb-2">Longevity Protocols</span>
                <h3 class="fw-bold mb-0">Active Lifestyle & Longevity</h3>
            </div>
            <a href="{{ route('fitness.index') }}" class="text-decoration-none fw-bold small text-secondary">
                Back to Fitness Hub &rarr;
            </a>
        </div>
        <p class="text-muted mb-4">Maintaining metabolic flexibility, healthy blood sugar levels, and joyful recreational sports to ensure decade-spanning vitality.</p>

        <div class="row g-4">
            @foreach($lifestylePosts as $post)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition-card">
                        <div style="height: 180px; position: relative;">
                            @if($post->media)
                                <img src="{{ asset('storage/' . $post->media->path) }}" alt="{{ $post->media->alt ?: $post->title }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-shield-check fs-2"></i>
                                </div>
                            @endif
                            <span class="badge bg-dark text-white position-absolute top-0 start-0 m-2 px-2 py-1 small">
                                {{ $post->category?->name ?: 'Longevity' }}
                            </span>
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <span class="text-muted small mb-1">{{ $post->reading_time ?? 5 }} min read</span>
                            <h6 class="fw-bold mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ Str::limit($post->title, 65) }}
                                </a>
                            </h6>
                            <p class="text-muted small mb-3 flex-grow-1">
                                {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 90) }}
                            </p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-secondary small fw-bold text-decoration-none mt-auto">
                                Read Guide &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- All Wellness Articles Paginated --}}
    <section class="mb-5 pt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-success fw-bold text-uppercase small">Full Archive</span>
                <h3 class="fw-bold mb-0">All Wellness & Lifestyle Articles</h3>
            </div>
            <span class="badge bg-light text-muted border px-3 py-2">
                {{ $allWellnessPosts->total() }} Total Guides
            </span>
        </div>

        <div class="row g-4 mb-4">
            @foreach($allWellnessPosts as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white hover-shadow transition-card">
                        <div style="height: 200px; position: relative;">
                            @if($post->media)
                                <img src="{{ asset('storage/' . $post->media->path) }}" alt="{{ $post->media->alt ?: $post->title }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-heart-pulse fs-1"></i>
                                </div>
                            @endif
                            <span class="badge bg-success text-white position-absolute top-0 start-0 m-3 px-2 py-1 small">
                                {{ $post->category?->name ?: 'Wellness' }}
                            </span>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <span class="text-muted small mb-2 d-flex align-items-center gap-2">
                                <span><i class="bi bi-clock me-1"></i>{{ $post->reading_time ?? 5 }} min read</span>
                                <span>&bull;</span>
                                <span>{{ optional($post->published_at)->format('d M Y') }}</span>
                            </span>
                            <h5 class="fw-bold mb-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            <p class="text-muted small mb-3 flex-grow-1">
                                {{ Str::limit(strip_tags($post->excerpt ?: $post->content), 120) }}
                            </p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-success fw-bold btn-sm rounded-pill mt-auto align-self-start">
                                Read Guide &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $allWellnessPosts->fragment('all-wellness')->links() }}
        </div>
    </section>

    {{-- Editorial Ecosystem Cross-Links --}}
    <div class="card bg-light border rounded-4 p-4 p-md-5 text-center mb-5">
        <h3 class="fw-bold mb-2">Continue Exploring the DK Singh Ecosystem</h3>
        <p class="text-muted mx-auto mb-4" style="max-width: 640px;">
            Wellness works hand-in-hand with structured movement and nutrition. Discover our specialized training pillars and comprehensive exercise index.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="{{ route('fitness.index') }}" class="btn btn-dark fw-bold px-4 py-2 rounded-pill">
                <i class="bi bi-compass-fill me-1"></i> Fitness Knowledge Hub
            </a>
            <a href="{{ route('fitness.exercise') }}" class="btn btn-outline-primary fw-bold px-4 py-2 rounded-pill">
                <i class="bi bi-universal-access me-1"></i> Exercise Guides
            </a>
            <a href="{{ route('fitness.strength-training') }}" class="btn btn-outline-warning fw-bold px-4 py-2 rounded-pill text-dark">
                <i class="bi bi-lightning-charge-fill me-1"></i> Strength Training
            </a>
            <a href="{{ route('fitness.cardio') }}" class="btn btn-outline-danger fw-bold px-4 py-2 rounded-pill">
                <i class="bi bi-heart-pulse-fill me-1"></i> Cardio & Fat Loss
            </a>
            <a href="{{ route('blog.category', 'nutrition') }}" class="btn btn-outline-success fw-bold px-4 py-2 rounded-pill">
                <i class="bi bi-egg-fried me-1"></i> Nutrition & Diet
            </a>
        </div>
    </div>

</div>
@endsection
