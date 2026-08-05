@extends('layouts.app')

@section('title', $post->meta_title)
@section('meta_title', $post->meta_title)
@section('meta_description', $post->meta_description)
@section('canonical', route('blog.show', $post->slug))
@section('og_type', 'article')
@if($post->media)
    @section('meta_image', $post->media->url)
@endif

@push('head')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post->title,
        'description' => $post->meta_description,
        'image' => $post->media ? [$post->media->url] : [],
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at?->toIso8601String(),
        'author' => ['@type' => 'Person', 'name' => $post->author],
        'publisher' => ['@type' => 'Organization', 'name' => $setting?->site_name ?? 'DK Singh Fitness'],
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => route('blog.show', $post->slug)],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endpush

@section('content')
    <main class="blog-page blog-article-page">
        <div class="container-xxl">
            <nav aria-label="Breadcrumb" class="blog-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                    @if($post->category)<li class="breadcrumb-item"><a href="{{ route('blog.category', $post->category->slug) }}">{{ $post->category->name }}</a></li>@endif
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 45) }}</li>
                </ol>
            </nav>

            <header class="article-hero">
                @if($post->media)
                    <img src="{{ $post->media->url }}" alt="{{ $post->media->alt ?: $post->title }}" width="{{ $post->media->width ?: 1600 }}" height="{{ $post->media->height ?: 900 }}" fetchpriority="high" decoding="async">
                @endif
                <div class="article-hero__overlay"></div>
                <div class="article-hero__content">
                    @if($post->category)<a class="blog-category" href="{{ route('blog.category', $post->category->slug) }}">{{ $post->category->name }}</a>@endif
                    <h1>{{ $post->title }}</h1>
                    <div class="article-byline">
                        <span class="article-avatar" aria-hidden="true">{{ Str::upper(Str::substr($post->author, 0, 1)) }}</span>
                        <span><strong>{{ $post->author }}</strong><small>{{ $post->published_at?->format('F j, Y') ?? 'Recently published' }} · {{ $post->reading_time }} min read · {{ number_format($post->views) }} views</small></span>
                    </div>
                </div>
            </header>

            <div class="row g-4 g-xl-5 blog-layout">
                <div class="col-lg-8 col-xl-9">
                    <article class="article-shell">
                        <div class="article-share" aria-label="Share this article">
                            <span>Share</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook">f</a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" aria-label="Share on X">𝕏</a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn">in</a>
                        </div>
                        @if($post->excerpt)<p class="article-lead">{{ $post->excerpt }}</p>@endif
                        <div class="blog-content">{!! $post->content !!}</div>
                        @if($post->tags->isNotEmpty())
                            <footer class="article-tags"><strong>Filed under</strong><div class="blog-tag-list">@foreach($post->tags as $tag)<a href="{{ route('blog.tag', $tag->slug) }}">#{{ $tag->name }}</a>@endforeach</div></footer>
                        @endif
                    </article>
                </div>
                <div class="col-lg-4 col-xl-3">@include('blog.partials.sidebar')</div>
            </div>

            @include('blog.partials.related-posts')
            @include('blog.partials.newsletter')
        </div>
    </main>
@endsection
