@php
    $heroMedia = $featured?->media;
@endphp

<section class="blog-hero">
    @if($heroMedia)
        <img class="blog-hero__background" src="{{ $heroMedia->url }}" alt="" width="{{ $heroMedia->width ?: 1600 }}" height="{{ $heroMedia->height ?: 900 }}" fetchpriority="high" decoding="async">
    @endif
    <span class="blog-hero__overlay" aria-hidden="true"></span>
    <div class="blog-hero__content">
        <span class="blog-eyebrow">Train smarter. Live stronger.</span>
        <h1>Evidence-led fitness for results that last.</h1>
        <p>Practical training, nutrition, recovery, and transformation advice from DK Singh Fitness.</p>

        <form action="{{ route('blog.index') }}" method="GET" class="blog-search" role="search">
            <label class="visually-hidden" for="hero-blog-search">Search fitness articles</label>
            <input id="hero-blog-search" type="search" name="search" value="{{ request('search') }}" placeholder="What do you want to improve?">
            <button type="submit">Search articles</button>
        </form>

        <div class="blog-stats" aria-label="Blog statistics">
            <div><strong>{{ number_format($posts->total() + ($featured ? 1 : 0)) }}+</strong><span>Expert articles</span></div>
            <div><strong>{{ number_format($categories->count()) }}</strong><span>Fitness topics</span></div>
            <div><strong>100%</strong><span>Actionable advice</span></div>
        </div>
    </div>

    <a class="blog-hero__cta" href="#latest-articles">Explore the latest <span aria-hidden="true">↓</span></a>
</section>
