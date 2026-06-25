@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-6 py-12">

    <h1 class="text-4xl font-black mb-8">
        My Plan
    </h1>

    @if($membership)

    <div class="bg-white rounded-3xl shadow-xl p-8">

        <div class="flex justify-between items-start">

            <div>

                <h2 class="text-3xl font-bold">
                    {{ $membership->plan->name }}
                </h2>

                <div class="mt-2">

                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                        Active Membership
                    </span>

                </div>

            </div>

            <div class="text-right">

                <p class="text-sm text-gray-500">
                    Membership ID
                </p>

                <p class="font-bold">
                    #{{ $membership->id }}
                </p>

            </div>

        </div>

        <hr class="my-6">

        <div class="grid md:grid-cols-2 gap-6">

            <div>

                <p>
                    <strong>Status:</strong>
                    Active
                </p>

                <p>
                    <strong>Purchase Date:</strong>
                    {{ $membership->created_at->format('d M Y') }}
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

            <div>

                @if($orders->count())

                    <p>
                        <strong>Payment Method:</strong>
                        {{ strtoupper($orders->first()->payment_gateway) }}
                    </p>

                    <p>
                        <strong>Last Payment:</strong>
                        ₹{{ number_format($orders->first()->amount, 2) }}
                    </p>

                    <p>
                        <strong>Payment ID:</strong>
                        {{ $orders->first()->payment_id }}
                    </p>

                @endif

            </div>

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

        <hr class="my-8">

        <h3 class="text-2xl font-bold mb-4">
            Recent Orders
        </h3>

        @if($orders->count())

            <div class="overflow-x-auto">

                <table class="w-full border">

                    <thead>

                        <tr class="bg-gray-100">

                            <th class="p-3 text-left">Date</th>
                            <th class="p-3 text-left">Amount</th>
                            <th class="p-3 text-left">Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($orders as $order)

                            <tr>

                                <td class="p-3">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>

                                <td class="p-3">
                                    ₹{{ number_format($order->amount, 2) }}
                                </td>

                                <td class="p-3">
                                    {{ ucfirst($order->payment_status) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

        <div class="mt-8 flex gap-4">

            <a href="/plans"
               class="bg-black text-white px-6 py-3 rounded-xl">

                Renew Early

<a href="{{ route('member.billing') }}"
   class="bg-yellow-500 text-black px-6 py-3 rounded-xl">

    View Billing History

</a>

        

            @if($membership->plan->access_type === 'basic')

                <a href="/plans"
                   class="bg-yellow-500 text-black px-6 py-3 rounded-xl">

                    Upgrade to Pro

                </a>

            @elseif($membership->plan->access_type === 'pro')

                <a href="/plans"
                   class="bg-yellow-500 text-black px-6 py-3 rounded-xl">

                    Upgrade to Elite

                </a>

            @endif

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

            Choose a Plan

        </a>

    </div>

    @endif

</div>

@endsection