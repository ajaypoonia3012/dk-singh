@extends('layouts.app')

@section('content')

<section class="py-20 bg-black text-white min-h-screen">

    <div class="max-w-6xl mx-auto px-6">

        <div class="grid md:grid-cols-2 gap-12 items-center">

            <div>

                @if($transformation->image)

                    <img
                        src="{{ asset('storage/' . $transformation->image) }}"
                        class="rounded-3xl shadow-2xl w-full h-[500px] object-cover"
                    >

                @endif

            </div>

            <div>

                <p class="text-yellow-500 uppercase tracking-[3px] font-bold mb-4">
                    Client Transformation
                </p>

                <h1 class="text-5xl font-black mb-6">
                    {{ $transformation->name }}
                </h1>

                @if($transformation->goal)
                    <p class="mb-4 text-gray-300">
                        <strong>Goal:</strong>
                        {{ $transformation->goal }}
                    </p>
                @endif

                @if($transformation->duration)
                    <p class="mb-4 text-gray-300">
                        <strong>Duration:</strong>
                        {{ $transformation->duration }}
                    </p>
                @endif

                @if($transformation->weight_loss)
                    <p class="mb-4 text-gray-300">
                        <strong>Result:</strong>
                        {{ $transformation->weight_loss }}
                    </p>
                @endif

                @if($transformation->description)
                    <div class="mt-8 text-gray-300 leading-8">
                        {{ $transformation->description }}
                    </div>
                @endif

                <a
                    href="/contact"
                    class="inline-block mt-10 bg-yellow-500 hover:bg-yellow-400 text-black font-bold px-8 py-4 rounded-2xl transition">

                    Start Your Transformation

                </a>

            </div>

        </div>

    </div>

</section>

@endsection