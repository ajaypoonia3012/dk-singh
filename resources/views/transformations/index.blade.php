@extends('layouts.app')

@section('title', 'Real Client Success Stories & Transformations | DK Singh Fitness')
@section('meta_description', 'Witness real client transformations: fat loss, muscle building, and lifestyle overhauls achieved through DK Singh Fitness protocols.')

@section('content')

<section class="theme-section theme-surface-muted min-h-screen">

    <div class="theme-page-container">

        <div class="text-center mb-16">

            <p class="theme-text-primary font-bold uppercase tracking-[3px] mb-4">
               {{ $setting->transformations_page_label ?? 'Real Client Results' }}
            </p>

            <h1 class="text-5xl font-black theme-text-secondary mb-6">
               {{ $setting->transformations_page_title ?? 'Body Transformations' }}
            </h1>

            <p class="text-xl theme-text-neutral max-w-3xl mx-auto">
                {{ $setting->transformations_page_description ?? 'Real transformations achieved through discipline, coaching, and customized fitness programs.' }}
            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 theme-grid-gap">

            @forelse($transformations as $transformation)
                @php
                    $imgPath = $transformation->after_image ?: ($transformation->image ?: $transformation->before_image);
                    $hasImg = filled($imgPath) && file_exists(public_path('storage/' . $imgPath));
                @endphp
                <a
                    href="{{ route('transformations.show', $transformation->slug) }}"
                    data-aos="zoom-in"
                    class="group theme-card theme-radius overflow-hidden theme-shadow hover:theme-shadow hover:-translate-y-1.5 transition duration-300 flex flex-col justify-between block">

                    <div>
                        <div class="relative aspect-[4/3] w-full overflow-hidden theme-surface-strong">
                            @if($hasImg)
                                <img
                                    src="{{ asset('storage/' . $imgPath) }}"
                                    alt="{{ $transformation->title ?? 'Transformation' }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                >
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center theme-surface-strong theme-text-neutral">
                                    <span class="text-3xl mb-2">⚡</span>
                                    <span class="font-bold text-sm tracking-wider uppercase theme-text-primary">Verified Result</span>
                                    <span class="text-xs theme-text-neutral mt-1">DK Singh Coaching Protocol</span>
                                </div>
                            @endif

                            @if(filled($transformation->goal))
                                <div class="absolute top-4 left-4 theme-status-warning theme-text-secondary px-3 py-1 rounded-full text-xs font-bold theme-shadow">
                                    {{ $transformation->goal }}
                                </div>
                            @endif
                        </div>

                        <div class="theme-card-padding">
                            <div class="flex theme-text-primary text-base mb-2 tracking-[2px]">
                                <svg class="inline-block w-4 h-4 theme-text-primary" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.4 1.1 6.2-5.6-2.9L6.4 20l1.1-6.2-4.6-4.4 6.3-.9L12 2.8Z" /></svg>
                                <svg class="inline-block w-4 h-4 theme-text-primary" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.4 1.1 6.2-5.6-2.9L6.4 20l1.1-6.2-4.6-4.4 6.3-.9L12 2.8Z" /></svg>
                                <svg class="inline-block w-4 h-4 theme-text-primary" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.4 1.1 6.2-5.6-2.9L6.4 20l1.1-6.2-4.6-4.4 6.3-.9L12 2.8Z" /></svg>
                                <svg class="inline-block w-4 h-4 theme-text-primary" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.4 1.1 6.2-5.6-2.9L6.4 20l1.1-6.2-4.6-4.4 6.3-.9L12 2.8Z" /></svg>
                                <svg class="inline-block w-4 h-4 theme-text-primary" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2.8 2.8 5.7 6.3.9-4.6 4.4 1.1 6.2-5.6-2.9L6.4 20l1.1-6.2-4.6-4.4 6.3-.9L12 2.8Z" /></svg>
                            </div>

                            <h2 class="text-xl font-bold theme-text-secondary mb-2">
                                {{ $transformation->name }}
                            </h2>

                            @if($transformation->story)
                                <p class="text-sm leading-relaxed theme-text-neutral line-clamp-3">
                                    “{{ Str::limit(trim(strip_tags($transformation->story)), 140) }}”
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-2">
                        <div class="flex items-center justify-between border-t theme-border pt-4">
                            @if($transformation->duration)
                                <span class="text-xs font-bold theme-text-neutral uppercase tracking-wider">
                                    {{ $transformation->duration }}
                                </span>
                            @endif

                            <span class="theme-text-primary font-bold text-xs uppercase tracking-wider">
                                {{ $setting->verified_client_label ?? 'Verified Client' }}
                            </span>
                        </div>
                    </div>

                </a>

            @empty

                <div class="col-span-3 text-center theme-section">

                    <h2 class="text-3xl font-black theme-text-secondary mb-4">
                        No Transformations Found
                    </h2>

                    <p class="theme-text-neutral">
                        Transformation stories will appear here soon.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</section>

@endsection
