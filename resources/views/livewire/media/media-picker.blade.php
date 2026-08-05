<div class="flex flex-col h-full">

    <div class="border-b flex">

        <button
            wire:click="showLibrary"
            class="px-6 py-4 font-semibold">

            Library

        </button>

        <button
            wire:click="showUpload"
            class="px-6 py-4 font-semibold">

            Upload

        </button>

    </div>

    <div class="flex-1 overflow-hidden">

        @if($tab === 'library')

            <livewire:media.media-browser />

        @endif

        @if($tab === 'upload')

            <livewire:media.media-upload />

        @endif

    </div>

</div>
