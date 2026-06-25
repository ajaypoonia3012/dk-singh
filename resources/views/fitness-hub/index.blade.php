@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb] min-h-screen">

<div class="max-w-7xl mx-auto px-6">

<div class="text-center mb-20">

<p class="text-yellow-500 uppercase tracking-[4px] font-bold mb-4">
FITNESS HUB
</p>

<h1 class="text-6xl font-black text-black mb-6">
Free Fitness Resources
</h1>

<p class="text-xl text-gray-600 max-w-3xl mx-auto">
Free workouts, diet plans, articles and transformation stories.
Learn first. Join coaching when you're ready.
</p>

</div>

{{-- WORKOUTS --}}

<div class="mb-24">

<h2 class="text-4xl font-black mb-10">
🔥 Free Workout Plans
</h2>

<div class="grid md:grid-cols-3 gap-8">

@foreach($workouts as $workout)

<a href="{{ route('fitness-hub.workouts.show',$workout->slug) }}"
class="bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition block">

@if($workout->thumbnail)

<img
src="{{ asset('storage/'.$workout->thumbnail) }}"
class="w-full h-64 object-cover">

@endif

<div class="p-6">

<h3 class="text-2xl font-black mb-3">
{{ $workout->title }}
</h3>

<p class="text-gray-600">
{{ Str::limit($workout->description,120) }}
</p>

</div>

</a>

@endforeach

</div>

<div class="text-center mt-10">

    <a href="{{ route('fitness-hub.workouts.index') }}"
       class="bg-black text-white px-8 py-4 rounded-2xl font-bold">

        View All Workouts

    </a>

</div>


</div>

{{-- DIETS --}}

<div class="mb-24">

<h2 class="text-4xl font-black mb-10">
🥗 Free Diet Plans
</h2>

<div class="grid md:grid-cols-3 gap-8">

@foreach($diets as $diet)

<a href="{{ route('fitness-hub.diets.show',$diet->slug) }}"
class="bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition block">

<h3 class="text-2xl font-black mb-3">
{{ $diet->title }}
</h3>

<p class="text-gray-600">
{{ Str::limit($diet->description,120) }}
</p>

</a>

@endforeach

</div>

<div class="text-center mt-10">

    <a href="{{ route('fitness-hub.diets.index') }}"
       class="bg-black text-white px-8 py-4 rounded-2xl font-bold">

        View All Diet Plans

    </a>

</div>



</div>

{{-- BLOGS --}}

<div class="mb-24">

<h2 class="text-4xl font-black mb-10">
📝 Fitness Articles
</h2>

<div class="grid md:grid-cols-3 gap-8">

@foreach($blogs as $blog)

<a href="/blog/{{ $blog->slug }}"
class="bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition block">

<h3 class="text-2xl font-black mb-3">
{{ $blog->title }}
</h3>

</a>

@endforeach

</div>

</div>

{{-- TRANSFORMATIONS --}}

<div>

<h2 class="text-4xl font-black mb-10">
🏆 Success Stories
</h2>

<div class="grid md:grid-cols-3 gap-8">

@foreach($transformations as $transformation)

<a href="/transformations/{{ $transformation->id }}"
class="bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition block">

@if($transformation->image)

<img
src="{{ asset('storage/'.$transformation->image) }}"
class="w-full h-64 object-cover">

@endif

<div class="p-6">

<h3 class="text-2xl font-black">
{{ $transformation->name }}
</h3>

</div>

</a>

@endforeach

</div>

</div>

</div>

<div class="mt-24">

    <div class="bg-black rounded-[40px] p-16 text-center">

        <h2 class="text-5xl font-black text-white mb-6">

            Ready For Faster Results?

        </h2>

        <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-10">

            Free resources are a great start.
            Get personalized coaching, nutrition guidance,
            and transformation programs from DK Singh.

        </p>

        <div class="flex flex-wrap justify-center gap-5">

            <a href="/programs"
               class="bg-yellow-500 text-black px-8 py-4 rounded-2xl font-black">

                View Programs

            </a>

            <a href="/services"
               class="border border-white text-white px-8 py-4 rounded-2xl font-black">

                View Services

            </a>

            <a href="/plans"
               class="border border-yellow-500 text-yellow-500 px-8 py-4 rounded-2xl font-black">

                View Membership Plans

            </a>

        </div>

    </div>

</div>


</section>

@endsection