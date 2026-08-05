<div class="h-full flex flex-col">

    {{-- Top Toolbar --}}
    <div class="border-b p-4 flex gap-4">

        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search media..."
            class="flex-1 rounded-lg border px-4 py-2">

        <select
            wire:model.live="category"
            class="rounded-lg border px-4 py-2 w-56">

            <option value="">All Categories</option>

            @foreach($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
            @endforeach

        </select>

    </div>

    {{-- Main Layout --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- LEFT --}}
        <div class="w-2/3 overflow-y-auto p-6">

            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">

                @foreach($media as $item)

                    <div
                        wire:click="select({{ $item->id }})"
                        class="cursor-pointer rounded-xl border-2 transition
                        {{ $selectedMediaId == $item->id
                            ? 'border-amber-500 ring-2 ring-amber-300'
                            : 'border-gray-200 hover:border-amber-400' }}">

                        <img
                            src="{{ asset('storage/'.$item->path) }}"
                            class="aspect-square w-full object-cover rounded-t-xl">

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

            <div class="mt-6">
                {{ $media->links() }}
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="w-1/3 border-l p-6 bg-gray-50">

            @if($this->selectedMedia)

                <img
                    src="{{ asset('storage/'.$this->selectedMedia->path) }}"
                    class="w-full rounded-xl shadow">

                <div class="mt-6 space-y-2">

                    <div>
                        <strong>Name:</strong><br>
                        {{ $this->selectedMedia->name }}
                    </div>

                    <div>
                        <strong>Dimensions:</strong><br>
                        {{ $this->selectedMedia->width }}
                        ×
                        {{ $this->selectedMedia->height }}
                    </div>

                    @if($this->selectedMedia->title)
                        <div>
                            <strong>Title:</strong><br>
                            {{ $this->selectedMedia->title }}
                        </div>
                    @endif

                </div>

                <button
                    wire:click="useSelected"
                    class="mt-8 w-full rounded-lg bg-amber-500 hover:bg-amber-600 py-3 font-bold">

                    ✓ Use This Image

                </button>

            @else

                <div class="h-full flex items-center justify-center text-gray-500">

                    Select an image from the library.

                </div>

            @endif

        </div>

    </div>

</div>