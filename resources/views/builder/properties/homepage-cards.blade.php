<div class="space-y-6">

    <div class="flex items-center justify-between">

    <h3 class="text-xl font-bold">

        Homepage Cards

    </h3>

    <button
        wire:click="addHomepageCard"
        class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">

        ➕ Add

    </button>

</div>

    {{-- Card Selector --}}
    <div class="space-y-2">

        @foreach($homepageCards as $card)

            <div class="flex items-center gap-2">

                {{-- Select Card --}}
                <button
                    wire:click="selectHomepageCard({{ $card->id }})"
                    class="flex-1 text-left p-3 rounded-lg border transition
                    {{ $selectedHomepageCardId == $card->id
                        ? 'bg-amber-500 text-white border-amber-500'
                        : 'bg-white hover:bg-gray-50' }}">

                    <div class="font-semibold">

                        {{ $card->title }}

                    </div>

                    <div class="text-xs opacity-70">

                        {{ $card->subtitle }}

                    </div>

                </button>

                {{-- Move Up --}}
                <button
                    wire:click="moveHomepageCardUp({{ $card->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬆

                </button>

                {{-- Move Down --}}
                <button
                    wire:click="moveHomepageCardDown({{ $card->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬇

                </button>

<button
    wire:click="deleteHomepageCard({{ $card->id }})"
    wire:confirm="Delete this Homepage Card?"
    class="px-3 py-3 rounded-lg border border-red-500 text-red-600 hover:bg-red-50">

    🗑

</button>


<button
    wire:click="toggleHomepageCard({{ $card->id }})"
    class="px-3 py-3 rounded-lg border
    {{ $card->is_active
        ? 'border-green-500 text-green-600 hover:bg-green-50'
        : 'border-gray-400 text-gray-500 hover:bg-gray-100' }}">

    {{ $card->is_active ? '✅' : '🚫' }}

</button>

            </div>

        @endforeach

    </div>

    <hr>

    @if($homepageCard)

        <div>

            <label class="block text-sm font-semibold mb-2">

                Title

            </label>

            <input
                wire:model.live="homepageCard.title"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Subtitle

            </label>

            <input
                wire:model.live="homepageCard.subtitle"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Description

            </label>

            <textarea
                rows="5"
                wire:model.live="homepageCard.description"
                class="w-full rounded-lg border p-3"></textarea>

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Button Text

            </label>

            <input
                wire:model.live="homepageCard.button_text"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Button Link

            </label>

            <input
                wire:model.live="homepageCard.button_link"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Icon

            </label>

            <input
                wire:model.live="homepageCard.icon"
                class="w-full rounded-lg border p-3">

        </div>

        @php
    $selectedMedia = collect($homepageCardBackgroundOptions)
        ->firstWhere('id', $homepageCardBackgroundMediaId);
@endphp

<x-media.field
    label="Card Image"
    target="homepage-card"
    :selected-media="$selectedMedia"
/>

        <button
            wire:click="saveHomepageCard"
            class="w-full bg-amber-500 hover:bg-amber-600 text-black py-3 rounded-lg font-bold">

            💾 Save Homepage Card

        </button>

    @endif

</div>