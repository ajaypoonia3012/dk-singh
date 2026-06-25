@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-6 py-12">

```
<h1 class="text-4xl font-black mb-8">
    Order Details
</h1>

<div class="bg-white rounded-3xl shadow-xl p-8">

    <div class="grid md:grid-cols-2 gap-8">

        <div>
            <h3 class="font-bold text-gray-500 mb-2">
                Order Number
            </h3>

            <p class="text-xl font-bold">
                {{ $order->order_number }}
            </p>
        </div>

        <div>
            <h3 class="font-bold text-gray-500 mb-2">
                Invoice Number
            </h3>

            <p class="text-xl font-bold">
                {{ $order->invoice_number }}
            </p>
        </div>

        <div>
            <h3 class="font-bold text-gray-500 mb-2">
                Order Date
            </h3>

            <p>
                {{ $order->created_at->format('d M Y h:i A') }}
            </p>
        </div>

        <div>
            <h3 class="font-bold text-gray-500 mb-2">
                Payment Status
            </h3>

            <p>
                {{ ucfirst($order->payment_status) }}
            </p>
        </div>

        <div>
            <h3 class="font-bold text-gray-500 mb-2">
                Order Status
            </h3>

            <p>
                {{ ucfirst($order->order_status) }}
            </p>
        </div>

        <div>
            <h3 class="font-bold text-gray-500 mb-2">
                Amount
            </h3>

            <p class="text-xl font-bold">
                ₹{{ number_format($order->amount,2) }}
            </p>
        </div>

    </div>

    <hr class="my-8">

    <h2 class="text-2xl font-bold mb-4">
        Shipment Timeline
    </h2>

    @if($order->shipment)

        <div class="space-y-4">

            @foreach($order->shipment->events as $event)

                <div class="border-l-4 border-yellow-500 pl-4">

                    <div class="font-bold">
                        {{ ucwords(str_replace('_',' ',$event->event_type)) }}
                    </div>

                    <div class="text-gray-600 text-sm">
                        {{ $event->event_time->format('d M Y h:i A') }}
                    </div>

                    @if($event->message)

                        <div class="text-gray-700 mt-1">
                            {{ $event->message }}
                        </div>

                    @endif

                </div>

            @endforeach

        </div>

    @else

        <p>
            Shipment has not been created yet.
        </p>

    @endif

    <hr class="my-8">

    <h2 class="text-2xl font-bold mb-4">
        Product
    </h2>

    <p class="text-lg">
        {{ optional($order->product)->name ?? 'Product Removed' }}
    </p>

    <hr class="my-8">

    <h2 class="text-2xl font-bold mb-4">
        Shipping Address
    </h2>

    <p>{{ $order->shipping_address }}</p>
    <p>{{ $order->city }}</p>
    <p>{{ $order->state }}</p>
    <p>{{ $order->pincode }}</p>

    <hr class="my-8">

    <h2 class="text-2xl font-bold mb-4">
        Shipping Information
    </h2>

    <p>
        <strong>Courier:</strong>
        {{ optional($order->shipment?->courierProvider)->name ?? 'Not Assigned Yet' }}
    </p>

    <p class="mt-2">
        <strong>Tracking Number:</strong>
        {{ $order->shipment?->tracking_number ?? 'Not Available Yet' }}
    </p>

    <p class="mt-2">
        <strong>AWB Number:</strong>
        {{ $order->shipment?->awb_number ?? 'Not Available Yet' }}
    </p>

    @if($order->shipment?->tracking_url)

        <a
            href="{{ $order->shipment->tracking_url }}"
            target="_blank"
            class="text-blue-600 font-bold"
        >
            Track Shipment
        </a>

    @endif

    <hr class="my-8">

    <a
        href="{{ route('invoice.download',$order->id) }}"
        class="inline-block bg-yellow-500 hover:bg-yellow-400 px-6 py-3 rounded-xl font-bold"
    >
        Download Invoice
    </a>

    <a
        href="{{ route('account.orders') }}"
        class="inline-block ml-4 border border-black px-6 py-3 rounded-xl font-bold"
    >
        Back To Orders
    </a>

</div>
```

</div>

@endsection
