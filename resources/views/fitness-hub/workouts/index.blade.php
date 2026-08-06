@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb] min-h-screen">

<div class="max-w-7xl mx-auto px-6">

<div class="text-center mb-20">

<p class="text-yellow-500 uppercase tracking-[4px] font-bold mb-4">
FREE WORKOUTS
</p>

<h1 class="text-6xl font-black text-black mb-6">
Workout Library
</h1>

<p class="text-xl text-gray-600 max-w-3xl mx-auto">
Free workout plans designed by {{ $setting?->site_name ?: config('app.name') }}.
</p>

</div>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

@foreach($workouts as $workout)

<a href="{{ route('fitness-hub.workouts.show',$workout->slug) }}"
class="bg-white rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition block">

@if($workout->thumbnail)

<img
src="{{ asset('storage/'.$workout->thumbnail) }}"
class="w-full h-64 object-cover">

@endif

<div class="p-8">

<p class="text-yellow-500 font-bold mb-3">
{{ $workout->difficulty }}
</p>

<h2 class="text-3xl font-black mb-4">
{{ $workout->title }}
</h2>

<p class="text-gray-600">
{{ Str::limit($workout->description,120) }}
</p>

</div>

</a>

@endforeach

</div>

</div>

</section>

@endsection
