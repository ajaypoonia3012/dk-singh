@php
    $transformations = \App\Models\Transformation::where('status', 1)
        ->orderBy('sort_order')
        ->take(3)
        ->get();
@endphp

<section class="bg-white py-20">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-12">

            <p class="uppercase tracking-[4px] text-yellow-500 font-bold">
                Transformations
            </p>

            <h2 class="text-5xl font-black mt-4">

                Real Client Results

            </h2>

            <p class="text-gray-500 mt-4">

                Preview of your featured client transformations.

            </p>

        </div>

        <div class="grid lg:grid-cols-3 gap-8">

            @forelse($transformations as $item)

                <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

                    <div class="grid grid-cols-2">

                        <div class="relative">

                            <img
                                src="{{ asset('storage/'.$item->before_image) }}"
                                class="w-full h-72 object-cover">

                            <span class="absolute top-3 left-3 bg-black text-white px-3 py-1 rounded-full text-xs">

                                BEFORE

                            </span>

                        </div>

                        <div class="relative">

                            <img
                                src="{{ asset('storage/'.$item->after_image) }}"
                                class="w-full h-72 object-cover">

                            <span class="absolute top-3 right-3 bg-yellow-500 text-black px-3 py-1 rounded-full text-xs font-bold">

                                AFTER

                            </span>

                        </div>

                    </div>

                    <div class="p-6">

                        <h3 class="text-2xl font-bold">

                            {{ $item->name }}

                        </h3>

                        <p class="text-gray-500 mt-2">

                            {{ $item->goal }}

                        </p>

                        <div class="grid grid-cols-2 gap-4 mt-6">

                            <div>

                                <div class="text-3xl font-black text-yellow-500">

                                    {{ $item->weight_loss }} kg

                                </div>

                                <div class="text-sm text-gray-500">

                                    Weight Lost

                                </div>

                            </div>

                            <div>

                                <div class="text-3xl font-black text-yellow-500">

                                    {{ $item->duration }}

                                </div>

                                <div class="text-sm text-gray-500">

                                    Duration

                                </div>

                            </div>

                        </div>

                        <p class="mt-6 text-gray-600">

                            {{ $item->description }}

                        </p>

                        <div class="mt-6 flex justify-between items-center">

                            <span class="text-sm text-gray-500">

                                Coach: {{ $item->coach }}

                            </span>

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">

                                {{ $item->program }}

                            </span>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-span-3 text-center py-20 text-gray-500">

                    No transformation records found.

                </div>

            @endforelse

        </div>

    </div>

</section>