@extends('layouts.app')

@section('title', 'Performance Supplements & Wellness Essentials | DK Singh Fitness')
@section('meta_description', 'Shop genuine DK Singh fitness supplements, natural wellness powders, and training essentials designed to support your physique goals.')

@section('content')

<section class="theme-section theme-surface-muted">

    <div class="theme-page-container">

        <div class="text-center mb-20">

            <p class="theme-text-primary font-bold uppercase tracking-[4px] mb-4">
                Premium Supplements
            </p>

            <h1 class="text-5xl md:text-7xl font-black theme-text-secondary mb-8">
                {{ $setting->product_label }}
            </h1>

            <p class="text-xl theme-text-neutral max-w-3xl mx-auto leading-relaxed">
                High-quality fitness supplements and wellness products
                designed to support your transformation journey.
            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">

            @foreach($products as $product)

            <div class="theme-card theme-radius overflow-hidden theme-shadow hover:-translate-y-3 hover:theme-shadow transition duration-500">

                @if($product->image)

                <img
                    src="{{ asset('storage/' . $product->image) }}"
                    alt="{{ $product->name }}"
                    class="w-full h-[320px] object-cover"
                >

                @endif

                <div class="theme-card-padding">

                    <h2 class="text-3xl font-black theme-text-secondary mb-4">

                        {{ $product->name }}

                    </h2>

                    <p class="theme-text-neutral leading-relaxed mb-6">

                        {{ Str::limit($product->description, 100) }}

                    </p>

                    <div class="flex justify-between items-center mb-8">

                        <span class="text-3xl font-black theme-text-primary">

                            &#8377;{{ number_format($product->price) }}

                        </span>

                    </div>

                    <a href="{{ route('products.show', $product->slug) }}"
                       class="block text-center theme-status-warning hover:theme-status-warning theme-text-secondary font-black py-4 theme-radius transition duration-300">

                        View Product

                    </a>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>

@endsection
