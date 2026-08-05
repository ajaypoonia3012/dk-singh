<div class="bg-white rounded-xl shadow h-full overflow-y-auto">

    <div class="border-b p-4">

        <h3 class="text-lg font-bold">
            Website Sections
        </h3>

        <p class="text-sm text-gray-500 mt-1">
            Click a section to edit it.
        </p>

    </div>

    <div class="p-3 space-y-3">

        @foreach($sections as $section)

            <div
                class="rounded-xl border transition

                {{ optional($selectedSection)->id == $section->id
                    ? 'border-amber-500 bg-amber-50'
                    : 'border-gray-200 bg-white'
                }}">

                <button
                    wire:click="selectSection({{ $section->id }})"
                    class="w-full text-left p-3">

                    <div class="flex justify-between items-start">

                        <div>

                            <div class="font-semibold">

                                {{ $section->title }}

                            </div>

                            <div class="text-xs text-gray-500">

                                {{ ucfirst($section->template) }}

                            </div>

                        </div>

                        <div class="text-lg">

                            @if($section->enabled)

                                🟢

                            @else

                                🔴

                            @endif

                        </div>

                    </div>

                </button>

                <div class="border-t px-3 py-2 flex justify-between items-center">

                    <div class="flex gap-2">

                        <button
                            wire:click="moveUp({{ $section->id }})"
                            class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">

                            ⬆

                        </button>

                        <button
                            wire:click="moveDown({{ $section->id }})"
                            class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">

                            ⬇

                        </button>

                    </div>

                    <span class="text-xs text-gray-400">

                        #{{ $section->sort_order }}

                    </span>

                </div>

            </div>

        @endforeach

    </div>

</div>