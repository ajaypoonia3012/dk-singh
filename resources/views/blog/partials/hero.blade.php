<section class="blog-hero">

    <div style="max-width:700px;">

        <span class="badge bg-warning text-dark px-3 py-2 mb-4">

            {{ $setting?->site_name ?: config('app.name') }}

        </span>

        <h1 class="display-3 fw-bold mb-4">

            Fitness Articles,
            Nutrition Guides &
            Real Transformations

        </h1>

        <p class="lead text-white mb-5">

            Discover science-based fitness advice, workout plans,
            nutrition strategies and real transformation stories.

        </p>

        <form
            action="{{ route('blog.index') }}"
            method="GET"
            class="blog-search">

            <div class="input-group input-group-lg">

                <input
                    class="form-control"
                    name="search"
                    placeholder="Search articles..."
                    value="{{ request('search') }}">

                <button class="btn btn-warning">

                    Search

                </button>

            </div>

        </form>

    </div>

</section>
