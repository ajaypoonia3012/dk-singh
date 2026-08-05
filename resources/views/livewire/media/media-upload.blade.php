<div class="p-6">

    <label
        class="border-2 border-dashed rounded-2xl h-72 flex flex-col justify-center items-center cursor-pointer hover:border-yellow-500 transition">

        <svg
            class="w-16 h-16 text-gray-400 mb-4"
            fill="none"
            stroke="currentColor"
            stroke-width="1.5"
            viewBox="0 0 24 24">

            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 16V4m0 0l-4 4m4-4l4 4M4 20h16"/>

        </svg>

        <div class="text-xl font-semibold">

            Drag & Drop Images

        </div>

        <div class="text-gray-500 mt-2">

            or click to browse

        </div>

        <input
            type="file"
            class="hidden"
            wire:model.live="upload"
            multiple
            accept="image/*">

    </label>

    <div wire:loading wire:target="upload"
         class="mt-6 text-yellow-600">

        Uploading...

    </div>

</div>
