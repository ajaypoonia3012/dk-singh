<div class="sticky-top" style="top:100px;">

    {{-- Search --}}

    <div class="blog-widget">

        <h4>Search Articles</h4>

        <form action="{{ route('blog.index') }}" method="GET">

            <div class="input-group">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Search fitness articles...">

                <button class="blog-btn" type="submit">

                    Search

                </button>

            </div>

        </form>

    </div>

    {{-- Categories --}}

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

                    {{ $category->posts()->count() }}

                </span>

            </a>

        @endforeach

    </div>

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

</div>