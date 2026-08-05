<div class="bg-white rounded-xl shadow p-4">

    <h2 class="text-lg font-bold mb-4">
        Website Sections
    </h2>

    @foreach($sections as $section)

        <button
    wire:click="select({{ $section->id }})"
    class="w-full text-left border rounded-lg p-3 mb-3
    {{ optional($selected)->id == $section->id ? 'bg-amber-100 border-amber-500' : 'hover:bg-gray-50' }}">

            <div class="flex justify-between items-center">

                <div>

                    <div class="font-semibold">

                        ☰ {{ $section->title }}

                    </div>

                    <div class="text-xs text-gray-500">

                        {{ ucfirst($section->template) }}

                    </div>

                </div>

                @if($section->enabled)

                    <span class="text-green-600 font-bold">
                        ●
                    </span>

                @else

                    <span class="text-red-600 font-bold">
                        ●
                    </span>

                @endif

            </div>

        </button>

    @endforeach

</div>