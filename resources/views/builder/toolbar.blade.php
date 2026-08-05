<div class="bg-white rounded-xl shadow p-4 mb-6 flex items-center justify-between">

    <div>
        <h2 class="text-2xl font-bold">
            🎨 Website Builder
        </h2>

        <p class="text-sm text-gray-500">
            Build your homepage visually.
        </p>
    </div>

    <div class="flex gap-2">

        <button
            wire:click="changeDevice('desktop')"
            class="px-4 py-2 rounded-lg border {{ $selectedDevice=='desktop' ? 'bg-amber-500 text-white' : '' }}">
            🖥 Desktop
        </button>

        <button
            wire:click="changeDevice('tablet')"
            class="px-4 py-2 rounded-lg border {{ $selectedDevice=='tablet' ? 'bg-amber-500 text-white' : '' }}">
            📱 Tablet
        </button>

        <button
            wire:click="changeDevice('mobile')"
            class="px-4 py-2 rounded-lg border {{ $selectedDevice=='mobile' ? 'bg-amber-500 text-white' : '' }}">
            📲 Mobile
        </button>

    </div>

</div>