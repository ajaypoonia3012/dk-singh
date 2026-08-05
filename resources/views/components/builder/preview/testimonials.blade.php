@php
    $testimonials = \App\Models\Testimonial::where('status', 1)
        ->orderBy('sort_order')
        ->take(3)
        ->get();
@endphp

<section class="bg-white py-20">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-14">

            <p class="uppercase tracking-[4px] text-yellow-500 font-bold">
                Testimonials
            </p>

            <h2 class="text-5xl font-black mt-4">
                What Clients Say
            </h2>

            <p class="mt-4 text-gray-500">
                Preview of your featured testimonials.
            </p>

        </div>

        <div class="grid lg:grid-cols-3 gap-8">

            @forelse($testimonials as $item)

                <div class="bg-white rounded-3xl shadow-xl p-8">

                    {{-- Profile Image --}}
                    <div class="flex justify-center mb-6">

                        @if($item->image)

                            <img
                                src="{{ asset('storage/'.$item->image) }}"
                                class="w-24 h-24 rounded-full object-cover border-4 border-yellow-400"
                                alt="{{ $item->name }}">

                        @else

                            <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-3xl font-bold text-gray-600">

                                {{ strtoupper(substr($item->name, 0, 1)) }}

                            </div>

                        @endif

                    </div>

                    {{-- Rating --}}
                    <div class="flex justify-center mb-5">

                        @for($i = 1; $i <= 5; $i++)

                            <span class="text-2xl">
                                {{ $i <= $item->rating ? '⭐' : '☆' }}
                            </span>

                        @endfor

                    </div>

                    {{-- Review --}}
                    <p class="text-gray-600 leading-7 text-center italic mb-6">

                        "{{ $item->review }}"

                    </p>

                    <div class="border-t pt-6">

                        <h3 class="text-xl font-bold text-center">

                            {{ $item->name }}

                        </h3>

                        <p class="text-center text-gray-500 mt-1">

                            {{ $item->profession }}

                        </p>

                        <p class="text-center text-sm text-gray-400">

                            {{ $item->location }}

                        </p>

                        <div class="mt-5 flex justify-center">

                            <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-semibold">

                                {{ $item->transformation }}

                            </span>

                        </div>

                        <div class="mt-3 flex justify-center">

                            <span class="text-sm text-gray-500">

                                {{ $item->program }}

                            </span>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-span-3 text-center py-20 text-gray-500">

                    No testimonials found.

                </div>

            @endforelse

        </div>

    </div>

</section>