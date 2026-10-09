<section class="bg-white py-20">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">

            <p class="uppercase tracking-[4px] text-yellow-500 font-bold">
                Blogs
            </p>

            <h2 class="text-5xl font-black mt-4">
                Latest Articles
            </h2>

            <p class="mt-4 text-gray-500">
                Preview of your latest blog posts.
            </p>

        </div>

@php
    $previewGridClass = match($selectedDevice ?? 'desktop') {
        'mobile' => 'wb-grid-1 gap-6',
        'tablet' => 'wb-grid-2 gap-6',
        default => 'wb-grid-3 gap-8',
    };
@endphp

        <div class="{{ $previewGridClass }}">

            @forelse($previewBlogs->take(3) as $blog)

                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

                    @if($blog->id === $selectedBlogId && $blogImage)
                        <img src="{{ $blogImage->temporaryUrl() }}" class="w-full h-60 object-cover" alt="{{ $blog->title }}">
                    @elseif($blog->featured_image)

    <img
        src="{{ asset('storage/'.$blog->featured_image) }}"
        class="w-full h-60 object-cover">

@else

                        <div class="h-60 bg-gray-200"></div>

                    @endif

                    <div class="p-6">

                        <h3 class="text-2xl font-bold">

                            {{ $blog->title }}

                        </h3>

                        <p class="text-gray-500 mt-4">

                            {{ \Illuminate\Support\Str::limit(strip_tags($blog->content),120) }}

                        </p>

                        <button
                            class="mt-6 px-6 py-3 bg-yellow-500 rounded-xl font-bold">

                            Read More

                        </button>

                    </div>

                </div>

            @empty

                <div class="col-span-3 text-center py-20 text-gray-500">

                    No blogs found.

                </div>

            @endforelse

        </div>

    </div>

</section>
