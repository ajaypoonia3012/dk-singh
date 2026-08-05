<main class="blog-page">
    <div class="container-xxl">
        <header class="blog-archive-header">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $heading }}</li>
                </ol>
            </nav>
            <span class="blog-kicker">{{ $eyebrow }}</span>
            <h1>{{ $heading }}</h1>
            @if($description)<p>{{ $description }}</p>@endif
        </header>

        <div class="row g-4 g-xl-5 blog-layout">
            <section class="col-lg-8 col-xl-9" aria-label="Articles">
                @if($posts->isNotEmpty())
                    <div class="row g-4">
                        @foreach($posts as $post)
                            <div class="col-md-6 d-flex">@include('blog.partials.card', ['post' => $post, 'featured' => false])</div>
                        @endforeach
                    </div>
                    <div class="blog-pagination">{{ $posts->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
                @else
                    <div class="blog-empty-state"><h2>No articles yet</h2><p>Explore the latest training and nutrition guidance instead.</p><a href="{{ route('blog.index') }}" class="blog-button">Browse the blog</a></div>
                @endif
            </section>
            <div class="col-lg-4 col-xl-3">@include('blog.partials.sidebar')</div>
        </div>
    </div>
</main>
