@extends('layouts.app')

@section('content')

<section class="min-h-screen theme-surface-muted theme-section">

    <div class="max-w-3xl mx-auto px-6">

        <div class="theme-card theme-radius theme-shadow theme-card-padding-lg">

            <h1 class="text-4xl font-black mb-10">
                Weekly Check-In
            </h1>

            <form method="POST"
                  action="{{ route('member.check-ins.store') }}"
                  class="theme-stack-lg">

                @csrf

                <div>
                    <label class="theme-label font-bold">
                        Weight (kg)
                    </label>

                    <input
                        type="number"
                        step="0.1"
                        name="weight"
                        required
                        class="theme-form-control w-full mt-2 theme-radius border p-3">
                </div>

                <div>
                    <label class="theme-label font-bold">
                        Waist (cm)
                    </label>

                    <input
                        type="number"
                        step="0.1"
                        name="waist"
                        required
                        class="theme-form-control w-full mt-2 theme-radius border p-3">
                </div>

                <div>
                    <label class="theme-label font-bold">
                        Energy Level (1-10)
                    </label>

                    <input
                        type="number"
                        min="1"
                        max="10"
                        name="energy_level"
                        required
                        class="theme-form-control w-full mt-2 theme-radius border p-3">
                </div>

                <div>
                    <label class="theme-label font-bold">
                        Mood (1-10)
                    </label>

                    <input
                        type="number"
                        min="1"
                        max="10"
                        name="mood"
                        required
                        class="theme-form-control w-full mt-2 theme-radius border p-3">
                </div>

                <div>
                    <label class="theme-label font-bold">
                        Sleep Hours
                    </label>

                    <input
                        type="number"
                        step="0.5"
                        name="sleep_hours"
                        required
                        class="theme-form-control w-full mt-2 theme-radius border p-3">
                </div>

                <div>
                    <label class="theme-label font-bold">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        rows="4"
                        class="theme-form-control w-full mt-2 theme-radius border p-3"></textarea>
                </div>

                <button
                    type="submit"
                    class="theme-status-warning hover:theme-status-warning px-8 py-4 theme-radius font-bold">

                    Submit Check-In

                </button>

            </form>

        </div>

    </div>

</section>

@endsection
