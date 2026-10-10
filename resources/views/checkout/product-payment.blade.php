@extends('layouts.app')

@section('content')

<section class="theme-section theme-surface-muted min-h-screen py-12">

<div class="max-w-4xl mx-auto px-4">

<div class="theme-card theme-radius theme-shadow p-6 sm:p-10 border theme-border">

    <div class="border-b theme-border pb-6 mb-8">
        <p class="text-xs uppercase tracking-widest theme-text-primary font-bold mb-2">Product Payment</p>
        <h1 class="text-3xl sm:text-4xl font-black theme-text-secondary">
            Product Checkout
        </h1>
    </div>

    <div class="grid md:grid-cols-12 gap-8 items-start">

        <!-- Left: Customer Information & Delivery Details -->
        <div class="md:col-span-7 space-y-6">
            <div class="theme-card p-5 theme-radius border theme-border theme-surface-muted">
                <div class="flex items-center gap-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover theme-radius border theme-border flex-shrink-0">
                    @endif
                    <div>
                        <h2 class="text-xl font-bold theme-text-secondary leading-tight">{{ $product->name }}</h2>
                        <p class="text-xs theme-text-neutral mt-1">Authentic wellness & fitness nutrition</p>
                        <div class="text-lg font-black theme-text-primary mt-2">
                            &#8377;{{ number_format($product->price, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold theme-text-secondary mb-4 flex items-center gap-2">
                    <span>📦 Delivery Information</span>
                </h3>

                <div class="space-y-4">
                    <div>
                        <label for="customer_name" class="block text-xs font-bold uppercase tracking-wider theme-text-neutral mb-1">Full Name *</label>
                        <input
                            type="text"
                            id="customer_name"
                            placeholder="Full Name"
                            value="{{ auth()->user()->name ?? '' }}"
                            class="theme-form-control w-full border theme-radius p-3 text-sm"
                            required
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_phone" class="block text-xs font-bold uppercase tracking-wider theme-text-neutral mb-1">Phone Number *</label>
                            <input
                                type="text"
                                id="customer_phone"
                                placeholder="10-digit mobile number"
                                value="{{ auth()->user()->phone ?? '' }}"
                                class="theme-form-control w-full border theme-radius p-3 text-sm"
                                required
                            >
                        </div>
                        <div>
                            <label for="customer_email" class="block text-xs font-bold uppercase tracking-wider theme-text-neutral mb-1">Email Address</label>
                            <input
                                type="email"
                                id="customer_email"
                                value="{{ auth()->user()->email ?? '' }}"
                                class="theme-form-control w-full border theme-radius p-3 text-sm opacity-80"
                                readonly
                            >
                        </div>
                    </div>

                    <div>
                        <label for="shipping_address" class="block text-xs font-bold uppercase tracking-wider theme-text-neutral mb-1">Shipping Address *</label>
                        <textarea
                            id="shipping_address"
                            placeholder="House / Flat No., Street, Landmark"
                            rows="3"
                            class="theme-form-control w-full border theme-radius p-3 text-sm"
                            required
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="city" class="block text-xs font-bold uppercase tracking-wider theme-text-neutral mb-1">City *</label>
                            <input
                                type="text"
                                id="city"
                                placeholder="City"
                                class="theme-form-control w-full border theme-radius p-3 text-sm"
                                required
                            >
                        </div>
                        <div>
                            <label for="state" class="block text-xs font-bold uppercase tracking-wider theme-text-neutral mb-1">State *</label>
                            <input
                                type="text"
                                id="state"
                                placeholder="State"
                                class="theme-form-control w-full border theme-radius p-3 text-sm"
                                required
                            >
                        </div>
                        <div>
                            <label for="pincode" class="block text-xs font-bold uppercase tracking-wider theme-text-neutral mb-1">Pincode *</label>
                            <input
                                type="text"
                                id="pincode"
                                placeholder="PIN Code"
                                class="theme-form-control w-full border theme-radius p-3 text-sm"
                                required
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Offers Selection & Price Summary -->
        <div class="md:col-span-5 space-y-6">

            <!-- Offers & Promo Box -->
            <div class="theme-card p-5 theme-radius border theme-border">
                <h3 class="text-sm font-bold uppercase tracking-wider theme-text-secondary mb-3 flex items-center gap-1.5">
                    <span>🏷️ Offers & Discounts</span>
                </h3>

                <!-- Manual Promo Code Entry -->
                <div class="flex gap-2 mb-4">
                    <input
                        type="text"
                        id="promo-input"
                        placeholder="Enter Promo Code"
                        value="{{ $appliedOffer?->code ?? '' }}"
                        class="theme-form-control flex-1 border theme-radius px-3 py-2 text-sm uppercase font-mono tracking-wider"
                    >
                    <button
                        type="button"
                        id="btn-apply-promo"
                        class="theme-button-primary px-4 py-2 theme-radius text-sm font-bold transition hover:opacity-90"
                    >
                        Apply
                    </button>
                </div>

                <!-- Alert Message -->
                <div id="promo-feedback" class="hidden text-xs p-3 theme-radius mb-3"></div>

                <!-- Applied Promo State -->
                <div id="applied-promo-box" class="{{ $appliedOffer ? '' : 'hidden' }} flex items-center justify-between p-3 theme-radius theme-surface-muted border theme-border text-xs mb-3">
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold uppercase font-mono theme-text-primary" id="applied-code-badge">{{ $appliedOffer?->code ?? '' }}</span>
                        <span class="text-[11px] opacity-80 theme-text-neutral">(Offer Applied)</span>
                    </div>
                    <button type="button" id="btn-remove-promo" class="text-xs font-bold theme-text-primary hover:underline">
                        Remove
                    </button>
                </div>

                <!-- Available Offers List -->
                @if($availableOffers->isNotEmpty())
                    <div class="mt-4 pt-3 border-t theme-border">
                        <p class="text-xs font-bold theme-text-neutral mb-2">Available Offers for you:</p>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            @foreach($availableOffers as $offer)
                                <div class="p-2.5 theme-radius border theme-border theme-surface-muted flex items-center justify-between gap-2 text-xs">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono font-bold theme-text-primary uppercase">{{ $offer->code }}</span>
                                            <span class="text-[10px] px-1.5 py-0.5 theme-radius theme-card border theme-border theme-text-accent font-extrabold">
                                                @if($offer->discount_type === 'percentage')
                                                    {{ rtrim(rtrim((string) $offer->discount_value, '0'), '.') }}% OFF
                                                @else
                                                    &#8377;{{ number_format($offer->discount_value) }} OFF
                                                @endif
                                            </span>
                                        </div>
                                        <p class="text-[11px] theme-text-neutral mt-0.5 line-clamp-1">{{ $offer->name }}</p>
                                    </div>
                                    <button
                                        type="button"
                                        onclick="applyOfferCode('{{ $offer->code }}')"
                                        class="px-2.5 py-1 theme-radius theme-card border theme-border text-xs font-bold flex-shrink-0"
                                    >
                                        Apply
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Price Breakdown Summary -->
            <div class="theme-card p-5 theme-radius border theme-border space-y-3">
                <h3 class="text-sm font-bold uppercase tracking-wider theme-text-secondary mb-2">
                    Order Price Summary
                </h3>

                <div class="flex justify-between text-sm theme-text-neutral">
                    <span>Original Price</span>
                    <span>&#8377;<span id="display-subtotal">{{ number_format($pricing['original_price'], 2) }}</span></span>
                </div>

                <div id="row-discount" class="{{ $pricing['discount_amount'] > 0 ? '' : 'hidden' }} flex justify-between text-sm theme-text-accent font-semibold">
                    <span>Discount (<span id="display-promo-code">{{ $appliedOffer?->code ?? '' }}</span>)</span>
                    <span>-&#8377;<span id="display-discount">{{ number_format($pricing['discount_amount'], 2) }}</span></span>
                </div>

                <div class="flex justify-between text-sm theme-text-neutral">
                    <span>Shipping & Handling</span>
                    <span class="theme-text-accent font-bold uppercase text-xs">Free</span>
                </div>

                <div class="border-t theme-border pt-3 flex justify-between items-baseline text-lg font-black theme-text-secondary">
                    <span>Total Payable</span>
                    <span class="text-2xl theme-text-primary">
                        &#8377;<span id="display-final-amount">{{ number_format($pricing['final_amount'], 2) }}</span>
                    </span>
                </div>

                <button
                    type="button"
                    id="rzp-button"
                    class="w-full theme-status-warning py-4 theme-radius font-black text-center theme-shadow transition transform hover:-translate-y-0.5 mt-4"
                >Pay &#8377;{{ number_format($pricing['final_amount']) }}</button>
                <p class="text-[11px] text-center theme-text-neutral">🔒 256-bit encrypted Razorpay transaction</p>
            </div>

        </div>

    </div>

