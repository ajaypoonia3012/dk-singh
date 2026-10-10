<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CourierProvider;
use App\Models\Membership;
use App\Models\Offer;
use App\Models\OfferRedemption;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Models\User;
use App\Services\CommunicationService;
use App\Services\DelhiveryService;
use App\Services\OfferService;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request, RazorpayService $razorpay, OfferService $offerService): JsonResponse
    {
        $signature = (string) $request->header('X-Razorpay-Signature', '');

        if ($signature === '') {
            Log::warning('Razorpay webhook received without X-Razorpay-Signature header.');

            return response()->json([
                'status' => 'error',
                'message' => 'Signature missing',
            ], 400);
        }

        $rawPayload = $request->getContent();

        try {
            $razorpay->verifyWebhookSignature($rawPayload, $signature);
        } catch (Throwable $e) {
            Log::warning('Razorpay webhook signature verification failed: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature',
            ], 400);
        }

        $data = json_decode($rawPayload, true);

        if (! is_array($data) || empty($data['event'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Malformed payload',
            ], 400);
        }

        $event = (string) $data['event'];

        // Handle payment failure event safely (audit log without creating paid orders)
        if ($event === 'payment.failed') {
            $payment = $data['payload']['payment']['entity'] ?? [];
            $paymentId = (string) ($payment['id'] ?? 'unknown');
            $errorDesc = (string) ($payment['error_description'] ?? 'Payment failed on gateway');

            Log::info("Razorpay payment failed event received for payment {$paymentId}: {$errorDesc}");

            return response()->json([
                'status' => 'acknowledged',
                'message' => 'Payment failure logged',
                'payment_id' => $paymentId,
            ], 200);
        }

        // Only process confirmed capture events
        if (! in_array($event, ['payment.captured', 'order.paid'], true)) {
            return response()->json([
                'status' => 'ignored',
                'message' => "Event '{$event}' not handled",
            ], 200);
        }

        $payment = $data['payload']['payment']['entity'] ?? null;

        if (! is_array($payment) || ($payment['status'] ?? '') !== 'captured') {
            return response()->json([
                'status' => 'ignored',
                'message' => 'Payment entity missing or not in captured status',
            ], 200);
        }

        $paymentId = (string) ($payment['id'] ?? '');

        if ($paymentId === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment ID missing',
            ], 400);
        }

        $currency = (string) ($payment['currency'] ?? 'INR');
        if ($currency !== 'INR') {
            Log::warning("Razorpay webhook payment {$paymentId} received with unsupported currency {$currency}.");

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid currency; only INR is supported',
            ], 400);
        }

        $amountInPaise = (int) ($payment['amount'] ?? 0);
        if ($amountInPaise < 100) {
            Log::warning("Razorpay webhook payment {$paymentId} received with invalid amount {$amountInPaise} paise.");

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid payment amount',
            ], 400);
        }

        // Idempotency: verify if payment is already recorded in orders
        if (Order::query()->where('payment_id', $paymentId)->exists()) {
            return response()->json([
                'status' => 'already_processed',
                'message' => 'Payment has already been processed and recorded.',
                'payment_id' => $paymentId,
            ], 200);
        }

        // Concurrency lock to prevent race conditions between webhook and browser callbacks
        $lockKey = "razorpay_payment_processing_{$paymentId}";
        $lock = Cache::lock($lockKey, 10);

        try {
            return $lock->block(5, function () use ($payment, $paymentId, $amountInPaise, $offerService): JsonResponse {
                // Re-check idempotency inside lock
                if (Order::query()->where('payment_id', $paymentId)->exists()) {
                    return response()->json([
                        'status' => 'already_processed',
                        'message' => 'Payment has already been processed.',
                        'payment_id' => $paymentId,
                    ], 200);
                }

                $razorpayOrderId = $payment['order_id'] ?? null;
                $trustedOrder = $razorpayOrderId ? Cache::get("razorpay_order_{$razorpayOrderId}") : null;

                if (! $trustedOrder && $razorpayOrderId) {
                    $fetchedOrder = app(RazorpayService::class)->fetchOrder($razorpayOrderId);
                    if ($fetchedOrder && (int) $fetchedOrder['amount'] === (int) $amountInPaise && $fetchedOrder['currency'] === 'INR') {
                        $orderNotes = $fetchedOrder['notes'] ?? [];
                        $trustedOrder = [
                            'order_id' => $fetchedOrder['id'],
                            'amount' => (int) $fetchedOrder['amount'],
                            'currency' => $fetchedOrder['currency'],
                            'item_type' => $orderNotes['item_type'] ?? ($payment['notes']['item_type'] ?? null),
                            'item_id' => isset($orderNotes['item_id']) ? (int) $orderNotes['item_id'] : (isset($payment['notes']['item_id']) ? (int) $payment['notes']['item_id'] : null),
                            'user_id' => isset($orderNotes['user_id']) ? (int) $orderNotes['user_id'] : (isset($payment['notes']['user_id']) ? (int) $payment['notes']['user_id'] : null),
                            'offer_id' => ! empty($orderNotes['offer_id']) ? (int) $orderNotes['offer_id'] : (! empty($payment['notes']['offer_id']) ? (int) $payment['notes']['offer_id'] : null),
                            'promo_code' => ! empty($orderNotes['promo_code']) ? (string) $orderNotes['promo_code'] : (! empty($payment['notes']['promo_code']) ? (string) $payment['notes']['promo_code'] : null),
                            'subtotal' => ! empty($orderNotes['original_price']) ? (float) $orderNotes['original_price'] : round($amountInPaise / 100, 2),
                            'discount_amount' => ! empty($orderNotes['discount_amount']) ? (float) $orderNotes['discount_amount'] : 0.0,
                            'final_amount' => round($amountInPaise / 100, 2),
                            'pricing_snapshot' => [
                                'original_price' => ! empty($orderNotes['original_price']) ? (float) $orderNotes['original_price'] : round($amountInPaise / 100, 2),
                                'discount_amount' => ! empty($orderNotes['discount_amount']) ? (float) $orderNotes['discount_amount'] : 0.0,
                                'final_amount' => round($amountInPaise / 100, 2),
                                'reconciled_via' => 'razorpay_api_order_fetch',
                                'recorded_at' => now()->toIso8601String(),
                            ],
                        ];
                    }
                }

                if (! $trustedOrder) {
                    Log::warning("Razorpay webhook payment {$paymentId} rejected: trusted checkout order could not be established.", [
                        'order_id' => $razorpayOrderId,
                        'payment' => $paymentId,
                    ]);

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Trusted checkout record not found or cannot be verified',
                    ], 422);
                }

                $notes = $payment['notes'] ?? [];
                $itemType = $trustedOrder['item_type'] ?? ($notes['item_type'] ?? null);
                $itemId = isset($trustedOrder['item_id'])
                    ? (int) $trustedOrder['item_id']
                    : (isset($notes['item_id']) ? (int) $notes['item_id'] : null);
                $userId = isset($trustedOrder['user_id'])
                    ? (int) $trustedOrder['user_id']
                    : (isset($notes['user_id']) ? (int) $notes['user_id'] : null);

                $user = $userId ? User::find($userId) : null;
                $finalAmount = round($amountInPaise / 100, 2);

                if ($itemType === 'product') {
                    return $this->reconcileProductPayment($payment, $paymentId, $itemId, $user, $finalAmount, $offerService, $trustedOrder);
                }

                if ($itemType === 'plan') {
                    return $this->reconcilePlanPayment($payment, $paymentId, $itemId, $user, $finalAmount, $trustedOrder);
                }

                Log::warning("Razorpay webhook captured payment {$paymentId} without recognized item_type in notes.", [
                    'notes' => $notes,
                ]);

                return response()->json([
                    'status' => 'unrecognized_item',
                    'message' => 'Payment captured but item_type is unrecognized in notes',
                    'payment_id' => $paymentId,
                ], 200);
            });
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'status' => 'error',
                'message' => 'Exception occurred during webhook reconciliation: '.$e->getMessage(),
            ], 500);
        }
    }

    private function reconcileProductPayment(
        array $payment,
        string $paymentId,
        ?int $itemId,
        ?User $user,
        float $finalAmount,
        OfferService $offerService,
        array $trustedOrder
    ): JsonResponse {
        $product = $itemId ? Product::find($itemId) : null;

        if (! $product) {
            Log::warning("Razorpay webhook payment {$paymentId} referenced non-existent product ID: {$itemId}");

            return response()->json([
                'status' => 'error',
                'message' => 'Product not found for payment',
            ], 422);
        }

        $notes = $payment['notes'] ?? [];

        $customerEmail = $user?->email ?? ($payment['email'] ?? 'customer@dksinghfitness.com');
        $customerName = $user?->name ?? 'Customer';
        $customerPhone = $user?->phone ?? ($payment['contact'] ?? '');

        // Determine offer and expected pricing strictly from trusted checkout
        $offerId = $trustedOrder['offer_id'] ?? ($notes['offer_id'] ?? null);
        $promoCode = $trustedOrder['promo_code'] ?? ($notes['promo_code'] ?? null);

        $offer = null;
        if (! empty($offerId)) {
            $offer = Offer::find((int) $offerId);
        } elseif (! empty($promoCode)) {
            $offer = Offer::where('code', mb_strtoupper(trim($promoCode)))->first();
        }

        $expectedAmount = (float) $trustedOrder['final_amount'];
        $subtotal = (float) ($trustedOrder['subtotal'] ?? $product->price);
        $discountAmount = (float) ($trustedOrder['discount_amount'] ?? max(0.0, round($subtotal - $expectedAmount, 2)));
        $pricingSnapshot = $trustedOrder['pricing_snapshot'] ?? [
            'original_price' => $subtotal,
            'discount_amount' => $discountAmount,
            'final_amount' => $expectedAmount,
            'reconciled_via' => 'razorpay_webhook_trusted_order',
            'recorded_at' => now()->toIso8601String(),
        ];

        if (abs($finalAmount - $expectedAmount) > 0.05) {
            Log::warning("Razorpay webhook payment {$paymentId} amount ₹{$finalAmount} does not match expected amount ₹{$expectedAmount} for product {$product->id}.");

            return response()->json([
                'status' => 'error',
                'message' => 'Payment amount does not match expected product pricing',
            ], 422);
        }

        $order = DB::transaction(function () use (
            $user,
            $customerName,
            $customerEmail,
            $customerPhone,
            $itemId,
            $finalAmount,
            $subtotal,
            $discountAmount,
            $offer,
            $notes,
            $paymentId,
            $pricingSnapshot,
            $offerService
        ): Order {
            $order = Order::query()->create([
                'user_id' => $user?->id,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'item_type' => 'product',
                'item_id' => $itemId,
                'amount' => $finalAmount,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'offer_id' => $offer?->id,
                'promo_code' => $offer?->code ?? ($notes['promo_code'] ?? null),
                'pricing_snapshot' => $pricingSnapshot,
                'payment_status' => 'paid',
                'payment_gateway' => 'razorpay',
                'payment_id' => $paymentId,
                'shipping_address' => $notes['shipping_address'] ?? 'Pending customer address confirmation',
                'city' => $notes['city'] ?? null,
                'state' => $notes['state'] ?? null,
                'pincode' => $notes['pincode'] ?? null,
                'order_status' => 'pending',
            ]);

            // Offer redemption consistency with row lock and idempotency check
            if ($offer && $discountAmount > 0) {
                $lockedOffer = Offer::query()->where('id', $offer->id)->lockForUpdate()->first();
                if ($lockedOffer && ! OfferRedemption::query()->where('order_id', $order->id)->exists()) {
                    $offerService->recordRedemption($lockedOffer, $order, $user ?? new User(), $discountAmount);
                }
            }

            return $order;
        });

        if (! empty($payment['order_id'])) {
            Cache::forget("razorpay_order_{$payment['order_id']}");
        }

        if ($product) {
            $this->createShipmentSafely($order, $product);
        }

        if ($user) {
            try {
                app(CommunicationService::class)->send('order_confirmed', $user, [
                    'name' => $order->customer_name,
                    'order_number' => $order->order_number,
                    'amount' => $order->amount,
                ]);
            } catch (Throwable $e) {
                Log::warning('Could not send order confirmation notification in webhook: '.$e->getMessage());
            }
        }

        return response()->json([
            'status' => 'reconciled',
            'order_id' => $order->id,
            'payment_id' => $paymentId,
        ], 200);
    }

    private function reconcilePlanPayment(
        array $payment,
        string $paymentId,
        ?int $itemId,
        ?User $user,
        float $finalAmount,
        array $trustedOrder
    ): JsonResponse {
        $plan = $itemId ? Plan::find($itemId) : null;

        if (! $plan || ! $user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Plan or user not found for plan payment',
            ], 422);
        }

        $expectedAmount = (float) $trustedOrder['final_amount'];

        if (abs($finalAmount - $expectedAmount) > 0.05) {
            Log::warning("Razorpay webhook payment {$paymentId} amount ₹{$finalAmount} does not match expected amount ₹{$expectedAmount} for plan {$plan->id}.");

            return response()->json([
                'status' => 'error',
                'message' => 'Payment amount does not match expected plan pricing',
            ], 422);
        }

        [$order, $membership] = DB::transaction(function () use ($plan, $user, $finalAmount, $paymentId): array {
            $order = Order::query()->create([
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone ?? '',
                'item_type' => 'plan',
                'item_id' => $plan->id,
                'amount' => $finalAmount,
                'payment_status' => 'paid',
                'payment_gateway' => 'razorpay',
                'payment_id' => $paymentId,
            ]);

            Membership::query()
                ->where('user_id', $user->id)
                ->update(['status' => false]);

            $membership = Membership::query()->create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'expires_at' => match ($plan->billing_cycle) {
                    'day' => now()->addDays((int) $plan->duration),
                    'week' => now()->addWeeks((int) $plan->duration),
                    'month' => now()->addMonths((int) $plan->duration),
                    'year' => now()->addYears((int) $plan->duration),
                    default => now()->addMonths((int) $plan->duration),
                },
                'status' => true,
            ]);

            return [$order, $membership];
        });

        try {
            app(CommunicationService::class)->send('membership_purchased', $user, [
                'name' => $user->name,
                'plan_name' => $plan->name,
                'amount' => $order->amount,
                'expiry_date' => $membership->expires_at->format('d M Y'),
            ]);
        } catch (Throwable $e) {
            Log::warning('Could not send membership notification in webhook: '.$e->getMessage());
        }

        if (! empty($payment['order_id'])) {
            Cache::forget("razorpay_order_{$payment['order_id']}");
        }

        return response()->json([
            'status' => 'reconciled',
            'order_id' => $order->id,
            'membership_id' => $membership->id,
            'payment_id' => $paymentId,
        ], 200);
    }

    private function createShipmentSafely(Order $order, Product $product): void
    {
        try {
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
                'message' => 'Order created via webhook and awaiting packing',
                'event_time' => now(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Shipment creation failed in webhook: '.$e->getMessage());
        }
    }
}
