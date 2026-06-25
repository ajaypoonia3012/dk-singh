@extends('layouts.app')

@section('content')

<section class="py-20">

    <div class="max-w-7xl mx-auto px-5 grid md:grid-cols-2 gap-16">

        <div>

            <img 
                src="{{ asset('storage/' . $product->image) }}"
                class="rounded-2xl shadow-xl w-full"
            >

        </div>

        <div>

            <h1 class="text-5xl font-bold">
                {{ $product->name }}
            </h1>

            <div class="mt-6 text-3xl font-bold text-yellow-500">
                ₹{{ $product->price }}
            </div>

            <p class="mt-8 text-lg text-gray-700 leading-relaxed">
                {{ $product->description }}
            </p>

            <a
    href="{{ route('product.checkout',$product->id) }}"
    class="inline-block mt-10 bg-black text-white px-8 py-4 rounded-xl"
>
    Buy Now
</a>

        </div>

    </div>

</section>

@endsection