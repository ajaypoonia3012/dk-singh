@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb]">

    <div class="max-w-7xl mx-auto px-6">

        <div class="text-center mb-20">

            <p class="text-yellow-500 font-bold uppercase tracking-[4px] mb-4">
                Premium Supplements
            </p>

            <h1 class="text-5xl md:text-7xl font-black text-[#111111] mb-8">
                {{ $setting->product_label }}
            </h1>

            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                High-quality fitness supplements and wellness products
                designed to support your transformation journey.
            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

            @foreach($products as $product)

            <div class="bg-white rounded-[35px] overflow-hidden shadow-xl hover:-translate-y-3 hover:shadow-2xl transition duration-500">

                @if($product->image)

                <img
                    src="{{ asset('storage/' . $product->image) }}"
                    alt="{{ $product->name }}"
                    class="w-full h-[320px] object-cover"
                >

                @endif

                <div class="p-8">

                    <h2 class="text-3xl font-black text-[#111111] mb-4">

                        {{ $product->name }}

                    </h2>

                    <p class="text-gray-600 leading-relaxed mb-6">

                        {{ Str::limit($product->description, 100) }}

                    </p>

                    <div class="flex justify-between items-center mb-8">

                        <span class="text-3xl font-black text-yellow-500">

                            ₹{{ number_format($product->price) }}

                        </span>

                    </div>

                    <a href="{{ route('products.show', $product->slug) }}"
                       class="block text-center bg-yellow-500 hover:bg-yellow-400 text-black font-black py-4 rounded-2xl transition duration-300">

                        View Product

                    </a>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>

@endsection