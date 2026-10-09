@extends('layouts.app')

@section('content')

<section class="min-h-screen theme-surface-muted theme-section">

    <div class="theme-page-container">

        <h1 class="text-5xl font-black mb-12">
            Coach Feedback History
        </h1>

        @forelse($notes as $note)

            <div class="theme-card theme-radius theme-card-padding theme-shadow mb-8">

                <div class="flex items-center justify-between mb-3">
                    <p class="uppercase tracking-[2px] theme-text-neutral text-xs">
                        {{ $note->created_at->format('d M Y') }}
                    </p>
                </div>

                <p class="text-lg leading-relaxed">
                    {{ $note->note }}
                </p>

            </div>

        @empty

            <div class="theme-card theme-radius theme-card-padding theme-shadow">

                No coach feedback available yet.

            </div>

        @endforelse

    </div>

</section>

@endsection
