@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto theme-section px-6">

    <div class="theme-card theme-radius theme-shadow theme-card-padding-lg">

        <h1 class="text-4xl font-black mb-6">
            Product Checkout
        </h1>

        <h2 class="text-2xl font-bold mb-4">
            {{ $product->name }}
        </h2>

        <div class="text-3xl font-black theme-text-primary mb-6">
            &#8377;{{ number_format($product->price) }}
        </div>

        @if($product->description)

            <p class="theme-text-neutral mb-8">
                {{ $product->description }}
            </p>

        @endif

        <a
    href="/product-payment/{{ $product->id }}"
    class="inline-block theme-surface-strong theme-text-on-strong px-8 py-4 theme-radius"
>
    Proceed To Payment
</a>

    </div>

</div>

@endsection
