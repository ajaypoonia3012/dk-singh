<div class="space-y-6">

    <h3 class="text-xl font-bold">
        Hero Settings
    </h3>

    <div>
        <label class="block text-sm font-semibold mb-2">
            Hero Title
        </label>

        <textarea
            wire:model.live="hero.heading"
            class="w-full rounded-lg border p-3"
            rows="3"></textarea>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">
            Hero Subtitle
        </label>

        <textarea
            wire:model.live="hero.subheading"
            class="w-full rounded-lg border p-3"
            rows="4"></textarea>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">
            Button Text
        </label>

        <input
            wire:model.live="hero.button_text"
            class="w-full rounded-lg border p-3">
    </div>

{{-- HERO BACKGROUND --}}

@php
    $selectedMedia = collect($heroBackgroundOptions)
        ->firstWhere('id', $heroBackgroundMediaId);
@endphp

<x-media.field
    label="Hero Background"
    :selected-media="$selectedMedia"
    target="hero"
    remove-action="deleteHeroImage"
/>

<div>

    <label class="block text-sm font-semibold mb-2">
        Button Link
        </label>

        <input
            wire:model.live="hero.button_link"
            class="w-full rounded-lg border p-3">
    </div>

<div>

    <label class="block text-sm font-semibold mb-2">

        View Transformations Button

    </label>

    <input

        wire:model.live="hero.view_button_text"

        class="w-full rounded-lg border p-3">

</div>


    
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">

    <div>
        <label class="block text-sm font-semibold mb-2">
            Followers Label
        </label>

        <input
            wire:model.live="hero.followers_label"
            class="w-full rounded-lg border p-3">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">
            Years Label
        </label>

        <input
            wire:model.live="hero.years_label"
            class="w-full rounded-lg border p-3">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">
            Transformations Label
        </label>

        <input
            wire:model.live="hero.transformations_label"
            class="w-full rounded-lg border p-3">
    </div>

</div>
<button
        wire:click="saveHero"
        class="w-full bg-amber-500 hover:bg-amber-600 text-black py-3 rounded-lg font-bold">

        Save Hero

    </button>


</div>