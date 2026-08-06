<section class="newsletter-box text-center">

    <span class="badge bg-warning text-dark px-3 py-2 mb-3">

        Join {{ $setting?->site_name ?: config('app.name') }}

    </span>

    <h2 class="display-5 fw-bold mb-3">

        Never Miss a Fitness Update

    </h2>

    <p class="lead text-white-50 mb-5">

        Get workout plans, fat-loss tips, nutrition guides and exclusive transformation stories delivered directly to your inbox.

    </p>

    <form class="row justify-content-center g-3">

        <div class="col-lg-5">

            <input
                type="email"
                class="form-control form-control-lg"
                placeholder="Enter your email address">

        </div>

        <div class="col-lg-auto">

            <button
                class="blog-btn btn-lg px-5"
                type="button">

                Subscribe

            </button>

        </div>

    </form>

    <div class="row mt-5 text-center">

        <div class="col-md-4">

            <h3 class="fw-bold text-warning">

                20+

            </h3>

            <p class="mb-0">

                Expert Articles

            </p>

        </div>

        <div class="col-md-4">

            <h3 class="fw-bold text-warning">

                10K+

            </h3>

            <p class="mb-0">

                Community Members

            </p>

        </div>

        <div class="col-md-4">

            <h3 class="fw-bold text-warning">

                500+

            </h3>

            <p class="mb-0">

                Transformations

            </p>

        </div>

    </div>

</section>
