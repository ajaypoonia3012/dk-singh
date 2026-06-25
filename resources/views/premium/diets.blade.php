@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb] min-h-screen">

    <div class="max-w-6xl mx-auto px-6 text-center">

        <p class="uppercase tracking-[3px] text-yellow-500 font-bold mb-4">
            Premium Access
        </p>

        <h1 class="text-6xl font-black text-black mb-8">
            Premium Diet Plans
        </h1>

        <p class="text-xl text-gray-600 leading-[40px] max-w-3xl mx-auto mb-16">
            Access advanced nutrition systems, customized
            meal structures, and premium transformation diets.
        </p>

        <a href="/diet-plans"
           class="bg-yellow-500 hover:bg-yellow-400 text-black px-10 py-5 rounded-2xl font-black text-lg transition">

            Explore Diet Plans

        </a>

    </div>

</section>

@endsection