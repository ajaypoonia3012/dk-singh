@extends('layouts.app')

@section('title', $product->name . ' | DK Singh Fitness')
@section('meta_description', Str::limit(strip_tags($product->description ?? 'Premium wellness supplement by DK Singh Fitness.'), 155))

@section('content')

<section class="theme-section">

    <div class="max-w-7xl mx-auto px-5 grid md:grid-cols-2 gap-16">

        <div>

            <img 
                src="{{ asset('storage/' . $product->image) }}"
                alt="{{ $product->name }}"
                class="theme-radius theme-shadow w-full"
            >

        </div>

        <div>

            <h1 class="text-5xl font-bold">
                {{ $product->name }}
            </h1>

            <div class="mt-6 text-3xl font-bold theme-text-primary">
                &#8377;{{ $product->price }}
            </div>

            <p class="mt-8 text-lg theme-text-neutral leading-relaxed">
                {{ $product->description }}
            </p>

            <a
    href="{{ route('product.checkout',$product->id) }}"
    class="inline-block mt-10 theme-surface-strong theme-text-on-strong px-8 py-4 theme-radius"
>
    Buy Now
</a>

        </div>

    </div>

</section>

@endsection
