<div class="grid grid-cols-2 gap-5 mt-12">

    @foreach($homepageCards as $card)

        <div class="bg-[#f6f3eb] rounded-2xl p-6 shadow-lg hover:-translate-y-2 hover:shadow-2xl transition duration-300">

            @if($card->svg_icon)
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center mb-3" aria-hidden="true">
                    {!! $card->svg_icon !!}
                </div>
            @elseif($card->icon)
                <div class="text-3xl mb-3" aria-hidden="true">
                    {{ $card->icon }}
                </div>
            @endif

            <h3 class="font-black text-xl">
                {{ $card->title }}
            </h3>

            <p class="text-gray-600 mt-2">
                {{ $card->subtitle }}
            </p>

        </div>

    @endforeach

</div>