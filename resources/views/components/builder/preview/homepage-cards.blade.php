<section class="bg-white py-16">
    @php
        $deviceCols = match($selectedDevice ?? 'desktop') {
            'mobile' => 'wb-grid-1',
            'tablet' => 'wb-grid-2',
            default => 'wb-grid-3',
        };
    @endphp
    <div class="max-w-7xl mx-auto px-6">
        <div class="{{ $deviceCols }} gap-6">
            @forelse($previewHomepageCards as $card)
                @php
                    $backgroundMedia = ! empty($card->background_media_id)
                        ? collect($homepageCardBackgroundOptions)->firstWhere('id', $card->background_media_id)
                        : null;
                @endphp

                <article class="bg-white rounded-3xl overflow-hidden border border-gray-200 shadow-sm">
                    @if($card->id === $selectedHomepageCardId && $homepageCardImage)
                        <img src="{{ $homepageCardImage->temporaryUrl() }}" alt="{{ $card->title }}" class="w-full h-56 object-cover">
                    @elseif($backgroundMedia)
                        <img
                            src="{{ asset('storage/'.$backgroundMedia->path) }}"
                            alt="{{ $card->title }}"
                            class="w-full h-56 object-cover">
                    @elseif($card->background_image)
                        <img
                            src="{{ asset('storage/'.$card->background_image) }}"
                            alt="{{ $card->title }}"
                            class="w-full h-56 object-cover">
                    @endif

                    <div class="p-8">
                        @if($card->icon)
                            <div class="text-5xl mb-5">{{ $card->icon }}</div>
                        @endif

                        <h3 class="text-2xl font-bold mb-2">{{ $card->title }}</h3>
                        <p class="text-yellow-500 font-semibold mb-4">{{ $card->subtitle }}</p>
                        <p class="text-gray-600 leading-7 mb-6">{{ $card->description }}</p>

                        @if($card->button_text)
                            <a
                                href="{{ $card->button_link ?: '#' }}"
                                class="inline-flex px-6 py-3 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-black font-bold">
                                {{ $card->button_text }}
                            </a>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-20 text-gray-500">
                    No Homepage Cards Found
                </div>
            @endforelse
        </div>
    </div>
</section>
