@extends('layouts.app')

@section('title', $post->seo_title ?: $post->title)

@section('content')

<div class="container py-5">

    <div class="row">

        <div class="col-lg-8">

            <article>

                @if($post->media)

                    <img
                        src="{{ asset('storage/'.$post->media->path) }}"
                        class="img-fluid rounded-4 mb-4 w-100"
                        alt="{{ $post->title }}">

                @endif

                <div class="mb-3">

                    @if($post->category)

                        <span class="badge bg-warning text-dark">

                            {{ $post->category->name }}

                        </span>

                    @endif

                </div>

                <h1 class="display-5 fw-bold mb-3">

                    {{ $post->title }}

                </h1>

                <div class="text-muted mb-4">

                    By <strong>{{ $post->author }}</strong>

                    •

                    {{ optional($post->published_at)->format('d M Y') }}

                    •

                    {{ $post->reading_time }} min read

                    •

                    {{ number_format($post->views) }} views

                </div>

                @if($post->excerpt)

                    <div class="lead mb-4">

                        {{ $post->excerpt }}

                    </div>

                @endif

                <div class="blog-content">

                    {!! $post->content !!}

                </div>

                @if($post->tags->count())

                    <hr class="my-5">

                    <h5 class="mb-3">

                        Tags

                    </h5>

                    @foreach($post->tags as $tag)

                        <a
                            href="{{ route('blog.tag', $tag->slug) }}"
                            class="badge bg-light text-dark text-decoration-none me-2 mb-2">

                            {{ $tag->name }}

                        </a>

                    @endforeach

                @endif

            </article>

            @include('blog.partials.related-posts')

        </div>

        <div class="col-lg-4">

            @include('blog.partials.sidebar')

        </div>

    </div>

</div>

@endsection