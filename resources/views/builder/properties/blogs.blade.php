<div class="space-y-6">

    <div class="flex items-center justify-between">

        <h3 class="text-xl font-bold">

            Blogs

        </h3>

        <button
            wire:click="addBlog"
            class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">

            ➕ Add

        </button>

    </div>

    {{-- Blog List --}}
    <div class="space-y-2">

        @foreach($blogs as $item)

            <div class="flex items-center gap-2">

                <button
                    wire:click="selectBlog({{ $item->id }})"
                    class="flex-1 text-left p-3 rounded-xl border transition wb-card-selector-btn {{ $selectedBlogId == $item->id ? 'active' : 'bg-white hover:bg-slate-50 border-slate-200' }}"
                    style="{{ $selectedBlogId == $item->id ? 'background-color: #fef3c7 !important; border-color: #f59e0b !important; color: #78350f !important; font-weight: 700;' : '' }}">

                    <div class="font-bold text-xs truncate">
                        {{ $item->title ?: 'Untitled Blog' }}
                    </div>

                    <div class="text-[11px] opacity-75 truncate">
                        {{ $item->category ?: 'General' }}
                    </div>

                </button>

                <button
                    wire:click="moveBlogUp({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬆

                </button>

                <button
                    wire:click="moveBlogDown({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬇

                </button>

                <button
                    wire:click="duplicateBlog({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border border-blue-500 text-blue-600">

                    📄

                </button>

                <button
                    wire:click="toggleBlogFeatured({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border">

                    {{ $item->featured ? '⭐' : '☆' }}

                </button>

                <button
                    wire:click="toggleBlogStatus({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border">

                    {{ $item->status ? '👁' : '🚫' }}

                </button>

                <button
                    wire:click="deleteBlog({{ $item->id }})"
                    wire:confirm="Delete Blog?"
                    class="px-3 py-3 rounded-lg border border-red-500 text-red-600">

                    🗑

                </button>

            </div>

        @endforeach

    </div>

    <hr>

    @if($blog)

        <div>
            <label class="block text-sm font-semibold mb-2">Title</label>

            <input
                wire:model.live="blog.title"
                class="w-full rounded-lg border p-3">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Category</label>

            <input
                wire:model.live="blog.category"
                class="w-full rounded-lg border p-3">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Author</label>

            <input
                wire:model.live="blog.author"
                class="w-full rounded-lg border p-3">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Reading Time</label>

            <input
                wire:model.live="blog.reading_time"
                class="w-full rounded-lg border p-3">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Excerpt</label>

            <textarea
                rows="3"
                wire:model.live="blog.excerpt"
                class="w-full rounded-lg border p-3"></textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Content</label>

            <textarea
                rows="10"
                wire:model.live="blog.content"
                class="w-full rounded-lg border p-3"></textarea>
        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Featured Image

            </label>

            @if(!empty($blog['featured_image']))

                <img
                    src="{{ asset('storage/'.$blog['featured_image']) }}"
                    class="w-full h-48 object-cover rounded-xl border mb-3">

            @endif

            <input
                type="file"
                wire:model="blogImage"
                class="w-full rounded-lg border p-2">

        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">SEO Title</label>

            <input
                wire:model.live="blog.seo_title"
                class="w-full rounded-lg border p-3">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">SEO Description</label>

            <textarea
                rows="3"
                wire:model.live="blog.seo_description"
                class="w-full rounded-lg border p-3"></textarea>
        </div>

        <button
            wire:click="saveBlog"
            class="w-full wb-btn-primary py-3 rounded-xl font-bold flex items-center justify-center gap-2 shadow-sm"
            style="background: #f59e0b !important; color: #000000 !important; font-weight: 700 !important;">

            <span>💾</span> Save Blog

        </button>

    @endif

</div>