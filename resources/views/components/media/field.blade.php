@props([
    'label' => 'Image',
    'target',
    'selectedMedia' => null,
    'removeAction' => null,
])

<div class="space-y-4">

    <label class="block text-sm font-semibold">
        {{ $label }}
    </label>

    <button
        type="button"
        wire:click="openMediaPicker('{{ $target }}')"
        class="w-full rounded-lg border border-amber-500 bg-amber-50 hover:bg-amber-100 px-5 py-4 font-semibold">

        Choose from Media Library

    </button>

    @if($selectedMedia)

        <img
            src="{{ asset('storage/'.$selectedMedia->path) }}"
            class="w-full h-56 rounded-xl border object-cover">

    @else

        <div
            class="h-56 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-400">

            No image selected

        </div>

    @endif

    @if($selectedMedia && $removeAction)

        <button
            type="button"
            wire:click="{{ $removeAction }}"
            class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">

            Remove Image

        </button>

    @endif

    <p class="text-xs text-gray-500">
        Images are managed from the Media Library.
    </p>

</div>