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
                    class="flex-1 text-left p-3 rounded-xl border transition wb-card-selector-btn {{ $selectedHomepageCardId == $card->id ? 'active' : 'bg-white hover:bg-slate-50 border-slate-200' }}"
                    style="{{ $selectedHomepageCardId == $card->id ? 'background-color: #fef3c7 !important; border-color: #f59e0b !important; color: #78350f !important; font-weight: 700;' : '' }}">

                    <div class="font-bold text-xs truncate">
                        {{ $card->title ?: 'Untitled Card' }}
                    </div>

                    <div class="text-[11px] opacity-75 truncate">
                        {{ $card->subtitle ?: 'No subtitle' }}
                    </div>

                </button>

                {{-- Duplicate --}}
                <button
                    wire:click="duplicateHomepageCard({{ $card->id }})"
                    class="p-2.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-xs font-semibold text-slate-700 transition"
                    title="Duplicate card">
                    📋
                </button>

                {{-- Move Up --}}
                <button
                    wire:click="moveHomepageCardUp({{ $card->id }})"
                    class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-700 transition"
                    title="Move up">
                    ▲
                </button>

                {{-- Move Down --}}
                <button
                    wire:click="moveHomepageCardDown({{ $card->id }})"
                    class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-700 transition"
                    title="Move down">
                    ▼
                </button>

                <button
                    wire:click="deleteHomepageCard({{ $card->id }})"
                    wire:confirm="Delete this Homepage Card?"
                    class="w-8 h-8 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center text-xs font-bold transition"
                    title="Delete card">
                    🗑️
                </button>

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
    remove-action="removeHomepageCardImage"
/>

        <div>
            <label class="block text-sm font-semibold mb-2">Upload Card Image</label>
            <input type="file" wire:model="homepageCardImage" accept="image/jpeg,image/png,image/webp" class="w-full rounded-lg border p-3">
            @error('homepageCardImage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button
            wire:click="saveHomepageCard"
            class="wb-btn-primary w-full py-3 rounded-xl font-black text-sm tracking-wide shadow-md transition"
            style="background-color: #f59e0b !important; color: #000000 !important;">
            💾 Save Homepage Card
        </button>

    @endif

</div>
