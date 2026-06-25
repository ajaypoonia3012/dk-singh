@extends('layouts.app')

@section('content')

<section class="min-h-screen bg-[#f6f3eb] py-20">

    <div class="max-w-5xl mx-auto px-6">

        <h1 class="text-5xl font-black mb-12">
            Coach Feedback History
        </h1>

        @forelse($notes as $note)

            <div class="bg-white rounded-[30px] p-8 shadow-lg mb-8">

                <p class="uppercase tracking-[2px] text-gray-400 text-sm mb-3">
                    {{ $note->created_at->format('d M Y') }}
                </p>

                <p class="text-lg leading-relaxed">
                    {{ $note->note }}
                </p>

            </div>

        @empty

            <div class="bg-white rounded-[30px] p-8 shadow-lg">

                No coach feedback available yet.

            </div>

        @endforelse

    </div>

</section>

@endsection