</div>

</div>

</section>

<!-- Hidden Form for Payment Callback Submission -->
<form
    id="payment-form"
    action="{{ route('product.payment.success') }}"
    method="POST"
    class="hidden"
>
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="customer_name" id="hidden_customer_name">
    <input type="hidden" name="customer_phone" id="hidden_customer_phone">
    <input type="hidden" name="shipping_address" id="hidden_shipping_address">
    <input type="hidden" name="city" id="hidden_city">
    <input type="hidden" name="state" id="hidden_state">
    <input type="hidden" name="pincode" id="hidden_pincode">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
    <input type="hidden" name="razorpay_signature" id="razorpay_signature">
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
let currentOrderId = "{{ $razorpayOrder['id'] }}";
let currentAmountPaise = {{ $pricing['amount_in_paise'] }};
const productId = {{ $product->id }};
const csrfToken = "{{ csrf_token() }}";
const applyOfferUrl = "{{ route('product.checkout.apply-offer', $product->id) }}";

function showFeedback(message, isSuccess) {
    const feedback = document.getElementById('promo-feedback');
    feedback.textContent = message;
    feedback.className = isSuccess
        ? 'text-xs p-3 theme-radius mb-3 theme-surface-muted border theme-border theme-text-accent block'
        : 'text-xs p-3 theme-radius mb-3 theme-surface-muted border theme-border theme-text-primary block';
}

