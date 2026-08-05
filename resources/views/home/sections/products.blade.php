@if($theme?->show_products)

@if($products->count())

<section class="py-24 bg-white">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-16">

            <p class="text-yellow-500 font-bold uppercase tracking-[4px]">
                {{ $setting->supplements_label }}
            </p>

            <h2 class="text-5xl font-black mt-4">
                {{ $setting->supplements_heading }}
            </h2>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            @foreach($products as $product)

                <div class="bg-white rounded-3xl shadow-lg overflow-hidden">

                    @if($product->image)

                        <img
                            src="{{ asset('storage/'.$product->image) }}"
                            class="w-full h-72 object-cover"
                            alt="{{ $product->name }}"
                        >

                    @endif

                    <div class="p-6">

                        <h3 class="text-2xl font-bold mb-3">
                            {{ $product->name }}
                        </h3>

                        <div class="text-yellow-500 text-3xl font-black mb-4">
                            ₹{{ number_format($product->price) }}
                        </div>

                        <a
                            href="{{ route('products.show', $product->slug) }}"
                            class="inline-block bg-yellow-500 px-6 py-3 rounded-xl font-bold"
                        >
                            View Product
                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif

@endif