<div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-5 p-6">

@foreach($media as $item)

<div
    wire:click="select({{ $item->id }})"
    class="cursor-pointer rounded-xl overflow-hidden border-2 transition-all duration-200
    {{ $selected === $item->id
        ? 'border-amber-500 shadow-xl ring-2 ring-amber-300'
        : 'border-gray-200 hover:border-amber-300 hover:shadow-lg' }}">

    <img
        src="{{ asset('storage/'.$item->path) }}"
        class="aspect-square w-full object-cover">

    <div class="p-3">

        <div class="font-semibold truncate">
            {{ $item->name }}
        </div>

        <div class="text-xs text-gray-500">
            {{ $item->width }} × {{ $item->height }}
        </div>

    </div>

</div>

@endforeach

</div>