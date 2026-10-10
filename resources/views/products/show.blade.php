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

            @if($setting?->powder_promo_enabled)
                <div class="mt-4 p-4 theme-radius theme-card border flex items-center justify-between gap-3">
                    <div class="text-sm">
                        <span class="font-bold theme-text-accent uppercase tracking-wide text-xs">⚡ {{ $setting->powder_promo_badge ?: 'FEATURED COLLECTION' }}</span>
                        <p class="font-medium theme-text-neutral mt-0.5">{{ $setting->powder_promo_text ?: 'Explore our authentic herbal & wellness powder collection.' }}</p>
                    </div>
                    <a
                        href="{{ $setting->powder_promo_button_link ?: route('products.index') }}"
                        class="px-3 py-1.5 theme-radius theme-button-primary text-xs font-bold flex-shrink-0"
                    >
                        {{ $setting->powder_promo_button_text ?: 'Explore Collection' }}
                    </a>
                </div>
            @endif

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
