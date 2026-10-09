@extends('layouts.app')

@section('content')

<div class="theme-page-container py-12">

```
<h1 class="text-4xl font-black mb-8">
    Order Details
</h1>

<div class="theme-card theme-radius theme-shadow theme-card-padding">

    <div class="grid md:grid-cols-2 theme-grid-gap">

        <div>
            <h3 class="font-bold theme-text-neutral mb-2">
                Order Number
            </h3>

            <p class="text-xl font-bold">
                {{ $order->order_number }}
            </p>
        </div>

        <div>
            <h3 class="font-bold theme-text-neutral mb-2">
                Invoice Number
            </h3>

            <p class="text-xl font-bold">
                {{ $order->invoice_number }}
            </p>
        </div>

        <div>
            <h3 class="font-bold theme-text-neutral mb-2">
                Order Date
            </h3>

            <p>
                {{ $order->created_at->format('d M Y h:i A') }}
            </p>
        </div>

        <div>
            <h3 class="font-bold theme-text-neutral mb-2">
                Payment Status
            </h3>

            <p>
                {{ ucfirst($order->payment_status) }}
            </p>
        </div>

        <div>
            <h3 class="font-bold theme-text-neutral mb-2">
                Order Status
            </h3>

            <p>
                {{ ucfirst($order->order_status) }}
            </p>
        </div>

        <div>
            <h3 class="font-bold theme-text-neutral mb-2">
                Amount
            </h3>

            <p class="text-xl font-bold">
                &#8377;{{ number_format($order->amount,2) }}
            </p>
        </div>

    </div>

    <hr class="my-8">

    <h2 class="text-2xl font-bold mb-4">
        Shipment Timeline
    </h2>

    @if($order->shipment)

        <div class="theme-stack-md">

            @foreach($order->shipment->events as $event)

                <div class="border-l-4 theme-border pl-4">

                    <div class="font-bold">
                        {{ ucwords(str_replace('_',' ',$event->event_type)) }}
                    </div>

                    <div class="theme-text-neutral text-sm">
                        {{ $event->event_time->format('d M Y h:i A') }}
                    </div>

                    @if($event->message)

                        <div class="theme-text-neutral mt-1">
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
            class="theme-text-info font-bold"
        >
            Track Shipment
        </a>

    @endif

    <hr class="my-8">

    <a
        href="{{ route('invoice.download',$order->id) }}"
        class="inline-block theme-status-warning hover:theme-status-warning px-6 py-3 theme-radius font-bold"
    >
        Download Invoice
    </a>

    <a
        href="{{ route('account.orders') }}"
        class="inline-block ml-4 border theme-border px-6 py-3 theme-radius font-bold"
    >
        Back To Orders
    </a>

</div>
```

</div>

@endsection
