<div class="space-y-6">

    <div class="flex items-center justify-between">

        <h3 class="text-xl font-bold">
            Programs
        </h3>

        <button
            wire:click="addProgram"
            class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">

            ➕ Add

        </button>

    </div>

    {{-- Program List --}}
    <div class="space-y-2">

        @foreach($programs as $item)

            <div class="flex items-center gap-2">

                <button
                    wire:click="selectProgram({{ $item->id }})"
                    class="flex-1 text-left p-3 rounded-lg border transition
                    {{ $selectedProgramId == $item->id
                        ? 'bg-amber-500 text-white border-amber-500'
                        : 'bg-white hover:bg-gray-50' }}">

                    <div class="font-semibold">

                        {{ $item->title }}

                    </div>

                    <div class="text-xs opacity-70">

                        {{ $item->category }}

                    </div>

                </button>

                <button
                    wire:click="moveProgramUp({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬆

                </button>

                <button
                    wire:click="moveProgramDown({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬇

                </button>

                <button
                    wire:click="duplicateProgram({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border border-blue-500 text-blue-600">

                    📄

                </button>

                <button
                    wire:click="toggleProgramStatus({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border">

                    {{ $item->status ? '👁' : '🚫' }}

                </button>

                <button
                    wire:click="deleteProgram({{ $item->id }})"
                    wire:confirm="Delete Program?"
                    class="px-3 py-3 rounded-lg border border-red-500 text-red-600">

                    🗑

                </button>

            </div>

        @endforeach

    </div>

    <hr>

    @if($program)

        <div>

            <label class="block text-sm font-semibold mb-2">

                Title

            </label>

            <input
                wire:model.live="program.title"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Slug

            </label>

            <input
                wire:model.live="program.slug"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Category

            </label>

            <input
                wire:model.live="program.category"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Duration

            </label>

            <input
                wire:model.live="program.duration"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Price

            </label>

            <input
                type="number"
                step="0.01"
                wire:model.live="program.price"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Description

            </label>

            <textarea
                rows="5"
                wire:model.live="program.description"
                class="w-full rounded-lg border p-3"></textarea>

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                Program Image

            </label>

            @if(!empty($program['image']))

                <img
                    src="{{ asset('storage/'.$program['image']) }}"
                    class="w-full h-40 object-cover rounded-xl border mb-3">

            @endif

            <input
                type="file"
                wire:model="programImage"
                class="w-full rounded-lg border p-2">

        </div>

        <label class="flex items-center gap-3">

            <input
                type="checkbox"
                wire:model.live="program.featured">

            Featured Program

        </label>

        <div>

            <label class="block text-sm font-semibold mb-2">

                SEO Title

            </label>

            <input
                wire:model.live="program.seo_title"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">

                SEO Description

            </label>

            <textarea
                rows="3"
                wire:model.live="program.seo_description"
                class="w-full rounded-lg border p-3"></textarea>

        </div>

        <button
            wire:click="saveProgram"
            class="w-full bg-amber-500 hover:bg-amber-600 text-black py-3 rounded-lg font-bold">

            💾 Save Program

        </button>

    @endif

</div>