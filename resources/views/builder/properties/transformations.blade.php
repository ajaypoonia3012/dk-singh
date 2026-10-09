<div class="space-y-6">

    <div class="flex items-center justify-between">

        <h3 class="text-xl font-bold">

            Transformations

        </h3>

        <button
            wire:click="addTransformation"
            class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">

            ➕ Add

        </button>

    </div>

    {{-- Transformation List --}}
    <div class="space-y-2">

        @foreach($transformations as $item)

            <div class="flex items-center gap-2">

                <button
                    wire:click="selectTransformation({{ $item->id }})"
                    class="flex-1 text-left p-3 rounded-xl border transition wb-card-selector-btn {{ $selectedTransformationId == $item->id ? 'active' : 'bg-white hover:bg-slate-50 border-slate-200' }}"
                    style="{{ $selectedTransformationId == $item->id ? 'background-color: #fef3c7 !important; border-color: #f59e0b !important; color: #78350f !important; font-weight: 700;' : '' }}">

                    <div class="font-bold text-xs truncate">
                        {{ $item->name ?: 'Untitled Transformation' }}
                    </div>

                    <div class="text-[11px] opacity-75 truncate">
                        {{ $item->goal }}
                    </div>

                </button>

                <button
                    wire:click="moveTransformationUp({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬆

                </button>

                <button
                    wire:click="moveTransformationDown({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬇

                </button>

                <button
                    wire:click="duplicateTransformation({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border border-blue-500 text-blue-600">

                    📄

                </button>

                <button
                    wire:click="toggleTransformationFeatured({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border">

                    {{ $item->featured ? '⭐' : '☆' }}

                </button>

                <button
                    wire:click="toggleTransformationStatus({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border">

                    {{ $item->status ? '👁' : '🚫' }}

                </button>

                <button
                    wire:click="deleteTransformation({{ $item->id }})"
                    wire:confirm="Delete Transformation?"
                    class="px-3 py-3 rounded-lg border border-red-500 text-red-600">

                    🗑

                </button>

            </div>

        @endforeach

    </div>

    <hr>

    @if($transformation)

        <div>

            <label class="block text-sm font-semibold mb-2">
                Name
            </label>

            <input
                wire:model.live="transformation.name"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Goal
            </label>

            <input
                wire:model.live="transformation.goal"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Program
            </label>

            <input
                wire:model.live="transformation.program"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Coach
            </label>

            <input
                wire:model.live="transformation.coach"
                class="w-full rounded-lg border p-3">

        </div>

        <div class="grid grid-cols-3 gap-4">

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Before Weight
                </label>

                <input
                    type="number"
                    wire:model.live="transformation.before_weight"
                    class="w-full rounded-lg border p-3">

            </div>

            <div>

                <label class="block text-sm font-semibold mb-2">
                    After Weight
                </label>

                <input
                    type="number"
                    wire:model.live="transformation.after_weight"
                    class="w-full rounded-lg border p-3">

            </div>

            <div>

                <label class="block text-sm font-semibold mb-2">
                    Weight Loss
                </label>

                <input
                    type="number"
                    wire:model.live="transformation.weight_loss"
                    class="w-full rounded-lg border p-3">

            </div>

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Duration
            </label>

            <input
                wire:model.live="transformation.duration"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Before Image
            </label>

            @if(!empty($transformation['before_image']))
                <img src="{{ asset('storage/'.$transformation['before_image']) }}"
                     class="w-full h-40 object-cover rounded-xl border mb-3">
            @endif

            <input
                type="file"
                wire:model="beforeImage"
                class="w-full rounded-lg border p-2">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                After Image
            </label>

            @if(!empty($transformation['after_image']))
                <img src="{{ asset('storage/'.$transformation['after_image']) }}"
                     class="w-full h-40 object-cover rounded-xl border mb-3">
            @endif

            <input
                type="file"
                wire:model="afterImage"
                class="w-full rounded-lg border p-2">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Description
            </label>

            <textarea
                rows="5"
                wire:model.live="transformation.description"
                class="w-full rounded-lg border p-3"></textarea>

        </div>

        <button
            wire:click="saveTransformation"
            class="w-full bg-amber-500 hover:bg-amber-600 text-black py-3 rounded-lg font-bold">

            💾 Save Transformation

        </button>

    @endif

</div>