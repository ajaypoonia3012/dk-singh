@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb] min-h-screen">

<div class="max-w-7xl mx-auto px-6">

<div class="text-center mb-20">

<p class="text-yellow-500 uppercase tracking-[4px] font-bold mb-4">
FREE DIET PLANS
</p>

<h1 class="text-6xl font-black text-black mb-6">
Diet Library
</h1>

<p class="text-xl text-gray-600">
Free nutrition plans from DK Singh.
</p>

</div>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

@foreach($diets as $diet)

<a href="{{ route('fitness-hub.diets.show',$diet->slug) }}"
class="bg-white rounded-3xl shadow-xl p-8 block hover:shadow-2xl">

<h2 class="text-3xl font-black mb-4">
{{ $diet->title }}
</h2>

<p class="text-yellow-500 font-bold mb-4">
{{ $diet->goal }}
</p>

<p class="text-gray-600">
{{ Str::limit($diet->description,120) }}
</p>

</a>

@endforeach

</div>

</div>

</section>

@endsection