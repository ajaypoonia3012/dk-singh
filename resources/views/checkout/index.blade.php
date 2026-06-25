
@extends('layouts.app')

@section('content')

<section class="py-24 bg-[#f6f3eb] min-h-screen">

    <div class="max-w-5xl mx-auto px-6">

        <div class="grid lg:grid-cols-2 gap-14">

            {{-- LEFT --}}
            <div>

                <p class="uppercase tracking-[3px] text-yellow-500 font-bold mb-4">
                    Membership Checkout
                </p>

                <h1 class="text-5xl font-black text-black mb-6">

                    {{ $plan->name }}

                </h1>

                <p class="text-gray-600 text-lg leading-8 mb-10">

                    {{ $plan->description }}

                </p>

                {{-- PRICE --}}
                <div class="flex items-end gap-4 mb-12">

                    @if($plan->discount_price)

                        <span class="text-6xl font-black text-yellow-500">

                            ₹{{ number_format($plan->discount_price) }}

                        </span>

                        <span class="line-through text-2xl text-gray-400">

                            ₹{{ number_format($plan->price) }}

                        </span>

                    @else

                        <span class="text-6xl font-black text-yellow-500">

                            ₹{{ number_format($plan->price) }}

                        </span>

                    @endif

                    <span class="mb-2 text-lg text-gray-500">

                        /{{ strtolower($plan->billing_cycle) }}

                    </span>

                </div>

                {{-- FEATURES --}}
                <div class="space-y-5">

                    @foreach($plan->features as $feature)

                        <div class="flex items-start gap-4">

                            <div class="text-yellow-500 text-xl">
                                ✔
                            </div>

                            <span class="text-gray-700 text-lg">

                                {{ is_array($feature)
                                    ? $feature['feature']
                                    : $feature
                                }}

                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="bg-white rounded-[36px] shadow-xl p-10 border border-gray-200">

                <h2 class="text-3xl font-black mb-8">
                    Complete Purchase
                </h2>

                @guest

                    <div class="space-y-5">

                        <a href="/login"
                           class="w-full bg-black text-white py-5 rounded-2xl font-bold text-center block hover:bg-gray-800 transition">

                            Login to Continue

                        </a>

                        <a href="/register"
                           class="w-full border-2 border-black py-5 rounded-2xl font-bold text-center block hover:bg-black hover:text-white transition">

                            Create Account

                        </a>

                    </div>

                @else

                    {{-- RAZORPAY BUTTON --}}
                    <button id="rzp-button"
                        class="w-full bg-yellow-500 hover:bg-yellow-400 text-black py-5 rounded-2xl font-black text-lg transition">

                        Pay ₹{{ number_format($plan->discount_price ?? $plan->price) }}

                    </button>

                    {{-- HIDDEN PAYMENT FORM --}}
                    <form
                        id="payment-form"
                        action="/payment-success"
                        method="POST"
                        class="hidden">

                        @csrf

                        <input type="hidden"
                               name="plan_id"
                               value="{{ $plan->id }}">

                        <input type="hidden"
                               name="razorpay_payment_id"
                               id="razorpay_payment_id">

                        <input type="hidden"
                               name="razorpay_order_id"
                               id="razorpay_order_id">

                        <input type="hidden"
                               name="razorpay_signature"
                               id="razorpay_signature">

                    </form>

                @endguest

            </div>

        </div>

    </div>

</section>

{{-- RAZORPAY SCRIPT --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

    var options = {

        "key": "{{ env('RAZORPAY_KEY') }}",

        "amount": "{{ ($plan->discount_price ?? $plan->price) * 100 }}",

        "currency": "INR",

        "name": "{{ $setting->site_name }}",

        "description": "{{ $plan->name }}",

        "image": "/logo.png",

        handler: function (response) {

    document.getElementById('razorpay_payment_id').value =
        response.razorpay_payment_id;

    document.getElementById('razorpay_order_id').value =
        response.razorpay_order_id;

    document.getElementById('razorpay_signature').value =
        response.razorpay_signature;

    document.getElementById('payment-form').submit();

    setTimeout(function () {

        window.location.href = "/member/dashboard";

    }, 2000);

},
    };

    var rzp1 = new Razorpay(options);

    document.getElementById('rzp-button').onclick = function(e) {

        rzp1.open();

        e.preventDefault();

    }

</script>

@endsection