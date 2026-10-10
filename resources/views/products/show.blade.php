@extends('layouts.app')

@section('title', $product->name . ' | DK Singh Fitness')
@section('meta_description', Str::limit(strip_tags($product->description ?? 'Premium wellness supplement by DK Singh Fitness.'), 155))

@section('content')

<section class="theme-section">

    <div class="max-w-7xl mx-auto px-5 grid md:grid-cols-2 gap-16 items-start">

        <div>
            @if($product->image)
                <img
                    src="{{ asset('storage/' . $product->image) }}"
                    alt="{{ $product->name }}"
                    class="theme-radius theme-shadow w-full object-cover max-h-[500px]"
                >
            @else
                <div class="theme-radius theme-shadow w-full h-[400px] theme-surface-muted flex items-center justify-center theme-text-neutral">
                    <span>No image available</span>
                </div>
            @endif
        </div>

        <div>

            <h1 class="text-4xl sm:text-5xl font-bold theme-text-secondary">
                {{ $product->name }}
            </h1>

            <div class="mt-4 flex items-baseline gap-4">
                <span class="text-3xl font-black theme-text-primary">
                    &#8377;{{ number_format($product->price, 2) }}
                </span>
                <span class="text-xs uppercase font-bold tracking-wider px-2.5 py-1 theme-radius theme-card border theme-border theme-text-accent">
                    In Stock
                </span>
            </div>

            <!-- Applicable Offers on Product Detail -->
            @if(isset($availableOffers) && $availableOffers->isNotEmpty())
                <div class="mt-6 p-5 theme-radius theme-card border theme-border space-y-3 theme-surface-muted">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider theme-text-secondary flex items-center gap-1.5">
                            <span>🏷️ Available Offers & Savings</span>
                        </span>
                        <span class="text-[11px] font-semibold theme-text-accent">
                            {{ $availableOffers->count() }} active {{ Str::plural('offer', $availableOffers->count()) }}
                        </span>
                    </div>

                    <div class="space-y-2.5 pt-1">
                        @foreach($availableOffers as $offer)
                            <div class="p-3 theme-radius border theme-border theme-card flex flex-wrap items-center justify-between gap-3 text-xs">
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-black theme-text-primary uppercase px-2 py-0.5 theme-radius theme-card border theme-border">
                                            {{ $offer->code }}
                                        </span>
                                        <span class="font-bold theme-text-secondary">
                                            @if($offer->discount_type === 'percentage')
                                                Save {{ rtrim(rtrim((string) $offer->discount_value, '0'), '.') }}%
                                                @if($offer->max_discount_cap)
                                                    <span class="opacity-75 font-normal">(up to &#8377;{{ number_format($offer->max_discount_cap) }})</span>
                                                @endif
                                            @else
                                                Save &#8377;{{ number_format($offer->discount_value) }} Flat
                                            @endif
                                        </span>
                                    </div>
                                    @if(filled($offer->name))
                                        <p class="text-[11px] theme-text-neutral">{{ $offer->name }}</p>
                                    @endif
                                    @if($offer->min_order_value)
                                        <p class="text-[10px] theme-text-accent">Min. order &#8377;{{ number_format($offer->min_order_value) }}</p>
                                    @endif
                                </div>

                                <a
                                    href="{{ route('product.checkout', ['id' => $product->id, 'code' => $offer->code]) }}"
                                    class="px-3 py-1.5 theme-radius theme-button-primary text-[11px] font-bold theme-shadow transition hover:opacity-90 flex-shrink-0"
                                >
                                    Apply at Checkout →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($setting?->powder_promo_enabled)
                <div class="mt-4 p-4 theme-radius theme-card border theme-border flex items-center justify-between gap-3">
                    <div class="text-sm">
                        <span class="font-bold theme-text-accent uppercase tracking-wide text-xs">⚡ {{ $setting->powder_promo_badge ?: 'FEATURED COLLECTION' }}</span>
                        <p class="font-medium theme-text-neutral mt-0.5 text-xs">{{ $setting->powder_promo_text ?: 'Explore our authentic herbal & wellness powder collection.' }}</p>
                    </div>
                    <a
                        href="{{ $setting->powder_promo_button_link ?: route('products.index') }}"
                        class="px-3 py-1.5 theme-radius theme-button-primary text-xs font-bold flex-shrink-0"
                    >
                        {{ $setting->powder_promo_button_text ?: 'Explore Collection' }}
                    </a>
                </div>
            @endif

            <p class="mt-8 text-base theme-text-neutral leading-relaxed">
                {{ $product->description }}
            </p>

            <div class="mt-8 pt-4 border-t theme-border flex flex-wrap items-center gap-4">
                <a
                    href="{{ route('product.checkout', $product->id) }}"
                    class="theme-status-warning hover:opacity-90 px-8 py-4 theme-radius font-black text-sm uppercase tracking-wider theme-shadow transition"
                >
                    Buy Now — &#8377;{{ number_format($product->price, 2) }}
                </a>
                <a
                    href="{{ route('products.index') }}"
                    class="px-6 py-4 theme-radius border theme-border font-bold text-sm theme-text-neutral hover:theme-text-secondary transition"
                >
                    ← Back to Catalog
                </a>
            </div>

        </div>

    </div>

</section>

@endsection
