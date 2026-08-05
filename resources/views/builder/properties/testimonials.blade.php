<div class="space-y-6">

    <div class="flex items-center justify-between">

        <h3 class="text-xl font-bold">

            Testimonials

        </h3>

        <button
            wire:click="addTestimonial"
            class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">

            ➕ Add

        </button>

    </div>

    {{-- Testimonial List --}}
    <div class="space-y-2">

        @foreach($testimonials as $item)

            <div class="flex items-center gap-2">

                <button
                    wire:click="selectTestimonial({{ $item->id }})"
                    class="flex-1 text-left p-3 rounded-lg border transition
                    {{ $selectedTestimonialId == $item->id
                        ? 'bg-amber-500 text-white border-amber-500'
                        : 'bg-white hover:bg-gray-50' }}">

                    <div class="font-semibold">

                        {{ $item->name }}

                    </div>

                    <div class="text-xs opacity-70">

                        {{ $item->profession }}

                    </div>

                </button>

                <button
                    wire:click="moveTestimonialUp({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬆

                </button>

                <button
                    wire:click="moveTestimonialDown({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬇

                </button>

                <button
                    wire:click="duplicateTestimonial({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border border-blue-500 text-blue-600">

                    📄

                </button>

                <button
                    wire:click="toggleTestimonialFeatured({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border">

                    {{ $item->featured ? '⭐' : '☆' }}

                </button>

                <button
                    wire:click="toggleTestimonialStatus({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border">

                    {{ $item->status ? '👁' : '🚫' }}

                </button>

                <button
                    wire:click="deleteTestimonial({{ $item->id }})"
                    wire:confirm="Delete Testimonial?"
                    class="px-3 py-3 rounded-lg border border-red-500 text-red-600">

                    🗑

                </button>

            </div>

        @endforeach

    </div>

    <hr>

    @if($testimonial)

        <div>

            <label class="block text-sm font-semibold mb-2">

                Client Name

            </label>

            <input
                wire:model.live="testimonial.name"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Profession

            </label>

            <input
                wire:model.live="testimonial.profession"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Location

            </label>

            <input
                wire:model.live="testimonial.location"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Transformation

            </label>

            <input
                wire:model.live="testimonial.transformation"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Program

            </label>

            <input
                wire:model.live="testimonial.program"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Rating (1–5)

            </label>

            <input
                type="number"
                min="1"
                max="5"
                wire:model.live="testimonial.rating"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Review

            </label>

            <textarea
                rows="5"
                wire:model.live="testimonial.review"
                class="w-full rounded-lg border p-3"></textarea>

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Client Image

            </label>

            @if(!empty($testimonial['image']))

                <img
                    src="{{ asset('storage/'.$testimonial['image']) }}"
                    class="w-32 h-32 rounded-full object-cover border mb-3">

            @endif

            <input
                type="file"
                wire:model="testimonialImage"
                class="w-full rounded-lg border p-2">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                SEO Title

            </label>

            <input
                wire:model.live="testimonial.seo_title"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                SEO Description

            </label>

            <textarea
                rows="3"
                wire:model.live="testimonial.seo_description"
                class="w-full rounded-lg border p-3"></textarea>

        </div>

        <button
            wire:click="saveTestimonial"
            class="w-full bg-amber-500 hover:bg-amber-600 text-black py-3 rounded-lg font-bold">

            💾 Save Testimonial

        </button>

    @endif

</div>