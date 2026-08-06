@php
    $programs = \App\Models\Program::where('status', true)
        ->orderBy('sort_order')
        ->get();
@endphp

<section class="py-20 bg-white">

    <div class="max-w-7xl mx-auto px-8">

        <div class="text-center mb-14">

            <p class="uppercase tracking-[4px] text-yellow-500 font-bold">

                Featured Programs

            </p>

            <h2 class="text-5xl font-black mt-3">

                Transform Your Fitness

            </h2>

            <p class="text-gray-600 mt-5 max-w-2xl mx-auto">

                Choose the perfect fitness program designed by {{ $setting?->site_name ?: config('app.name') }} to match your goals.

            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse($programs as $program)

                <div class="rounded-3xl overflow-hidden shadow-xl bg-white border border-gray-100 hover:shadow-2xl transition">

                    @if($program->image)

                        <img
                            src="{{ asset('storage/'.$program->image) }}"
                            alt="{{ $program->title }}"
                            class="w-full h-56 object-cover">

                    @else

                        <div class="h-56 bg-gray-200 flex items-center justify-center">

                            <span class="text-gray-500">

                                No Image

                            </span>

                        </div>

                    @endif

                    <div class="p-6">

                        @if($program->featured)

                            <span class="inline-flex mb-3 px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">

                                ⭐ Featured

                            </span>

                        @endif

                        <h3 class="text-2xl font-bold">

                            {{ $program->title }}

                        </h3>

                        <div class="flex items-center justify-between mt-3">

                            <span class="text-sm text-gray-500">

                                {{ $program->category }}

                            </span>

                            <span class="text-sm text-gray-500">

                                {{ $program->duration }}

                            </span>

                        </div>

                        <p class="text-gray-600 mt-4 line-clamp-4">

                            {{ $program->description }}

                        </p>

                        <div class="mt-6 flex items-center justify-between">

                            <span class="text-2xl font-black text-yellow-600">

                                ₹{{ number_format($program->price, 0) }}

                            </span>

                            <a
                                href="#"
                                class="px-5 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-black font-bold">

                                View Program

                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-span-3 text-center py-20">

                    <h3 class="text-2xl font-bold text-gray-500">

                        No Programs Found

                    </h3>

                </div>

            @endforelse

        </div>

    </div>

</section>
