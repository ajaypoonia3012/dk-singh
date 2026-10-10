<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CourierProvider;
use App\Models\Offer;
use App\Models\OfferRedemption;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Services\CommunicationService;
use App\Services\DelhiveryService;
use App\Services\OfferService;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class ProductPaymentController extends Controller
{
    public function checkout(Request $request, int $id, RazorpayService $razorpay, OfferService $offerService): View|RedirectResponse
    {
        $product = Product::query()->where('status', true)->findOrFail($id);
        $user = auth()->user();

        $availableOffers = $offerService->getValidOffersForProduct($product, $user);
        $appliedOffer = null;

        $requestedCode = $request->input('code') ?: $request->input('offer');

        if (filled($requestedCode)) {
            $validation = $offerService->findAndValidateOffer((string) $requestedCode, $product, $user);
            if ($validation['valid'] && isset($validation['offer'])) {
                $appliedOffer = $validation['offer'];
            }
        }

        if ($appliedOffer) {
            $pricing = $offerService->calculateDiscount($appliedOffer, $product->price);
        } else {
            $pricing = [
                'original_price' => (float) $product->price,
                'discount_amount' => 0.0,
                'final_amount' => (float) $product->price,
                'amount_in_paise' => (int) round((float) $product->price * 100),
                'discount_type' => null,
                'discount_value' => 0.0,
                'offer_name' => null,
                'promo_code' => null,
            ];
        }

        try {
            $razorpayOrder = $razorpay->createOrder('product', $product->id, (int) auth()->id(), $pricing['final_amount']);
        } catch (Throwable $exception) {
            report($exception);

            return redirect('/products')->with('error', 'Payment service is temporarily unavailable.');
        }

        $orderMetadata = [
            'order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
            'item_type' => 'product',
            'item_id' => $product->id,
            'user_id' => (int) auth()->id(),
            'offer_id' => $appliedOffer?->id,
            'promo_code' => $appliedOffer?->code,
            'subtotal' => $pricing['original_price'],
            'discount_amount' => $pricing['discount_amount'],
            'final_amount' => $pricing['final_amount'],
            'pricing_snapshot' => $pricing,
            'created_at' => now()->timestamp,
        ];

        session()->put("razorpay_orders.{$razorpayOrder['id']}", $orderMetadata);
        Cache::put("razorpay_order_{$razorpayOrder['id']}", $orderMetadata, now()->addHours(48));

        return view('checkout.product-payment', compact('product', 'razorpayOrder', 'availableOffers', 'appliedOffer', 'pricing'));
    }

    public function applyOffer(Request $request, int $id, RazorpayService $razorpay, OfferService $offerService): JsonResponse
    {
        $product = Product::query()->where('status', true)->findOrFail($id);
        $user = auth()->user();
        $code = trim((string) $request->input('code', ''));

        // If code is empty or special remove flag, revert to full price
        if ($code === '' || $code === '__remove__') {
            $pricing = [
                'original_price' => (float) $product->price,
                'discount_amount' => 0.0,
                'final_amount' => (float) $product->price,
                'amount_in_paise' => (int) round((float) $product->price * 100),
                'discount_type' => null,
                'discount_value' => 0.0,
                'offer_name' => null,
                'promo_code' => null,
            ];

            try {
                $razorpayOrder = $razorpay->createOrder('product', $product->id, (int) auth()->id(), $pricing['final_amount']);
            } catch (Throwable $exception) {
                report($exception);

                return response()->json([
                    'success' => false,
                    'message' => 'Unable to reinitialize payment order with gateway.',
                ], 500);
            }

            $orderMetadata = [
                'order_id' => $razorpayOrder['id'],
                'amount' => $razorpayOrder['amount'],
                'currency' => $razorpayOrder['currency'],
                'item_type' => 'product',
                'item_id' => $product->id,
                'user_id' => (int) auth()->id(),
                'offer_id' => null,
                'promo_code' => null,
                'subtotal' => $pricing['original_price'],
                'discount_amount' => 0.0,
                'final_amount' => $pricing['final_amount'],
                'pricing_snapshot' => $pricing,
                'created_at' => now()->timestamp,
            ];

            session()->put("razorpay_orders.{$razorpayOrder['id']}", $orderMetadata);
            Cache::put("razorpay_order_{$razorpayOrder['id']}", $orderMetadata, now()->addHours(48));

            return response()->json([
                'success' => true,
                'applied' => false,
                'message' => 'Offer removed. Checkout updated to standard price.',
                'pricing' => $pricing,
                'razorpay_order' => $razorpayOrder,
            ]);
        }

        $validation = $offerService->findAndValidateOffer($code, $product, $user);

        if (! $validation['valid']) {
            return response()->json([
                'success' => false,
                'message' => $validation['message'] ?? 'Invalid promo code.',
            ], 422);
        }

        /** @var Offer $offer */
        $offer = $validation['offer'];
        $pricing = $offerService->calculateDiscount($offer, $product->price);

        try {
            $razorpayOrder = $razorpay->createOrder('product', $product->id, (int) auth()->id(), $pricing['final_amount']);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Unable to create discounted order with payment gateway.',
            ], 500);
        }

        $orderMetadata = [
            'order_id' => $razorpayOrder['id'],
            'amount' => $razorpayOrder['amount'],
            'currency' => $razorpayOrder['currency'],
            'item_type' => 'product',
            'item_id' => $product->id,
            'user_id' => (int) auth()->id(),
            'offer_id' => $offer->id,
            'promo_code' => $offer->code,
            'subtotal' => $pricing['original_price'],
            'discount_amount' => $pricing['discount_amount'],
            'final_amount' => $pricing['final_amount'],
            'pricing_snapshot' => $pricing,
            'created_at' => now()->timestamp,
        ];

        session()->put("razorpay_orders.{$razorpayOrder['id']}", $orderMetadata);
        Cache::put("razorpay_order_{$razorpayOrder['id']}", $orderMetadata, now()->addHours(48));

        return response()->json([
            'success' => true,
            'applied' => true,
            'message' => sprintf("Promo code '%s' applied! You saved ₹%s.", $offer->code, number_format($pricing['discount_amount'], 2)),
            'pricing' => $pricing,
            'razorpay_order' => $razorpayOrder,
        ]);
    }

    public function success(Request $request, RazorpayService $razorpay, OfferService $offerService): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:2000'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:20'],
            'razorpay_order_id' => ['required', 'string', 'max:255'],
            'razorpay_payment_id' => ['required', 'string', 'max:255'],
            'razorpay_signature' => ['required', 'string', 'max:512'],
        ]);

        $expected = session("razorpay_orders.{$validated['razorpay_order_id']}");

        if (! is_array($expected)
            || $expected['item_type'] !== 'product'
            || (int) $expected['item_id'] !== (int) $validated['product_id']
            || (int) $expected['user_id'] !== (int) auth()->id()
            || (int) $expected['created_at'] < now()->subHour()->timestamp) {
            return redirect('/products')->with('error', 'Payment session is invalid or expired.');
        }

        try {
            $razorpay->verify($validated, $expected);
        } catch (RuntimeException $exception) {
            report($exception);

            return redirect('/products')->with('error', 'Payment verification failed.');
        }

        $lock = Cache::lock("razorpay_payment_processing_{$validated['razorpay_payment_id']}", 10);

        try {
            return $lock->block(5, function () use ($validated, $expected, $offerService): RedirectResponse {
                if (Order::query()->where('payment_id', $validated['razorpay_payment_id'])->exists()) {
                    session()->forget("razorpay_orders.{$validated['razorpay_order_id']}");
                    Cache::forget("razorpay_order_{$validated['razorpay_order_id']}");

                    return redirect('/products')->with('error', 'This payment has already been processed.');
                }

                $product = Product::query()->where('status', true)->findOrFail($validated['product_id']);

                $order = DB::transaction(function () use ($validated, $product, $expected, $offerService): Order {
                    $subtotal = (float) ($expected['subtotal'] ?? $product->price);
                    $discountAmount = (float) ($expected['discount_amount'] ?? 0.00);
                    $finalAmount = (float) ($expected['final_amount'] ?? $product->price);
                    $pricingSnapshot = $expected['pricing_snapshot'] ?? [
                        'original_price' => $subtotal,
                        'discount_amount' => $discountAmount,
                        'final_amount' => $finalAmount,
                        'recorded_at' => now()->toIso8601String(),
                    ];

                    $createdOrder = Order::query()->create([
                        'user_id' => auth()->id(),
                        'customer_name' => $validated['customer_name'],
                        'customer_email' => auth()->user()->email,
                        'customer_phone' => $validated['customer_phone'],
                        'item_type' => 'product',
                        'item_id' => $product->id,
                        'amount' => $finalAmount,
                        'subtotal' => $subtotal,
                        'discount_amount' => $discountAmount,
                        'offer_id' => $expected['offer_id'] ?? null,
                        'promo_code' => $expected['promo_code'] ?? null,
                        'pricing_snapshot' => $pricingSnapshot,
                        'payment_status' => 'paid',
                        'payment_gateway' => 'razorpay',
                        'payment_id' => $validated['razorpay_payment_id'],
                        'shipping_address' => $validated['shipping_address'],
                        'city' => $validated['city'],
                        'state' => $validated['state'],
                        'pincode' => $validated['pincode'],
                        'order_status' => 'pending',
                    ]);

                    if (! empty($expected['offer_id'])) {
                        $offer = Offer::query()->where('id', $expected['offer_id'])->lockForUpdate()->first();

                        if ($offer) {
                            $offerService->recordRedemption($offer, $createdOrder, auth()->user(), $discountAmount);
                        }
                    }

                    return $createdOrder;
                });

                session()->forget("razorpay_orders.{$validated['razorpay_order_id']}");
                Cache::forget("razorpay_order_{$validated['razorpay_order_id']}");
                $this->createShipment($order, $product);

                app(CommunicationService::class)->send('order_confirmed', auth()->user(), [
                    'name' => $order->customer_name,
                    'order_number' => $order->order_number,
                    'amount' => $order->amount,
                ]);

                return redirect('/products')->with('success', 'Product purchased successfully.');
            });
        } catch (Throwable $e) {
            report($e);

            return redirect('/products')->with('error', 'Unable to complete order processing. Please contact support.');
        }
    }

    private function createShipment(Order $order, Product $product): void
    {
        $provider = CourierProvider::query()->where('is_default', true)->where('is_active', true)->first();

        if (! $provider) {
            return;
        }

        $shipment = Shipment::query()->create([
            'order_id' => $order->id,
            'courier_provider_id' => $provider->id,
            'shipment_status' => 'created',
            'pickup_status' => 'pending',
        ]);

        $response = app(DelhiveryService::class)->sendToDelhivery($order, $product);

        if (($response['success'] ?? false) && ! empty($response['packages'][0]['waybill'])) {
            $awb = $response['packages'][0]['waybill'];
            $shipment->update([
                'awb_number' => $awb,
                'tracking_number' => $awb,
                'tracking_url' => 'https://www.delhivery.com/track/package/'.$awb,
                'shipment_status' => 'packed',
            ]);
        }

        ShipmentEvent::query()->create([
            'shipment_id' => $shipment->id,
            'event_type' => 'created',
            'message' => 'Order created and awaiting packing',
            'event_time' => now(),
        ]);
    }
}
