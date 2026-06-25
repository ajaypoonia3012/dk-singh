@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-12">

    <h1 class="text-4xl font-black mb-8">
        Billing History
    </h1>

    <div class="bg-white rounded-3xl shadow-xl p-8">

        @if($orders->count())

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="border-b">

                            <th class="text-left p-4">
                                Date
                            </th>

                            <th class="text-left p-4">
                                Amount
                            </th>

                            <th class="text-left p-4">
                                Gateway
                            </th>

                            <th class="text-left p-4">
                                Status
                            </th>

                            <th class="text-left p-4">
                                Payment ID
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($orders as $order)

                        <tr class="border-b">

                            <td class="p-4">
                                {{ $order->created_at->format('d M Y') }}
                            </td>

                            <td class="p-4">
                                ₹{{ number_format($order->amount,2) }}
                            </td>

                            <td class="p-4">
                                {{ strtoupper($order->payment_gateway) }}
                            </td>

                            <td class="p-4">
                                {{ ucfirst($order->payment_status) }}
                            </td>

                            <td class="p-4">
                                {{ $order->payment_id }}
                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <p>
                No billing history found.
            </p>

        @endif

    </div>

</div>

@endsection