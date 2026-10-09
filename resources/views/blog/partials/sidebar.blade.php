<div class="sticky-top" style="top:100px;">

    {{-- Fitness Platform Hub Banner --}}
    <div class="blog-widget p-3 rounded-4 bg-light border mb-4">
        <span class="badge bg-warning text-dark mb-2">New Platform Hub</span>
        <h5 class="fw-bold mb-1">DK Singh Fitness Hub</h5>
        <p class="text-muted small mb-3">Explore our structured training pillars, exercise database, and cardio protocols.</p>
        <div class="d-grid gap-2">
            <a href="{{ route('fitness.index') }}" class="btn btn-primary btn-sm rounded-pill fw-bold">
                <i class="bi bi-compass me-1"></i>Visit Fitness Hub
            </a>
            <a href="{{ route('fitness.exercise-library') }}" class="btn btn-outline-dark btn-sm rounded-pill fw-bold">
                <i class="bi bi-shield-shaded me-1"></i>Exercise Library (25+)
            </a>
        </div>
    </div>

    {{-- Search --}}

    <div class="blog-widget">

        <h4>Search Articles</h4>

        <form action="{{ route('blog.index') }}" method="GET">

            <div class="input-group">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="theme-form-control form-control"
                    placeholder="Search fitness articles...">

                <button class="blog-btn" type="submit">

                    Search

                </button>

            </div>

        </form>

    </div>

    {{-- Categories --}}

    @if(!empty($categories) && count($categories) > 0)
    <div class="blog-widget">

        <h4>Categories</h4>

        @foreach($categories as $category)

            <a
                href="{{ route('blog.category',$category->slug) }}"
                class="d-flex justify-content-between align-items-center text-decoration-none text-dark py-2 border-bottom">

                <span>

                    {{ $category->name }}

                </span>

                <span class="badge bg-warning text-dark rounded-pill">

                    {{ $category->posts_count ?? $category->posts()->count() }}

                </span>

            </a>

        @endforeach

    </div>
    @endif

    {{-- Popular Posts --}}

    <div class="blog-widget">

        <h4>Popular Articles</h4>

        @foreach(
            \App\Models\BlogPost::where('status',true)
                ->orderByDesc('views')
                ->take(5)
                ->get()
            as $popular
        )

            <div class="mb-4">

                <a
                    href="{{ route('blog.show',$popular->slug) }}"
                    class="fw-bold text-dark text-decoration-none d-block">

                    {{ $popular->title }}

                </a>

                <small class="text-muted">

                    {{ number_format($popular->views) }} views

                </small>

            </div>

        @endforeach

    </div>

    {{-- Tags --}}

    @if(!empty($tags) && count($tags) > 0)
    <div class="blog-widget">

        <h4>Popular Tags</h4>

        <div class="d-flex flex-wrap gap-2">

            @foreach($tags as $tag)

                <a
                    href="{{ route('blog.tag',$tag->slug) }}"
                    class="blog-category text-decoration-none">

                    {{ $tag->name }}

                </a>

            @endforeach

        </div>

    </div>
    @endif

</div>
