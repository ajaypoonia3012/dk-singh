@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto py-20 px-6">

    <div class="bg-white rounded-3xl shadow-xl p-10">

        <h1 class="text-4xl font-black mb-6">
            Product Checkout
        </h1>

        <h2 class="text-2xl font-bold mb-4">
            {{ $product->name }}
        </h2>

        <div class="text-3xl font-black text-yellow-500 mb-6">
            ₹{{ number_format($product->price) }}
        </div>

        @if($product->description)

            <p class="text-gray-600 mb-8">
                {{ $product->description }}
            </p>

        @endif

        <a
    href="/product-payment/{{ $product->id }}"
    class="inline-block bg-black text-white px-8 py-4 rounded-xl"
>
    Proceed To Payment
</a>

    </div>

</div>

@endsection