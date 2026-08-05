<div
    x-data="{ open: @entangle($attributes->wire('model')).live }"

    x-show="open"

    x-cloak

    x-transition.opacity

    x-effect="
        document.body.classList.toggle('overflow-hidden', open)
    "

    class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/70 p-6"

>

    <div
        @click.away="open = false"

        class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl h-[90vh] overflow-hidden flex flex-col"

    >

        <div class="border-b px-6 py-4 flex items-center justify-between">

            <h2 class="text-xl font-bold">
                Media Library
            </h2>

            <button
                type="button"
                @click="open = false"
                class="text-2xl">

                &times;

            </button>

        </div>

        <div class="flex-1 overflow-hidden">

            {{ $slot }}

        </div>

    </div>

</div>