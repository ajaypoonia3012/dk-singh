@extends('layouts.app')

@section('title', 'Fitness Blog')
@section('meta_title', 'Fitness, Nutrition & Transformation Blog | DK Singh Fitness')
@section('meta_description', 'Evidence-led training, nutrition, recovery, and transformation advice from DK Singh Fitness.')
@section('canonical', route('blog.index'))

@section('content')
    <main class="blog-page">
        <div class="container-xxl">
            @include('blog.partials.hero')

            <div class="row g-4 g-xl-5 blog-layout">
                <div class="col-lg-8 col-xl-9">
                    @if($featured && !$search)
                        <section class="blog-section" aria-labelledby="featured-heading">
                            <div class="blog-section-heading">
                                <div>
                                    <span class="blog-kicker">Start here</span>
                                    <h2 id="featured-heading">Featured article</h2>
                                </div>
                            </div>
                            @include('blog.partials.card', ['post' => $featured, 'featured' => true])
                        </section>
                    @endif

                    <section id="latest-articles" class="blog-section" aria-labelledby="latest-heading">
                        <div class="blog-section-heading">
                            <div>
                                <span class="blog-kicker">Knowledge into action</span>
                                <h2 id="latest-heading">{{ $search ? 'Search results' : 'Latest articles' }}</h2>
                            </div>
                            <span>{{ number_format($posts->total()) }} {{ Str::plural('article', $posts->total()) }}</span>
                        </div>

                        @if($search)
                            <div class="blog-search-summary">
                                Results for <strong>“{{ $search }}”</strong>
                                <a href="{{ route('blog.index') }}">Clear search</a>
                            </div>
                        @endif

                        @if($posts->isNotEmpty())
                            <div class="row g-4">
                                @foreach($posts as $post)
                                    <div class="col-md-6 d-flex">
                                        @include('blog.partials.card', ['post' => $post, 'featured' => false])
                                    </div>
                                @endforeach
                            </div>
                            <div class="blog-pagination">{{ $posts->onEachSide(1)->links('pagination::bootstrap-5') }}</div>
                        @else
                            <div class="blog-empty-state">
                                <h3>No articles found</h3>
                                <p>Try a broader training, nutrition, or recovery term.</p>
                                <a href="{{ route('blog.index') }}" class="blog-button">View all articles</a>
                            </div>
                        @endif
                    </section>
                </div>

                <div class="col-lg-4 col-xl-3">@include('blog.partials.sidebar')</div>
            </div>

            @include('blog.partials.newsletter')
        </div>
    </main>
@endsection
