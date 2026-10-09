@extends('layouts.app')

@section('title', 'Elite Coaching & Training Services | DK Singh Fitness')
@section('meta_description', 'Explore 1-on-1 online fitness coaching, personalized hypertrophy regimens, fat loss programming, and customized diet strategies by DK Singh.')

@section('content')

<section class="theme-section theme-surface-muted">

    <div class="theme-page-container">

        <!-- HEADING -->

        <div class="text-center mb-20">

            <p class="theme-text-primary font-bold uppercase tracking-[4px] mb-4">
               {{ $setting->services_page_label ?? 'Our Services' }}
            </p>

            <h1 class="text-5xl md:text-7xl font-black theme-text-secondary mb-8">
                {{ $setting->service_label }}
            </h1>

            <p class="text-xl theme-text-neutral max-w-3xl mx-auto leading-relaxed">
                {{ $setting->services_page_description ?? 'Premium fitness coaching and transformation services designed to help you achieve real results.' }}
            </p>

        </div>

        <!-- SERVICES GRID -->

        <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">

            @foreach($services as $service)

            <div class="theme-card theme-radius overflow-hidden theme-shadow hover:-translate-y-3 hover:theme-shadow transition duration-500">

                @if($service->image)

                <img
                    src="{{ asset('storage/' . $service->image) }}"
                    alt="{{ $service->title }}"
                    class="w-full h-[320px] object-cover"
                >

                @endif

                <div class="theme-card-padding">

                    <h2 class="text-3xl font-black theme-text-secondary mb-4">

                        {{ $service->title }}

                    </h2>

                    <p class="theme-text-neutral leading-relaxed mb-6">

                        {{ Str::limit($service->description, 100) }}

                    </p>

                    @if($service->price)

                    <div class="mb-6">

                        <span class="text-3xl font-black theme-text-primary">

                            &#8377;{{ number_format($service->price) }}

                        </span>

                    </div>

                    @endif

                    <a href="{{ route('services.show', $service->slug) }}"
                       class="block text-center theme-status-warning hover:theme-status-warning theme-text-secondary font-black py-4 theme-radius transition duration-300">

                        View Service

                    </a>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>

@endsection
