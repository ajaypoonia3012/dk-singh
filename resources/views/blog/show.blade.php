@extends('layouts.app')

@section('title', $post->seo_title ?: $post->title)

@push('meta')
    <meta name="description" content="{{ $post->meta_description ?? Str::limit(strip_tags($post->excerpt ?? $post->content), 155) }}">
    <link rel="canonical" href="{{ route('blog.show', $post->slug) }}">
    <meta property="og:title" content="{{ $post->title }} | DK Singh Fitness">
    <meta property="og:description" content="{{ Str::limit(strip_tags($post->excerpt ?? $post->content), 155) }}">
    @if($post->media)
        <meta property="og:image" content="{{ asset('storage/'.$post->media->path) }}">
    @endif
    <meta property="og:type" content="article">

    @php
        $blogSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => Str::limit(strip_tags($post->excerpt ?? $post->content), 155),
            'author' => [
                '@type' => 'Person',
                'name' => $post->author ?? 'DK Singh',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'DK Singh Fitness & Nutrition',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                ],
            ],
            'datePublished' => $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
        ];

        if ($post->media) {
            $blogSchema['image'] = asset('storage/' . $post->media->path);
        }
    @endphp
    <script type="application/ld+json">
    {!! json_encode($blogSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@php
    // Extract H2 and H3 headings for dynamic Table of Contents
    $pattern = '/<h([2-3])[^>]*>(.*?)<\/h\1>/i';
    preg_match_all($pattern, $post->content, $matches, PREG_SET_ORDER);
    $toc = [];
    $processedContent = $post->content;
    
    if (count($matches) >= 2) {
        foreach ($matches as $idx => $match) {
            $level = $match[1];
            $title = strip_tags($match[2]);
            $anchor = 'section-' . ($idx + 1) . '-' . Str::slug($title);
            $toc[] = [
                'level' => (int)$level,
                'title' => $title,
                'anchor' => $anchor,
            ];
            $replacement = "<h{$level} id=\"{$anchor}\" class=\"pt-2\">{$match[2]}</h{$level}>";
            $pos = strpos($processedContent, $match[0]);
            if ($pos !== false) {
                $processedContent = substr_replace($processedContent, $replacement, $pos, strlen($match[0]));
            }
        }
    }

    // Resolve contextual CTA payload
    $cta = $articleCta ?? (new \App\Services\ArticleCtaService)->forPost($post);
    $renderedCtaHtml = view('blog.partials.contextual-cta', compact('cta'))->render();

    // Dynamically replace legacy static card in content with contextual CTA
    $ctaPattern = '/<div class="card bg-dark text-[w]hite[^>]*>.*?<\/div>\s*<\/div>\s*<\/div>/s';
    if (preg_match($ctaPattern, $processedContent)) {
        $processedContent = preg_replace($ctaPattern, $renderedCtaHtml, $processedContent, 1);
        $hasEmbeddedCta = true;
    } else {
        $hasEmbeddedCta = false;
    }
@endphp

@section('content')

<div class="container py-4 py-lg-5">

    {{-- Breadcrumbs --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fitness.index') }}" class="text-decoration-none">Fitness</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-decoration-none">Blog</a></li>
            @if($post->category)
                <li class="breadcrumb-item"><a href="{{ route('blog.category', $post->category->slug) }}" class="text-decoration-none">{{ $post->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 40) }}</li>
        </ol>
    </nav>

    <div class="row g-5">

        <div class="col-lg-8">

            <article>

                @if($post->media)
                    <div class="rounded-4 overflow-hidden mb-4 border" style="max-height: 480px;">
                        <img
                            src="{{ asset('storage/'.$post->media->path) }}"
                            class="img-fluid w-100"
                            style="object-fit: cover;"
                            alt="{{ $post->media->alt ?: $post->title }}">
                    </div>
                @endif

                <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
                    @if($post->category)
                        <a href="{{ route('blog.category', $post->category->slug) }}" class="badge bg-warning text-dark text-decoration-none px-3 py-2 fw-bold">
                            {{ $post->category->name }}
                        </a>
                    @endif
                    <span class="badge bg-secondary text-light px-3 py-2">
                        {{ $post->type_label }}
                    </span>
                </div>

                <h1 class="display-5 fw-bold mb-3">
                    {{ $post->title }}
                </h1>

                <div class="text-muted mb-4 small d-flex flex-wrap align-items-center gap-2">
                    <span>By <strong class="text-dark">{{ $post->author ?? 'DK Singh' }}</strong></span>
                    <span>&bull;</span>
                    <span>Published {{ optional($post->published_at)->format('d M Y') }}</span>
                    @if($post->updated_at && $post->updated_at->diffInDays($post->published_at) > 1)
                        <span>&bull;</span>
                        <span class="text-success"><i class="bi bi-arrow-repeat me-1"></i>Updated {{ $post->updated_at->format('d M Y') }}</span>
                    @endif
                    <span>&bull;</span>
                    <span><i class="bi bi-clock me-1"></i>{{ $post->reading_time }} min read</span>
                    <span>&bull;</span>
                    <span><i class="bi bi-eye me-1"></i>{{ number_format($post->views) }} views</span>
                </div>

                @if($post->excerpt)
                    <div class="p-3 mb-4 rounded-3 border-start border-4 border-warning bg-light lead fs-6 fst-italic">
                        {{ $post->excerpt }}
                    </div>
                @endif

                {{-- Table of Contents (for long guides) --}}
                @if(count($toc) >= 2)
                    <div class="card border rounded-4 p-4 mb-4 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-list-nested text-primary me-2"></i>Table of Contents
                            </h5>
                            <span class="badge bg-light text-muted border small">{{ count($toc) }} sections</span>
                        </div>
                        <nav class="pt-2">
                            <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                                @foreach($toc as $item)
                                    <li class="{{ $item['level'] === 3 ? 'ps-3 small' : '' }}">
                                        <a href="#{{ $item['anchor'] }}" class="text-decoration-none text-dark hover-primary d-inline-flex align-items-center gap-2">
                                            <i class="bi bi-chevron-right text-primary small"></i>
                                            <span>{{ $item['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                @endif

                {{-- Article Body --}}
                <div class="blog-content" style="line-height: 1.8; font-size: 1.05rem;">
                    {!! $processedContent !!}
                </div>

                {{-- Informational Health & Medical Disclaimer --}}
                <div class="alert alert-light border border-secondary border-opacity-25 rounded-3 p-3 mt-5 small text-muted">
                    <strong class="text-dark d-block mb-1"><i class="bi bi-shield-check text-primary me-1"></i>Educational Health & Fitness Notice</strong>
                    The fitness, training, and nutritional information provided in this article is for educational and general wellness purposes only. It is not intended as medical advice, clinical diagnosis, or physical therapy treatment. Consult with a qualified physician or certified health specialist prior to implementing vigorous physical training or major dietary interventions.
                </div>

                @if($post->tags->count())
                    <hr class="my-4">
                    <h6 class="fw-bold text-muted text-uppercase small mb-2">Related Topics</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('blog.tag', $tag->slug) }}" class="badge bg-light text-dark border text-decoration-none py-2 px-3">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

            </article>

            {{-- Contextual Related Exercises Strip --}}
            @if(isset($relatedExercises) && $relatedExercises->count() > 0)
                <section class="mt-5 p-4 rounded-4 bg-light border">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0"><i class="bi bi-shield-shaded text-primary me-2"></i>Recommended Exercises</h4>
                        <a href="{{ route('fitness.exercise-library') }}" class="text-dark small fw-bold text-decoration-none">
                            Exercise Library &rarr;
                        </a>
                    </div>
                    <div class="row g-3">
                        @foreach($relatedExercises as $ex)
                            <div class="col-md-4">
                                <a href="{{ route('fitness.exercise-detail', $ex->slug) }}" class="card h-100 border text-decoration-none rounded-3 overflow-hidden bg-light transition-card">
                                    <div style="height: 120px; overflow: hidden;">
                                        <img src="{{ $ex->image_url }}" alt="{{ $ex->name }}" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="p-3">
                                        <span class="badge bg-warning text-dark small mb-1">{{ $ex->primary_muscle }}</span>
                                        <h6 class="fw-bold text-dark mb-1">{{ $ex->name }}</h6>
                                        <p class="text-muted small mb-0">{{ Str::limit($ex->short_description, 50) }}</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if(!$hasEmbeddedCta)
            {{-- 100% Ad-Free Editorial Flow: Content -> Related Content -> Contextual DK Singh CTA --}}
            <div class="p-4 p-lg-5 rounded-4 text-light bg-dark d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 mt-5">
                <div>
                    <span class="badge bg-warning text-dark mb-2">{{ $cta['badge'] }}</span>
                    <h4 class="fw-bold mb-1">{{ $cta['heading'] }}</h4>
                    <p class="mb-0 small" style="max-width: 520px; opacity: 0.85;">
                        {{ $cta['description'] }}
                    </p>
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2 text-nowrap">
                    @if(!empty($cta['secondary_button_text']) && !empty($cta['secondary_button_url']))
                        <a href="{{ $cta['secondary_button_url'] }}" class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold small">
                            {{ $cta['secondary_button_text'] }}
                        </a>
                    @endif
                    <a href="{{ $cta['button_url'] }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
                        {{ $cta['button_text'] }} &rarr;
                    </a>
                </div>
            </div>
            @endif

            @include('blog.partials.related-posts')

        </div>

        <div class="col-lg-4">
            @include('blog.partials.sidebar')
        </div>

    </div>

</div>

@endsection
