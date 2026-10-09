@extends('layouts.app')

@section('content')

<x-theme.section class="theme-surface-muted min-h-screen">

    <x-theme.page-container>

        @if(auth()->user()->age)

            {{-- PROFILE DASHBOARD --}}

            <div class="flex items-center justify-between mb-16">

                <div>

                    <p class="theme-eyebrow mb-4">
                        Fitness Profile
                    </p>

                    <h1 class="theme-section-heading text-5xl mb-4">

                        {{ auth()->user()->name }}

                    </h1>

                    <p class="theme-section-subtitle text-xl">
                        Your fitness transformation dashboard.
                    </p>

                </div>

                <x-theme.button href="#edit-profile" variant="secondary">
                    Update Profile
                </x-theme.button>

            </div>

            {{-- STATS --}}
            <div class="grid md:grid-cols-4 gap-8 mb-16">

                <x-theme.card class="theme-card-padding">

                    <p class="theme-text-neutral mb-3">
                        BMI
                    </p>

                    <h2 class="theme-section-heading theme-text-primary text-5xl">

                        {{ auth()->user()->bmi ?? '--' }}

                    </h2>

                </x-theme.card>

                <x-theme.card class="theme-card-padding">

                    <p class="theme-text-neutral mb-3">
                        Weight
                    </p>

                    <h2 class="theme-section-heading text-5xl">

                        {{ auth()->user()->weight ?? '--' }} KG

                    </h2>

                </x-theme.card>

                <x-theme.card class="theme-card-padding">

                    <p class="theme-text-neutral mb-3">
                        Height
                    </p>

                    <h2 class="theme-section-heading text-5xl">

                        {{ auth()->user()->height ?? '--' }} CM

                    </h2>

                </x-theme.card>

                <x-theme.card class="theme-card-padding">

                    <p class="theme-text-neutral mb-3">
                        Goal
                    </p>

                    <h2 class="theme-section-heading text-2xl">

                        {{ auth()->user()->goal ?? '--' }}

                    </h2>

                </x-theme.card>

            </div>

        @endif

        {{-- EDIT FORM --}}
        <x-theme.card id="edit-profile" class="theme-card-padding">

            <h2 class="theme-section-heading text-3xl mb-10">

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

                        <x-theme.label for="name" class="block mb-3">
                            Full Name
                        </x-theme.label>

                        <x-theme.input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', auth()->user()->name) }}"
                            class="w-full px-5 py-4" />

                    </div>

                    <div>

                        <x-theme.label for="email" class="block mb-3">
                            Email
                        </x-theme.label>

                        <x-theme.input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', auth()->user()->email) }}"
                            class="w-full px-5 py-4" />

                    </div>

                    <div>

                        <x-theme.label for="weight" class="block mb-3">
                            Weight (KG)
                        </x-theme.label>

                        <x-theme.input
                            id="weight"
                            type="number"
                            step="0.01"
                            name="weight"
                            value="{{ old('weight', auth()->user()->weight) }}"
                            class="w-full px-5 py-4" />

                    </div>

                    <div>

                        <x-theme.label for="height" class="block mb-3">
                            Height (CM)
                        </x-theme.label>

                        <x-theme.input
                            id="height"
                            type="number"
                            step="0.01"
                            name="height"
                            value="{{ old('height', auth()->user()->height) }}"
                            class="w-full px-5 py-4" />

                    </div>

                    <div>

                        <x-theme.label for="goal" class="block mb-3">
                            Goal
                        </x-theme.label>

                        <x-theme.input
                            id="goal"
                            type="text"
                            name="goal"
                            value="{{ old('goal', auth()->user()->goal) }}"
                            class="w-full px-5 py-4" />

                    </div>

                    <div>

                        <x-theme.label for="age" class="block mb-3">
                            Age
                        </x-theme.label>

                        <x-theme.input
                            id="age"
                            type="number"
                            name="age"
                            value="{{ old('age', auth()->user()->age) }}"
                            class="w-full px-5 py-4" />

                    </div>

                </div>

                <x-theme.button type="submit">
                    Save Profile
                </x-theme.button>

            </form>

        </x-theme.card>

    </x-theme.page-container>

</x-theme.section>

@endsection
