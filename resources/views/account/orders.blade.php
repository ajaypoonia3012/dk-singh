@extends('layouts.app')

@section('content')

<div class="theme-page-container py-12">

    <h1 class="text-4xl font-black mb-8">
        My Orders
    </h1>

    <div class="theme-card theme-radius theme-shadow theme-card-padding">

        @if($orders->count())

            <table class="w-full">

                <thead>

                    <tr class="border-b">

                        <th class="text-left p-4">
                            Order #
                        </th>

                        <th class="text-left p-4">
                            Product
                        </th>

                        <th class="text-left p-4">
                            Amount
                        </th>

                        <th class="text-left p-4">
                            Status
                        </th>

                        <th class="text-left p-4">
                            Invoice
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($orders as $order)

                    <tr class="border-b">

                        <td class="p-4">
                            <a
 href="{{ route('account.orders.show',$order) }}"
 class="theme-text-info font-bold"
>
    {{ $order->order_number }}
</a>
                        </td>

                        <td class="p-4">
                            {{ optional($order->product)->name }}
                        </td>

                        <td class="p-4">
                            &#8377;{{ number_format($order->amount,2) }}
                        </td>

                        <td class="p-4">
                            {{ ucfirst($order->order_status) }}
                        </td>

                        <td class="p-4">

                            <a
                                href="{{ route('invoice.download',$order->id) }}"
                                class="theme-text-info font-bold"
                            >
                                Download
                            </a>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            <p>No orders found.</p>

        @endif

    </div>

</div>

@endsection
