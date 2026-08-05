@php
</section>

                    @php
                        $backgroundMedia = !empty($card->background_media_id)
                            ? \App\Models\Media::find($card->background_media_id)
                            : null;
                    @endphp

                    @if($backgroundMedia)

                        <img
                            src="{{ asset('storage/'.$backgroundMedia->path) }}"
                            alt="{{ $card->title }}"
                            class="w-full h-56 object-cover">

                    @elseif($card->background_image)

                        <img
                            src="{{ asset('storage/'.$card->background_image) }}"
                            alt="{{ $card->title }}"
                            class="w-full h-56 object-cover">

                    @endif

                    <div class="p-8">

                        <div class="text-5xl mb-5">

                            {{ $card->icon }}

                        </div>

                        <h3 class="text-2xl font-bold mb-2">

                            {{ $card->title }}

                        </h3>

                        <p class="text-yellow-500 font-semibold mb-4">

                            {{ $card->subtitle }}

                        </p>

                        <p class="text-gray-600 leading-7 mb-6">

                            {{ $card->description }}

                        </p>

                        <a
                            href="{{ $card->button_link }}"
                            class="inline-flex px-6 py-3 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-black font-bold">

                            {{ $card->button_text }}

                        </a>

                    </div>

                </div>

            @empty

                <div class="col-span-3 text-center py-20 text-gray-500">

                    No Homepage Cards Found

                </div>

            @endforelse

        </div>

    </div>

</section>
