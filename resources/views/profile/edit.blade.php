@extends('layouts.app')

@section('content')

<section class="bg-[#f6f3eb] min-h-screen py-24">

    <div class="max-w-7xl mx-auto px-6">

        @if(auth()->user()->age)

            {{-- PROFILE DASHBOARD --}}

            <div class="flex items-center justify-between mb-16">

                <div>

                    <p class="uppercase tracking-[3px] text-yellow-500 font-bold mb-4">
                        Fitness Profile
                    </p>

                    <h1 class="text-5xl font-black text-black mb-4">

                        {{ auth()->user()->name }}

                    </h1>

                    <p class="text-xl text-gray-600">
                        Your fitness transformation dashboard.
                    </p>

                </div>

                <a href="#edit-profile"
                   class="bg-black text-white px-8 py-4 rounded-2xl font-bold">

                    Update Profile

                </a>

            </div>

            {{-- STATS --}}
            <div class="grid md:grid-cols-4 gap-8 mb-16">

                <div class="bg-white rounded-[32px] p-8 shadow-xl">

                    <p class="text-gray-500 mb-3">
                        BMI
                    </p>

                    <h2 class="text-5xl font-black text-yellow-500">

                        {{ auth()->user()->bmi ?? '--' }}

                    </h2>

                </div>

                <div class="bg-white rounded-[32px] p-8 shadow-xl">

                    <p class="text-gray-500 mb-3">
                        Weight
                    </p>

                    <h2 class="text-5xl font-black text-black">

                        {{ auth()->user()->weight ?? '--' }} KG

                    </h2>

                </div>

                <div class="bg-white rounded-[32px] p-8 shadow-xl">

                    <p class="text-gray-500 mb-3">
                        Height
                    </p>

                    <h2 class="text-5xl font-black text-black">

                        {{ auth()->user()->height ?? '--' }} CM

                    </h2>

                </div>

                <div class="bg-white rounded-[32px] p-8 shadow-xl">

                    <p class="text-gray-500 mb-3">
                        Goal
                    </p>

                    <h2 class="text-2xl font-black text-black">

                        {{ auth()->user()->goal ?? '--' }}

                    </h2>

                </div>

            </div>

        @endif

        {{-- EDIT FORM --}}
        <div id="edit-profile"
             class="bg-white rounded-[36px] p-10 shadow-xl">

            <h2 class="text-3xl font-black mb-10">

                {{ auth()->user()->age
                    ? 'Update Health Profile'
                    : 'Complete Your Health Profile'
                }}

            </h2>

            <form
                method="POST"
                action="{{ route('profile.update') }}"
                class="space-y-8">

                @csrf
                @method('PATCH')

                <div class="grid md:grid-cols-2 gap-8">

                    <div>

                        <label class="font-bold block mb-3">
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', auth()->user()->name) }}"
                            class="w-full rounded-2xl border border-gray-300 px-5 py-4">

                    </div>

                    <div>

                        <label class="font-bold block mb-3">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', auth()->user()->email) }}"
                            class="w-full rounded-2xl border border-gray-300 px-5 py-4">

                    </div>

                    <div>

                        <label class="font-bold block mb-3">
                            Weight (KG)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="weight"
                            value="{{ old('weight', auth()->user()->weight) }}"
                            class="w-full rounded-2xl border border-gray-300 px-5 py-4">

                    </div>

                    <div>

                        <label class="font-bold block mb-3">
                            Height (CM)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="height"
                            value="{{ old('height', auth()->user()->height) }}"
                            class="w-full rounded-2xl border border-gray-300 px-5 py-4">

                    </div>

                    <div>

                        <label class="font-bold block mb-3">
                            Goal
                        </label>

                        <input
                            type="text"
                            name="goal"
                            value="{{ old('goal', auth()->user()->goal) }}"
                            class="w-full rounded-2xl border border-gray-300 px-5 py-4">

                    </div>

                    <div>

                        <label class="font-bold block mb-3">
                            Age
                        </label>

                        <input
                            type="number"
                            name="age"
                            value="{{ old('age', auth()->user()->age) }}"
                            class="w-full rounded-2xl border border-gray-300 px-5 py-4">

                    </div>

                </div>

                <button
                    type="submit"
                    class="bg-yellow-500 hover:bg-yellow-400 text-black px-10 py-5 rounded-2xl font-black transition">

                    Save Profile

                </button>

            </form>

        </div>

    </div>

</section>

@endsection