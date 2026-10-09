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

<div>
    <label class="block text-sm font-semibold mb-2">Secondary Button Link</label>
    <input wire:model.live="hero.view_button_link" class="w-full rounded-lg border p-3">
</div>

<div>
    <label class="block text-sm font-semibold mb-2">Background Video URL</label>
    <input type="url" wire:model.live="hero.video" class="w-full rounded-lg border p-3">
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold mb-2">Overlay Color</label>
        <input type="color" wire:model.live="hero.overlay_color" class="w-full h-12 rounded-lg border p-1">
    </div>
    <div>
        <label class="block text-sm font-semibold mb-2">Overlay Opacity</label>
        <input type="number" min="0" max="100" wire:model.live="hero.overlay_opacity" class="w-full rounded-lg border p-3">
    </div>
</div>

<div>
    <label class="block text-sm font-semibold mb-2">Template</label>
    <select wire:model.live="hero.template" class="w-full rounded-lg border p-3">
        <option value="default">Default</option>
        <option value="modern">Modern</option>
    </select>
</div>

<label class="flex items-center gap-3">
    <input type="checkbox" wire:model.live="hero.enabled" class="rounded border-gray-300">
    <span class="text-sm font-semibold">Hero enabled</span>
</label>


    
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
        class="wb-btn-primary w-full py-3 rounded-xl font-black text-sm tracking-wide shadow-md transition"
        style="background-color: #f59e0b !important; color: #000000 !important;">
        Save Hero Section
    </button>


</div>
