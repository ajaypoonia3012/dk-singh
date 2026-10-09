
@extends('layouts.app')

@section('content')

<section class="theme-section theme-surface-muted min-h-screen">

    <div class="theme-page-container">

        <div class="grid lg:grid-cols-2 gap-14">

            {{-- LEFT --}}
            <div>

                @if(!empty($selectedProgram))
                    <div class="mb-6 p-4 theme-radius theme-card border theme-border flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider theme-text-primary">Target Curriculum</span>
                            <p class="text-xl font-bold theme-text-secondary">{{ $selectedProgram->title }} ({{ $selectedProgram->duration }})</p>
                        </div>
                        <span class="text-xs uppercase font-semibold theme-text-neutral">Enrolling With</span>
                    </div>
                @endif

                <p class="uppercase tracking-[3px] theme-text-primary font-bold mb-4">
                    Membership Checkout
                </p>

                <h1 class="text-5xl font-black theme-text-secondary mb-6">

                    {{ $plan->name }}

                </h1>

                <p class="theme-text-neutral text-lg leading-8 mb-10">

                    {{ $plan->description }}

                </p>

                {{-- PRICE --}}
                <div class="flex items-end gap-4 mb-12">

                    @if($plan->discount_price)

                        <span class="text-6xl font-black theme-text-primary">

                            &#8377;{{ number_format($plan->discount_price) }}

                        </span>

                        <span class="line-through text-2xl theme-text-neutral">

                            &#8377;{{ number_format($plan->price) }}

                        </span>

                    @else

                        <span class="text-6xl font-black theme-text-primary">

                            &#8377;{{ number_format($plan->price) }}

                        </span>

                    @endif

                    <span class="mb-2 text-lg theme-text-neutral">

                        /{{ strtolower($plan->billing_cycle) }}

                    </span>

                </div>

                {{-- FEATURES --}}
                <div class="space-y-5">

                    @foreach($plan->features as $feature)

                        <div class="flex items-start gap-4">

                            <div class="theme-text-primary text-xl">
                                <svg class="inline-block w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" /></svg>
                            </div>

                            <span class="theme-text-neutral text-lg">

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
            <div class="theme-card theme-radius theme-shadow theme-card-padding-lg border theme-border">

                <h2 class="text-3xl font-black mb-8">
                    Complete Purchase
                </h2>

                @guest

                    <div class="space-y-5">

                        <a href="/login"
                           class="theme-button theme-button-secondary w-full text-center">

                            Login to Continue

                        </a>

                        <a href="/register"
                           class="w-full border-2 theme-border py-5 theme-radius font-bold text-center block hover:theme-surface-strong hover:theme-text-on-strong transition">

                            Create Account

                        </a>

                    </div>

                @else

                    {{-- RAZORPAY BUTTON --}}
                    <button id="rzp-button"
                        class="w-full theme-status-warning hover:theme-status-warning theme-text-secondary py-5 theme-radius font-black text-lg transition">

                        Pay &#8377;{{ number_format($plan->discount_price ?? $plan->price) }}

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

        "key": "{{ config('services.razorpay.key') }}",

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
        },
    };

    var rzp1 = new Razorpay(options);

    document.getElementById('rzp-button').onclick = function(e) {

        rzp1.open();

        e.preventDefault();

    }

</script>

@endsection