function updatePricingDisplay(pricing, appliedCode) {
    document.getElementById('display-subtotal').textContent = Number(pricing.original_price).toFixed(2);
    document.getElementById('display-discount').textContent = Number(pricing.discount_amount).toFixed(2);
    document.getElementById('display-final-amount').textContent = Number(pricing.final_amount).toFixed(2);
    document.getElementById('rzp-button').innerHTML = 'Pay &#8377;' + Math.round(Number(pricing.final_amount)).toLocaleString('en-IN');

    const discountRow = document.getElementById('row-discount');
    const appliedPromoBox = document.getElementById('applied-promo-box');
    const promoBadge = document.getElementById('applied-code-badge');
    const promoCodeLabel = document.getElementById('display-promo-code');
    const promoInput = document.getElementById('promo-input');

    if (pricing.discount_amount > 0 && appliedCode) {
        discountRow.classList.remove('hidden');
        appliedPromoBox.classList.remove('hidden');
        promoBadge.textContent = appliedCode;
        promoCodeLabel.textContent = appliedCode;
        promoInput.value = appliedCode;
    } else {
        discountRow.classList.add('hidden');
        appliedPromoBox.classList.add('hidden');
        promoInput.value = '';
    }
}

async function applyOfferCode(code) {
    const btn = document.getElementById('btn-apply-promo');
    btn.disabled = true;
    btn.textContent = '...';

    try {
        const response = await fetch(applyOfferUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code: code })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            currentOrderId = data.razorpay_order.id;
            currentAmountPaise = data.pricing.amount_in_paise;
            updatePricingDisplay(data.pricing, data.pricing.promo_code);
            showFeedback(data.message, true);
        } else {
            showFeedback(data.message || 'Unable to apply offer.', false);
        }
    } catch (e) {
        showFeedback('A network error occurred while applying the offer.', false);
    } finally {
        btn.disabled = false;
        btn.textContent = 'Apply';
    }
}

document.getElementById('btn-apply-promo').addEventListener('click', function() {
    const code = document.getElementById('promo-input').value.trim();
    if (!code) {
        showFeedback('Please enter a promo code.', false);
        return;
    }
    applyOfferCode(code);
});

document.getElementById('btn-remove-promo').addEventListener('click', function() {
    applyOfferCode('__remove__');
});

document.getElementById('rzp-button').onclick = function(e) {
    e.preventDefault();

    const name = document.getElementById('customer_name').value.trim();
    const phone = document.getElementById('customer_phone').value.trim();
    const address = document.getElementById('shipping_address').value.trim();
    const city = document.getElementById('city').value.trim();
    const state = document.getElementById('state').value.trim();
    const pincode = document.getElementById('pincode').value.trim();

    if (!name || !phone || !address || !city || !state || !pincode) {
        alert('Please complete all required delivery details before proceeding.');
        return;
    }

    const options = {
        key: "{{ config('services.razorpay.key') }}",
        order_id: currentOrderId,
        amount: currentAmountPaise,
        currency: "INR",
        name: "{{ $setting->site_name ?? 'DK Singh Fitness' }}",
        description: "{{ $product->name }}",
        prefill: {
            name: name,
            contact: phone,
            email: "{{ auth()->user()->email ?? '' }}"
        },
        handler: function(response) {
            document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
            document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
            document.getElementById('razorpay_signature').value = response.razorpay_signature;

            document.getElementById('hidden_customer_name').value = name;
            document.getElementById('hidden_customer_phone').value = phone;
            document.getElementById('hidden_shipping_address').value = address;
            document.getElementById('hidden_city').value = city;
            document.getElementById('hidden_state').value = state;
            document.getElementById('hidden_pincode').value = pincode;

            document.getElementById('payment-form').submit();
        }
    };

    const rzp = new Razorpay(options);
    rzp.open();
};
</script>

@endsection
