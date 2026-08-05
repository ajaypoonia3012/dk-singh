@if($relatedPosts->isNotEmpty())
    <section class="blog-related" aria-labelledby="related-heading">
        <div class="blog-section-heading">
            <div><span class="blog-kicker">Keep progressing</span><h2 id="related-heading">Related articles</h2></div>
        </div>
        <div class="row g-4">
            @foreach($relatedPosts as $relatedPost)
                <div class="col-md-6 col-xl-4 d-flex">
                    @include('blog.partials.card', ['post' => $relatedPost, 'featured' => false])
                </div>
            @endforeach
        </div>
    </section>
@endif
