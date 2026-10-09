
@extends('layouts.app')

@section('content')

<section class="theme-section theme-surface-muted min-h-screen">

    <div class="max-w-7xl mx-auto px-5">

        <h1 class="text-6xl font-bold">
            My Purchases
        </h1>

        @if($membership)

            <div class="theme-surface-strong theme-text-on-strong theme-radius theme-card-padding-lg mt-12">

                <h2 class="text-4xl font-bold">
                    Active Membership
                </h2>

                <div class="grid md:grid-cols-3 theme-grid-gap mt-10">

                    <div>

                        <p class="theme-text-neutral">
                            Status
                        </p>

                        <h3 class="text-3xl font-bold theme-text-success">
                            Active
                        </h3>

                    </div>

                    <div>

                        <p class="theme-text-neutral">
                            Start Date
                        </p>

                        <h3 class="text-2xl font-bold">

                            {{ $membership->starts_at }}

                        </h3>

                    </div>

                    <div>

                        <p class="theme-text-neutral">
                            Expiry Date
                        </p>

                        <h3 class="text-2xl font-bold">

                            {{ $membership->expires_at }}

                        </h3>

                    </div>

                </div>

            </div>

        @endif

        <div class="mt-20">

            <h2 class="text-4xl font-bold">
                Order History
            </h2>

            <div class="mt-10 theme-card theme-radius theme-shadow overflow-hidden">

                <table class="w-full">

                    <thead class="theme-surface-strong theme-text-on-strong">

                        <tr>

                            <th class="p-5 text-left">
                                Order ID
                            </th>

                            <th class="p-5 text-left">
                                Item
                            </th>

                            <th class="p-5 text-left">
                                Amount
                            </th>

                            <th class="p-5 text-left">
                                Status
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($orders as $order)

                            <tr class="border-b">

                                <td class="p-5">
                                    #{{ $order->id }}
                                </td>

                                <td class="p-5">

                                    {{ $order->item_type }}

                                </td>

                                <td class="p-5">

                                    &#8377;{{ $order->amount }}

                                </td>

                                <td class="p-5">

                                    {{ $order->payment_status }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</section>

@endsection
