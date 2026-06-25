<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>
        body{
            font-family: DejaVu Sans;
            font-size:14px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table,th,td{
            border:1px solid #000;
            padding:8px;
        }

        h1{
            margin-bottom:5px;
        }
    </style>
</head>
<body>

<h1>{{ $setting->site_name }}</h1>

<p>
Invoice #: {{ $order->invoice_number }}
<br>
Order #: {{ $order->order_number }}
</p>

<hr>

<h3>Customer Details</h3>

<p>
{{ $order->customer_name }}<br>
{{ $order->customer_email }}<br>
{{ $order->customer_phone }}
</p>

<h3>Shipping Address</h3>

<p>
{{ $order->shipping_address }}<br>
{{ $order->city }},
{{ $order->state }}
{{ $order->pincode }}
</p>

<table>

<tr>
    <th>Item</th>
    <th>Amount</th>
</tr>

<tr>
    <td>
        @if($order->item_type === 'product')
            {{ optional($order->product)->name }}
        @else
            {{ optional($order->plan)->name }}
        @endif
    </td>

    <td>
        ₹{{ number_format($order->amount,2) }}
    </td>
</tr>

</table>

<p style="margin-top:30px;">
Thank you for your purchase.
</p>

</body>
</html>