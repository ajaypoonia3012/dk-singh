@extends('layouts.app')

@section('content')

<section class="min-h-screen bg-[#f6f3eb] py-20">

<div class="max-w-6xl mx-auto px-6">

    <h1 class="text-5xl font-black mb-6">
        My Action Plan
    </h1>

    <div class="bg-white rounded-[30px] p-8 shadow-xl mb-10">

        <h2 class="text-2xl font-bold mb-2">
            Progress
        </h2>

        <p class="text-lg">
            {{ $completed }} / {{ $total }} Tasks Completed
        </p>

    </div>

    @forelse($actionPlans as $plan)

        <div class="bg-white rounded-[30px] p-8 shadow-xl mb-6">

            <div class="flex justify-between items-center">

                <div>

                    <h3 class="text-2xl font-bold">
                        {{ $plan->title }}
                    </h3>

                    @if($plan->description)

                        <p class="text-gray-600 mt-3">
                            {{ $plan->description }}
                        </p>

                    @endif

                    @if($plan->due_date)

                        <p class="text-sm text-gray-500 mt-3">
                            Due:
                            {{ $plan->due_date->format('d M Y') }}
                        </p>

                    @endif

                </div>

                <div>

                    @if($plan->is_completed)

    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full">
        Completed
    </span>

@else

    <form
        method="POST"
        action="{{ route('member.action-plan.complete', $plan) }}"
    >
        @csrf

        <button
            class="bg-yellow-500 text-white px-4 py-2 rounded-full"
        >
            Mark Complete
        </button>

    </form>

@endif

                </div>

            </div>

        </div>

    @empty

        <div class="bg-white rounded-[30px] p-8 shadow-xl">

            No action plans assigned yet.

        </div>

    @endforelse

</div>

</section>

@endsection