@extends('layouts.app')

@section('title','Fitness Blog')

@section('content')

<div class="container-fluid py-5"><div class="container">

    @include('blog.partials.hero')

    <div class="row mt-5 g-5">

        <div class="col-xl-8 col-lg-8">

            @if($featured)

            <section class="mb-5">

                <h2 class="fw-bold mb-4">
                    Featured Article
                </h2>

                @include('blog.partials.card',[
                    'post'=>$featured,
                    'featured'=>true
                ])

            </section>

            @endif

            <section>

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <h2 class="fw-bold">
                        Latest Articles
                    </h2>

                    <span class="text-muted">
                        {{ $posts->total() }} Articles
                    </span>

                </div>

                <div class="row">

                    @foreach($posts as $post)

                        <div class="col-md-6 mb-4">

                            @include('blog.partials.card',[
                                'post'=>$post,
                                'featured'=>false
                            ])

                        </div>

                    @endforeach

                </div>

                <div class="mt-4">

                    {{ $posts->links() }}

                </div>

            </section>

        </div>

        <div class="col-xl-4 col-lg-4">

            @include('blog.partials.sidebar')

        </div>

    </div>

    <div class="mt-5">

        @include('blog.partials.newsletter')

    </div>

</div>

</div>

@endsection