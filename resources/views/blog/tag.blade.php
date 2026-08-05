@extends('layouts.app')

@section('title', $tag->name)

@section('content')

<div class="container py-5">

    <div class="mb-5">

        <h1 class="display-5 fw-bold">

            #{{ $tag->name }}

        </h1>

        <p class="lead text-muted">

            Articles tagged with "{{ $tag->name }}"

        </p>

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