@if($relatedPosts->count())

<section class="mt-5">

    <h2 class="fw-bold mb-4">

        Related Articles

    </h2>

    <div class="row g-4">

        @foreach($relatedPosts as $post)

            <div class="col-md-4">

                @include('blog.partials.card', [
                    'post' => $post,
                    'featured' => false,
                ])

            </div>

        @endforeach

    </div>

</section>

@endif