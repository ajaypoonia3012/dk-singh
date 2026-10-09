@extends('layouts.app')

@section('title', $category->name . ' Articles & Guides | DK Singh Fitness')
@section('meta_description', Str::limit(strip_tags($category->description ?? 'Explore expert fitness and nutrition guides in ' . $category->name . ' on DK Singh Fitness.'), 155))

@section('content')

<div class="container py-5">

    <div class="mb-5">

        <h1 class="display-5 fw-bold">

            {{ $category->name }}

        </h1>

        @if($category->description)

            <p class="lead text-muted">

                {{ $category->description }}

            </p>

        @endif

    </div>

    <div class="row">

        <div class="col-lg-8">

            <div class="row g-4">

                @foreach($posts as $post)

                    <div class="col-md-6">

                        @include('blog.partials.card', [
                            'post' => $post,
                            'featured' => false,
                        ])

                    </div>

                @endforeach

            </div>

            <div class="mt-5">

                {{ $posts->links() }}

            </div>

        </div>

        <div class="col-lg-4">

            @include('blog.partials.sidebar')

        </div>

    </div>

</div>

@endsection
