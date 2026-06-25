@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-6 py-12">

    <h1 class="text-4xl font-black mb-8">
        My Plan
    </h1>

    @if($membership)

        <div class="bg-white rounded-3xl shadow-xl p-8">

            <h2 class="text-3xl font-bold mb-6">
                {{ $membership->plan->name }}
<div class="mt-2">

    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">

        Active Membership

    </span>

</div>

            </h2>

            <div class="space-y-3">

                <p>
                    <strong>Status:</strong>
                    Active
                </p>

                <p>
                    <strong>Start Date:</strong>
                    {{ $membership->starts_at->format('d M Y') }}
                </p>

                <p>
                    <strong>Expiry Date:</strong>
                    {{ $membership->expires_at->format('d M Y') }}
                </p>

                <p>
                    <strong>Days Remaining:</strong>
                    {{ $daysRemaining }}
                </p>

            </div>

            <hr class="my-8">

            <h3 class="text-2xl font-bold mb-4">
                Benefits Included
            </h3>

            <ul class="space-y-2">

                @foreach($membership->plan->features as $feature)

                    <li>
                        ✓ {{ $feature['feature'] ?? '' }}
                    </li>

                @endforeach

            </ul>

            <div class="mt-8">

                <a href="/plans"
                   class="bg-black text-white px-6 py-3 rounded-xl">

                    @if($membership->plan->access_type !== 'elite')

Upgrade Plan

@else

View Available Plans

@endif

                </a>

            </div>

        </div>

    @else

        <div class="bg-white rounded-3xl shadow-xl p-8">

            <h2 class="text-3xl font-bold mb-4">
                No Active Membership
            </h2>

            <p class="mb-6">
                You currently do not have an active plan.
            </p>

            <a href="/plans"
               class="bg-black text-white px-6 py-3 rounded-xl">

                View Plans

            </a>

        </div>

    @endif

</div>

@endsection