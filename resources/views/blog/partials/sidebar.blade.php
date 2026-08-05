<aside class="blog-sidebar" aria-label="Blog sidebar">
    <section class="blog-widget">
        <h2>Search</h2>
        <form action="{{ route('blog.index') }}" method="GET" class="blog-widget__search" role="search">
            <label class="visually-hidden" for="sidebar-blog-search">Search articles</label>
            <input id="sidebar-blog-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search articles">
            <button type="submit" aria-label="Submit search">→</button>
        </form>
    </section>

    <section class="blog-widget">
        <h2>Categories</h2>
        <nav class="blog-category-list" aria-label="Blog categories">
            @forelse($categories as $category)
                <a href="{{ route('blog.category', $category->slug) }}">
                    <span>{{ $category->name }}</span>
                    <span>{{ $category->published_posts_count }}</span>
                </a>
            @empty
                <p class="blog-widget__empty">Categories are coming soon.</p>
            @endforelse
        </nav>
    </section>

    <section class="blog-widget">
        <h2>Popular reads</h2>
        <div class="blog-popular-list">
            @forelse($popularPosts as $popular)
                <a href="{{ route('blog.show', $popular->slug) }}" class="blog-popular-item">
                    @if($popular->media)
                        <img src="{{ $popular->media->url }}" alt="" width="84" height="84" loading="lazy" decoding="async">
                    @else
                        <span class="blog-popular-item__fallback">DK</span>
                    @endif
                    <span>
                        <strong>{{ Str::limit($popular->title, 62) }}</strong>
                        <small>{{ number_format($popular->views) }} views</small>
                    </span>
                </a>
            @empty
                <p class="blog-widget__empty">Popular articles will appear here.</p>
            @endforelse
        </div>
    </section>

    @if($tags->isNotEmpty())
        <section class="blog-widget">
            <h2>Explore topics</h2>
            <div class="blog-tag-list">
                @foreach($tags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}">#{{ $tag->name }}</a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="blog-widget blog-widget--newsletter">
        <span class="blog-eyebrow">Weekly coaching notes</span>
        <h2>Build your strongest week.</h2>
        <p>Get practical guidance and new articles from DK Singh Fitness.</p>
        <a href="{{ url('/contact') }}" class="blog-button">Join the community</a>
    </section>
</aside>
