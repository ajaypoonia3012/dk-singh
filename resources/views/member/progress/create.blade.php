@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto py-12 px-6">

<h1 class="text-4xl font-black mb-8">
Add Progress Check-In
</h1>

<form method="POST"
      action="{{ route('member.progress.store') }}"
      enctype="multipart/form-data">

@csrf

<div class="theme-card theme-radius theme-shadow theme-card-padding">

<input type="text"
       name="weight"
       placeholder="Weight"
       class="theme-form-control border p-3 theme-radius w-full mb-4">



<input type="text"
       name="body_fat"
       placeholder="Body Fat %"
       class="theme-form-control border p-3 theme-radius w-full mb-4">

<input type="text"
       name="chest"
       placeholder="Chest"
       class="theme-form-control border p-3 theme-radius w-full mb-4">

<input type="text"
       name="waist"
       placeholder="Waist"
       class="theme-form-control border p-3 theme-radius w-full mb-4">

<input type="text"
       name="arms"
       placeholder="Arms"
       class="theme-form-control border p-3 theme-radius w-full mb-4">

<input type="text"
       name="thighs"
       placeholder="Thighs"
       class="theme-form-control border p-3 theme-radius w-full mb-4">

<hr class="my-6">

<h2 class="text-2xl font-bold mb-4">
Transformation Photos
</h2>

<label class="theme-label block mb-2">
Front Photo
</label>

<input
    type="file"
    name="front_photo"
    class="theme-form-control border p-3 theme-radius w-full mb-4">

<label class="theme-label block mb-2">
Side Photo
</label>

<input
    type="file"
    name="side_photo"
    class="theme-form-control border p-3 theme-radius w-full mb-4">

<label class="theme-label block mb-2">
Back Photo
</label>

<input
    type="file"
    name="back_photo"
    class="theme-form-control border p-3 theme-radius w-full mb-4">



<textarea
    name="notes"
    class="theme-form-control border p-3 theme-radius w-full mb-4"
    placeholder="Notes"></textarea>

<button
    class="theme-surface-strong theme-text-on-strong px-8 py-3 theme-radius">

    Save Check-In

</button>

</div>

</form>

</div>

@endsection
