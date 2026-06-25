@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-12">

    <h1 class="text-4xl font-black mb-8">
        My Profile
    </h1>
@if(session('success'))

<div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6">
    {{ session('success') }}
</div>

@endif

@if($user->profile_completed)

<div class="bg-white rounded-3xl shadow-xl p-8">

    <h2 class="text-2xl font-bold mb-6">
        Profile Information
    </h2>

    <div class="grid md:grid-cols-2 gap-4">

        <div><strong>Phone:</strong> {{ $user->phone }}</div>
        <div><strong>WhatsApp:</strong> {{ $user->whatsapp_number }}</div>

        <div><strong>Gender:</strong> {{ $user->gender }}</div>
        <div><strong>Age:</strong> {{ $user->age }}</div>

        <div><strong>Height:</strong> {{ $user->height }}</div>
        <div><strong>Weight:</strong> {{ $user->weight }}</div>

        <div><strong>BMI:</strong> {{ $user->bmi }}</div>
        <div><strong>Goal:</strong> {{ $user->goal }}</div>

        <div><strong>Activity:</strong> {{ $user->activity_level }}</div>
        <div><strong>City:</strong> {{ $user->city }}</div>
<div><strong>Country:</strong> {{ $user->country }}</div>

<div><strong>Diet Preference:</strong>
{{ $user->diet_preference }}
</div>

<div><strong>Allergies:</strong>
{{ $user->allergies }}
</div>

<div><strong>Medical Conditions:</strong>
{{ $user->medical_conditions }}
</div>

<div class="md:col-span-2">
<strong>Bio:</strong>
{{ $user->bio }}
</div>

    </div>

</div>

@else
    
    <form method="POST"
          action="{{ route('member.profile.update') }}">

        @csrf

        <div class="bg-white rounded-3xl shadow-xl p-8">

            <h2 class="text-2xl font-bold mb-6">
                Personal Information
            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <input
                    type="text"
                    name="phone"
                    value="{{ $user->phone }}"
                    placeholder="Phone"
                    class="border rounded-xl p-3 w-full">

                <input
                    type="text"
                    name="whatsapp_number"
                    value="{{ $user->whatsapp_number }}"
                    placeholder="WhatsApp Number"
                    class="border rounded-xl p-3 w-full">

<div class="grid md:grid-cols-2 gap-6 mt-6">

    <input
        type="text"
        name="city"
        value="{{ $user->city }}"
        placeholder="City"
        class="border rounded-xl p-3 w-full">

    <input
        type="text"
        name="country"
        value="{{ $user->country }}"
        placeholder="Country"
        class="border rounded-xl p-3 w-full">

</div>

                
<select
    name="gender"
    class="border rounded-xl p-3 w-full">

    <option value="">Select Gender</option>

    <option value="Male"
        {{ $user->gender == 'Male' ? 'selected' : '' }}>
        Male
    </option>

    <option value="Female"
        {{ $user->gender == 'Female' ? 'selected' : '' }}>
        Female
    </option>

    <option value="Other"
        {{ $user->gender == 'Other' ? 'selected' : '' }}>
        Other
    </option>

</select>

                <input
                    type="number"
                    name="age"
                    value="{{ $user->age }}"
                    placeholder="Age"
                    class="border rounded-xl p-3 w-full">

            </div>

            <hr class="my-8">

            <h2 class="text-2xl font-bold mb-6">
                Fitness Information
            </h2>

            <div class="grid md:grid-cols-3 gap-6">

                <input
                    type="text"
                    name="height"
                    value="{{ $user->height }}"
                    placeholder="Height"
                    class="border rounded-xl p-3">

                <input
                    type="text"
                    name="weight"
                    value="{{ $user->weight }}"
                    placeholder="Weight"
                    class="border rounded-xl p-3">

                <input
    type="text"
    name="bmi"
    value="{{ $user->bmi }}"
    placeholder="BMI (Auto Calculated)"
    readonly
    class="border rounded-xl p-3 bg-gray-100">

            </div>

            <div class="mt-6">

                <select
    name="goal"
    class="border rounded-xl p-3 w-full">

    <option value="">Select Goal</option>

    <option value="Weight Loss" {{ $user->goal == 'Weight Loss' ? 'selected' : '' }}>
        Weight Loss
    </option>

    <option value="Weight Gain" {{ $user->goal == 'Weight Gain' ? 'selected' : '' }}>
        Weight Gain
    </option>

    <option value="Muscle Building" {{ $user->goal == 'Muscle Building' ? 'selected' : '' }}>
        Muscle Building
    </option>

    <option value="Fat Loss" {{ $user->goal == 'Fat Loss' ? 'selected' : '' }}>
        Fat Loss
    </option>

    <option value="Body Recomposition" {{ $user->goal == 'Body Recomposition' ? 'selected' : '' }}>
        Body Recomposition
    </option>

    <option value="General Fitness" {{ $user->goal == 'General Fitness' ? 'selected' : '' }}>
        General Fitness
    </option>

</select>

            </div>

            <div class="mt-6">

                <select
    name="activity_level"
    class="border rounded-xl p-3 w-full">

    <option value="">Select Activity Level</option>

    <option value="Sedentary" {{ $user->activity_level == 'Sedentary' ? 'selected' : '' }}>
        Sedentary
    </option>

    <option value="Lightly Active" {{ $user->activity_level == 'Lightly Active' ? 'selected' : '' }}>
        Lightly Active
    </option>

    <option value="Moderately Active" {{ $user->activity_level == 'Moderately Active' ? 'selected' : '' }}>
        Moderately Active
    </option>

    <option value="Very Active" {{ $user->activity_level == 'Very Active' ? 'selected' : '' }}>
        Very Active
    </option>

    <option value="Athlete" {{ $user->activity_level == 'Athlete' ? 'selected' : '' }}>
        Athlete
    </option>

</select>

            </div>

            <hr class="my-8">

            <h2 class="text-2xl font-bold mb-6">
                Nutrition
            </h2>

            <textarea
                name="diet_preference"
                placeholder="Diet Preference"
                class="border rounded-xl p-3 w-full mb-4">{{ $user->diet_preference }}</textarea>

            <textarea
                name="allergies"
                placeholder="Allergies"
                class="border rounded-xl p-3 w-full mb-4">{{ $user->allergies }}</textarea>

            <textarea
                name="medical_conditions"
                placeholder="Medical Conditions"
                class="border rounded-xl p-3 w-full">{{ $user->medical_conditions }}</textarea>

            <hr class="my-8">

            <h2 class="text-2xl font-bold mb-6">
                About
            </h2>

            <textarea
                name="bio"
                placeholder="Bio"
                class="border rounded-xl p-3 w-full">{{ $user->bio }}</textarea>

            <div class="mt-8">

                <button
                    type="submit"
                    class="bg-black text-white px-8 py-3 rounded-xl">

                    Save Profile

                </button>

            </div>

        </div>

    </form>

</div>
@endif
@endsection