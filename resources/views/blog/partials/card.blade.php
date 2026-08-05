@php
    $isFeatured = $featured ?? false;
    $image = $post->media;
@endphp

<article class="blog-card {{ $isFeatured ? 'blog-card--featured' : '' }}">
    <a href="{{ route('blog.show', $post->slug) }}" class="blog-card__image-link" aria-label="Read {{ $post->title }}">
        @if($image)
            <img
                src="{{ $image->url }}"
                alt="{{ $image->alt ?: $post->title }}"
                width="{{ $image->width ?: 1200 }}"
                height="{{ $image->height ?: 675 }}"
                loading="{{ $isFeatured ? 'eager' : 'lazy' }}"
                decoding="async">
        @else
            <div class="blog-card__image-fallback" role="img" aria-label="{{ $post->title }}">
                <span>DK</span> Singh Fitness
            </div>
        @endif
        <span class="blog-card__shade" aria-hidden="true"></span>
    </a>

    <div class="blog-card__body">
        <div class="blog-card__topline">
            @if($post->category)
                <a class="blog-category" href="{{ route('blog.category', $post->category->slug) }}">{{ $post->category->name }}</a>
            @endif
            @if($isFeatured)
                <span class="blog-featured-label">Editor’s pick</span>
            @endif
        </div>

        <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>

        <div class="blog-meta">
            <span>{{ $post->published_at?->format('M j, Y') ?? 'Recently published' }}</span>
            <span>{{ $post->reading_time }} min read</span>
            <span>{{ number_format($post->views) }} views</span>
        </div>

        @if($post->excerpt)
            <p>{{ Str::limit($post->excerpt, $isFeatured ? 190 : 125) }}</p>
        @endif

        <a href="{{ route('blog.show', $post->slug) }}" class="blog-read-link">
            Read article <span aria-hidden="true">→</span>
        </a>
    </div>
</article>
