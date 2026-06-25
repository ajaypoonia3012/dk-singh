@extends('layouts.app')

@section('content')

<section class="bg-[#f6f3eb] py-24 min-h-screen">

<div class="max-w-6xl mx-auto px-6">

<div class="grid lg:grid-cols-3 gap-10">

<div class="lg:col-span-2">

<h1 class="text-5xl font-black mb-6">
{{ $diet->title }}
</h1>

<div class="mb-6">

<span class="bg-yellow-500 text-black px-4 py-2 rounded-full font-bold">
{{ $diet->goal }}
</span>

</div>

<div class="bg-white rounded-3xl p-10 shadow-xl">

{!! $diet->content !!}

</div>

</div>

<div>

<div class="bg-white rounded-3xl p-8 shadow-xl sticky top-32">

<h3 class="text-3xl font-black mb-6">
Need Personalized Nutrition?
</h3>

<a href="/plans"
class="block bg-yellow-500 text-black text-center py-4 rounded-2xl font-black mb-4">
View Plans
</a>

<a href="/services"
class="block bg-black text-white text-center py-4 rounded-2xl font-black mb-4">
Book Coaching
</a>

<a href="/products"
class="block border border-black text-center py-4 rounded-2xl font-black">
Shop Supplements
</a>

</div>

</div>

</div>

</div>

</section>

@endsection