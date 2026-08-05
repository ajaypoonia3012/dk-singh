<div class="blog-card">

    <a href="{{ route('blog.show',$post->slug) }}" class="text-decoration-none">

        <div class="blog-card-image">

            @if($post->media)

                <img
                    src="{{ asset('storage/'.$post->media->path) }}"
                    alt="{{ $post->title }}">

            @else

                <img
                    src="https://placehold.co/1200x700?text=DK+Singh+Fitness"
                    alt="{{ $post->title }}">

            @endif

        </div>

    </a>

    <div class="blog-card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">

            @if($post->category)

                <span class="blog-category">

                    {{ $post->category->name }}

                </span>

            @endif

            @if($featured)

                <span class="badge bg-danger">

                    Featured

                </span>

            @endif

        </div>

        <h3 class="blog-card-title">

            <a href="{{ route('blog.show',$post->slug) }}">

                {{ $post->title }}

            </a>

        </h3>

        <div class="blog-meta mb-3">

            <span>

                {{ optional($post->published_at)->format('d M Y') }}

            </span>

            <span>

                {{ $post->reading_time }} min

            </span>

            <span>

                {{ number_format($post->views) }} views

            </span>

        </div>

        <p class="blog-excerpt">

            {{ Str::limit($post->excerpt,140) }}

        </p>

        <div class="mt-4">

            <a
                href="{{ route('blog.show',$post->slug) }}"
                class="blog-btn text-decoration-none">

                Read Article →

            </a>

        </div>

    </div>

</div>