@extends('layouts.app')

@section('title', ($transformation->name ?? 'Client') . ' Transformation Story | DK Singh Fitness')
@section('meta_description', Str::limit(strip_tags($transformation->story ?? $transformation->description ?? 'Real client transformation story achieved with DK Singh Fitness coaching.'), 155))

@section('content')

<section class="theme-section theme-surface-strong theme-text-on-strong min-h-screen">

    <div class="theme-page-container">

        <div class="grid md:grid-cols-2 gap-12 items-center">

            <div>

                @php
                    $imgPath = $transformation->image ?: ($transformation->after_image ?: $transformation->before_image);
                    $hasImg = filled($imgPath) && file_exists(public_path('storage/' . $imgPath));
                @endphp

                @if($hasImg)
                    <img
                        src="{{ asset('storage/' . $imgPath) }}"
                        alt="{{ $transformation->name ?? 'Client' }} Transformation Photo"
                        class="theme-radius theme-shadow w-full max-h-[500px] object-cover"
                    >
                @endif

            </div>

            <div>

                <p class="theme-text-primary uppercase tracking-[3px] font-bold mb-4">
                    Client Transformation
                </p>

                <h1 class="text-5xl font-black mb-6">
                    {{ $transformation->name }}
                </h1>

                @if($transformation->goal)
                    <p class="mb-4 theme-text-neutral">
                        <strong>Goal:</strong>
                        {{ $transformation->goal }}
                    </p>
                @endif

                @if($transformation->duration)
                    <p class="mb-4 theme-text-neutral">
                        <strong>Duration:</strong>
                        {{ $transformation->duration }}
                    </p>
                @endif

                @if($transformation->weight_loss)
                    <p class="mb-4 theme-text-neutral">
                        <strong>Result:</strong>
                        {{ $transformation->weight_loss }}
                    </p>
                @endif

                @if($transformation->description)
                    <div class="mt-8 theme-text-neutral leading-8">
                        {{ $transformation->description }}
                    </div>
                @endif

                <a
                    href="/contact"
                    class="inline-block mt-10 theme-status-warning hover:theme-status-warning theme-text-secondary font-bold px-8 py-4 theme-radius transition">

                    Start Your Transformation

                </a>

            </div>

        </div>

    </div>

</section>

@endsection
