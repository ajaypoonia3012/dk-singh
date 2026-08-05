<div class="space-y-6">

    <h3 class="text-xl font-bold">
        Contact Settings
    </h3>

    <div>
        <label class="block text-sm font-semibold mb-2">
            Contact Label
        </label>

        <input
            wire:model.live="contact.contact_title"
            class="w-full border rounded-lg p-3">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">
            Heading
        </label>

        <input
            wire:model.live="contact.contact_heading"
            class="w-full border rounded-lg p-3">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">
            Description
        </label>

        <textarea
            rows="4"
            wire:model.live="contact.contact_description"
            class="w-full border rounded-lg p-3"></textarea>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">
            Google Map Embed URL
        </label>

        <textarea
            rows="4"
            wire:model.live="contact.map_embed_url"
            class="w-full border rounded-lg p-3"></textarea>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">
            Google Maps Link
        </label>

        <input
            wire:model.live="contact.map_link"
            class="w-full border rounded-lg p-3">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-2">
            Map Button Text
        </label>

        <input
            wire:model.live="contact.contact_map_text"
            class="w-full border rounded-lg p-3">
    </div>

    <button
        wire:click="saveContact"
        class="w-full bg-amber-500 hover:bg-amber-600 text-black py-3 rounded-lg font-bold">

        💾 Save Contact

    </button>

</div>
