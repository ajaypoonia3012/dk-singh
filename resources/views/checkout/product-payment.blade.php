@extends('layouts.app')

@section('content')

<section class="theme-section theme-surface-muted min-h-screen">

<div class="max-w-3xl mx-auto">

<div class="theme-card theme-radius theme-shadow theme-card-padding-lg">

<p class="text-xs uppercase tracking-widest theme-text-primary font-bold mb-2">Product Payment</p>
<h1 class="text-4xl font-black mb-6">
Product Checkout
</h1>

<h2 class="text-2xl font-bold mb-4">
{{ $product->name }}
</h2>

<div class="text-4xl font-black theme-text-primary mb-8">
&#8377;{{ number_format($product->price) }}
</div>


<div class="theme-stack-md mb-8">

<input
type="text"
id="customer_name"
placeholder="Full Name"
value="{{ auth()->user()->name ?? '' }}"
class="theme-form-control w-full border theme-radius p-3"
required
>

<input
type="text"
id="customer_phone"
placeholder="Phone Number"
value="{{ auth()->user()->phone ?? '' }}"
class="theme-form-control w-full border theme-radius p-3"
required
>

<textarea
id="shipping_address"
placeholder="Full Address"
class="theme-form-control w-full border theme-radius p-3"
required
></textarea>

<input
type="text"
id="city"
placeholder="City"
class="theme-form-control w-full border theme-radius p-3"
required
>

<input
type="text"
id="state"
placeholder="State"
class="theme-form-control w-full border theme-radius p-3"
required
>

<input
type="text"
id="pincode"
placeholder="Pincode"
class="theme-form-control w-full border theme-radius p-3"
required
>

</div>

<button
id="rzp-button"
class="theme-status-warning px-8 py-4 theme-radius font-bold"
>
Pay &#8377;{{ number_format($product->price) }}
</button>

<form
id="payment-form"
action="/product-payment-success"
method="POST"
class="hidden"
>

@csrf

<input
type="hidden"
name="product_id"
value="{{ $product->id }}"
>

<input
type="hidden"
name="customer_name"
id="hidden_customer_name"
>

<input
type="hidden"
name="customer_phone"
id="hidden_customer_phone"
>

<input
type="hidden"
name="shipping_address"
id="hidden_shipping_address"
>

<input
type="hidden"
name="city"
id="hidden_city"
>

<input
type="hidden"
name="state"
id="hidden_state"
>

<input
type="hidden"
name="pincode"
id="hidden_pincode"
>


<input
type="hidden"
name="razorpay_payment_id"
id="razorpay_payment_id"
>

<input
type="hidden"
name="razorpay_order_id"
id="razorpay_order_id"
>

<input
type="hidden"
name="razorpay_signature"
id="razorpay_signature"
>


</form>

</div>

</div>

</section>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

var options = {

key: "{{ config('services.razorpay.key') }}",

amount: "{{ $product->price * 100 }}",

currency: "INR",

name: "{{ $setting->site_name }}",

description: "{{ $product->name }}",

handler: function(response) {

document.getElementById('razorpay_payment_id').value =
response.razorpay_payment_id;

document.getElementById('razorpay_order_id').value =
response.razorpay_order_id;

document.getElementById('razorpay_signature').value =
response.razorpay_signature;

document.getElementById('hidden_customer_name').value =
document.getElementById('customer_name').value;

document.getElementById('hidden_customer_phone').value =
document.getElementById('customer_phone').value;

document.getElementById('hidden_shipping_address').value =
document.getElementById('shipping_address').value;

document.getElementById('hidden_city').value =
document.getElementById('city').value;

document.getElementById('hidden_state').value =
document.getElementById('state').value;

document.getElementById('hidden_pincode').value =
document.getElementById('pincode').value;

document.getElementById('payment-form').submit();

}
};

var rzp1 = new Razorpay(options);

document.getElementById('rzp-button').onclick = function(e) {

rzp1.open();

e.preventDefault();

}

</script>

@endsection
