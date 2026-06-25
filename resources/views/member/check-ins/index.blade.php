@extends('layouts.app')

@section('content')

<section class="min-h-screen bg-[#f6f3eb] py-20">

    <div class="max-w-3xl mx-auto px-6">

        <div class="bg-white rounded-3xl shadow-xl p-10">

            <h1 class="text-4xl font-black mb-10">
                Weekly Check-In
            </h1>

            <form method="POST"
                  action="{{ route('member.check-ins.store') }}"
                  class="space-y-6">

                @csrf

                <div>
                    <label class="font-bold">
                        Weight (kg)
                    </label>

                    <input
                        type="number"
                        step="0.1"
                        name="weight"
                        required
                        class="w-full mt-2 rounded-xl border p-3">
                </div>

                <div>
                    <label class="font-bold">
                        Waist (cm)
                    </label>

                    <input
                        type="number"
                        step="0.1"
                        name="waist"
                        required
                        class="w-full mt-2 rounded-xl border p-3">
                </div>

                <div>
                    <label class="font-bold">
                        Energy Level (1-10)
                    </label>

                    <input
                        type="number"
                        min="1"
                        max="10"
                        name="energy_level"
                        required
                        class="w-full mt-2 rounded-xl border p-3">
                </div>

                <div>
                    <label class="font-bold">
                        Mood (1-10)
                    </label>

                    <input
                        type="number"
                        min="1"
                        max="10"
                        name="mood"
                        required
                        class="w-full mt-2 rounded-xl border p-3">
                </div>

                <div>
                    <label class="font-bold">
                        Sleep Hours
                    </label>

                    <input
                        type="number"
                        step="0.5"
                        name="sleep_hours"
                        required
                        class="w-full mt-2 rounded-xl border p-3">
                </div>

                <div>
                    <label class="font-bold">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        rows="4"
                        class="w-full mt-2 rounded-xl border p-3"></textarea>
                </div>

                <button
                    type="submit"
                    class="bg-yellow-500 hover:bg-yellow-400 px-8 py-4 rounded-2xl font-bold">

                    Submit Check-In

                </button>

            </form>

        </div>

    </div>

</section>

@endsection