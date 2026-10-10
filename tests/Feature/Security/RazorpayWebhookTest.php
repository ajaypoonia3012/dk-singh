<?php

namespace Tests\Feature\Security;

use App\Models\Membership;
use App\Models\Offer;
use App\Models\OfferRedemption;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Product;
use App\Models\User;
use App\Services\RazorpayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class RazorpayWebhookTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;
    private Plan $plan;
    private string $webhookSecret;

    protected function setUp(): void
    {
        parent::setUp();

        $this->webhookSecret = 'test_webhook_secret_key_123';
        config(['services.razorpay.webhook_secret' => $this->webhookSecret]);
        config(['services.razorpay.key' => 'rzp_test_mock_key']);
        config(['services.razorpay.secret' => 'rzp_test_mock_secret']);

        $this->user = User::factory()->create([
            'email' => 'webhook_customer@test.com',
            'name' => 'Webhook Tester',
            'phone' => '9876543210',
        ]);

        $this->product = Product::create([
            'name' => 'Ashwagandha Gold Powder',
            'slug' => 'ashwagandha-gold-powder',
            'price' => 1499.00,
            'status' => 1,
        ]);

        $this->plan = Plan::create([
            'name' => 'Pro Coaching Plan',
            'price' => 4999.00,
            'duration' => 3,
            'billing_cycle' => 'month',
            'status' => 1,
        ]);
    }

    private function generateSignature(string $payload): string
    {
        return hash_hmac('sha256', $payload, $this->webhookSecret);
    }

    public function test_webhook_rejects_missing_signature(): void
    {
        $payload = json_encode(['event' => 'payment.captured']);

        $response = $this->call(
            'POST',
            route('webhooks.razorpay'),
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $payload
        );

        $response->assertStatus(400);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Signature missing',
        ]);
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        $payload = json_encode(['event' => 'payment.captured']);

        $response = $this->call(
            'POST',
            route('webhooks.razorpay'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_RAZORPAY_SIGNATURE' => 'forged_or_invalid_signature',
            ],
            $payload
        );

        $response->assertStatus(400);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Invalid signature',
        ]);
    }

    public function test_webhook_ignores_unhandled_events(): void
    {
        $payload = json_encode([
            'event' => 'refund.processed',
            'payload' => [
                'refund' => ['entity' => ['id' => 'rfnd_123']],
            ],
        ]);

        $response = $this->call(
            'POST',
            route('webhooks.razorpay'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
            ],
            $payload
        );

        $response->assertOk();
        $response->assertJson([
            'status' => 'ignored',
            'message' => "Event 'refund.processed' not handled",
        ]);
    }

    public function test_webhook_acknowledges_failed_payment_without_creating_orders(): void
    {
        $payload = json_encode([
            'event' => 'payment.failed',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_failed_101',
                        'error_description' => 'Payment failed due to insufficient funds',
                    ],
                ],
            ],
        ]);

        $response = $this->call(
            'POST',
            route('webhooks.razorpay'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
            ],
            $payload
        );

        $response->assertOk();
        $response->assertJson([
            'status' => 'acknowledged',
            'message' => 'Payment failure logged',
            'payment_id' => 'pay_failed_101',
        ]);

        $this->assertDatabaseMissing('orders', ['payment_id' => 'pay_failed_101']);
    }

    public function test_webhook_ignores_non_captured_payment_status(): void
    {
        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_auth_only_999',
                        'status' => 'authorized', // Not captured
                    ],
                ],
            ],
        ]);

        $response = $this->call(
            'POST',
            route('webhooks.razorpay'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
            ],
            $payload
        );

        $response->assertOk();
        $response->assertJson([
            'status' => 'ignored',
            'message' => 'Payment entity missing or not in captured status',
        ]);

        $this->assertDatabaseMissing('orders', ['payment_id' => 'pay_auth_only_999']);
    }

    public function test_webhook_successfully_reconciles_product_payment_with_offer(): void
    {
        $offer = Offer::create([
            'name' => 'Festive 20% Off',
            'code' => 'FESTIVE20',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 20.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $orderId = 'order_rzp_prod_001';
        Cache::put("razorpay_order_{$orderId}", [
            'order_id' => $orderId,
            'amount' => 119920,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'user_id' => $this->user->id,
            'offer_id' => $offer->id,
            'promo_code' => 'FESTIVE20',
            'subtotal' => 1499.00,
            'discount_amount' => 299.80,
            'final_amount' => 1199.20,
            'pricing_snapshot' => [
                'original_price' => 1499.00,
                'discount_amount' => 299.80,
                'final_amount' => 1199.20,
                'reconciled_via' => 'razorpay_webhook_trusted_order',
                'recorded_at' => now()->toIso8601String(),
            ],
            'created_at' => now()->timestamp,
        ], now()->addHours(48));

        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_webhook_prod_001',
                        'order_id' => $orderId,
                        'amount' => 119920, // 1499 - 20% = 1199.20
                        'currency' => 'INR',
                        'status' => 'captured',
                        'email' => $this->user->email,
                        'contact' => '9876543210',
                        'notes' => [
                            'item_type' => 'product',
                            'item_id' => (string) $this->product->id,
                            'user_id' => (string) $this->user->id,
                            'offer_id' => (string) $offer->id,
                            'promo_code' => 'FESTIVE20',
                            'subtotal' => '1499.00',
                            'discount_amount' => '299.80',
                            'shipping_address' => '123 Test Street, Civil Lines',
                            'city' => 'Jaipur',
                            'state' => 'Rajasthan',
                            'pincode' => '302001',
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call(
            'POST',
            route('webhooks.razorpay'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
            ],
            $payload
        );

        $response->assertOk();
        $response->assertJson([
            'status' => 'reconciled',
            'payment_id' => 'pay_webhook_prod_001',
        ]);

        // Verify Order persisted
        $order = Order::where('payment_id', 'pay_webhook_prod_001')->first();
        $this->assertNotNull($order);
        $this->assertEquals(1199.20, $order->amount);
        $this->assertEquals(1499.00, $order->subtotal);
        $this->assertEquals(299.80, $order->discount_amount);
        $this->assertSame($offer->id, $order->offer_id);
        $this->assertSame('FESTIVE20', $order->promo_code);
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame('razorpay_webhook_trusted_order', $order->pricing_snapshot['reconciled_via']);

        // Verify offer redemption recorded
        $this->assertSame(1, $offer->fresh()->total_redemptions);
        $this->assertTrue(OfferRedemption::where('offer_id', $offer->id)->where('order_id', $order->id)->exists());
    }

    public function test_webhook_handles_duplicate_deliveries_idempotently(): void
    {
        $orderId = 'order_dup_002';
        Cache::put("razorpay_order_{$orderId}", [
            'order_id' => $orderId,
            'amount' => 149900,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'user_id' => $this->user->id,
            'subtotal' => 1499.00,
            'discount_amount' => 0.0,
            'final_amount' => 1499.00,
            'created_at' => now()->timestamp,
        ], now()->addHours(48));

        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_dup_webhook_002',
                        'order_id' => $orderId,
                        'amount' => 149900,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'product',
                            'item_id' => (string) $this->product->id,
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $signature = $this->generateSignature($payload);

        // First delivery
        $firstResponse = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $signature,
        ], $payload);

        $firstResponse->assertOk();
        $firstResponse->assertJson(['status' => 'reconciled']);
        $this->assertSame(1, Order::where('payment_id', 'pay_dup_webhook_002')->count());

        // Duplicate delivery
        $secondResponse = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $signature,
        ], $payload);

        $secondResponse->assertOk();
        $secondResponse->assertJson([
            'status' => 'already_processed',
            'payment_id' => 'pay_dup_webhook_002',
        ]);

        // Still exactly one order
        $this->assertSame(1, Order::where('payment_id', 'pay_dup_webhook_002')->count());
    }

    public function test_webhook_does_not_duplicate_order_when_browser_callback_already_created_it(): void
    {
        // Simulate browser callback ran first and created the order
        $order = Order::create([
            'user_id' => $this->user->id,
            'customer_name' => 'Browser Customer',
            'customer_email' => $this->user->email,
            'customer_phone' => '9876543210',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'amount' => 1499.00,
            'payment_status' => 'paid',
            'payment_gateway' => 'razorpay',
            'payment_id' => 'pay_browser_first_003',
        ]);

        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_browser_first_003',
                        'order_id' => 'order_browser_003',
                        'amount' => 149900,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'product',
                            'item_id' => (string) $this->product->id,
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload);

        $response->assertOk();
        $response->assertJson([
            'status' => 'already_processed',
            'payment_id' => 'pay_browser_first_003',
        ]);

        $this->assertSame(1, Order::where('payment_id', 'pay_browser_first_003')->count());
    }

    public function test_browser_callback_does_not_duplicate_order_when_webhook_ran_first(): void
    {
        $orderId = 'order_webhook_first_004';
        Cache::put("razorpay_order_{$orderId}", [
            'order_id' => $orderId,
            'amount' => 149900,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'user_id' => $this->user->id,
            'subtotal' => 1499.00,
            'discount_amount' => 0.0,
            'final_amount' => 1499.00,
            'created_at' => now()->timestamp,
        ], now()->addHours(48));

        // 1. Webhook runs first
        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_webhook_first_004',
                        'order_id' => $orderId,
                        'amount' => 149900,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'product',
                            'item_id' => (string) $this->product->id,
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload)->assertOk();

        $this->assertSame(1, Order::where('payment_id', 'pay_webhook_first_004')->count());

        // 2. Client browser redirects to success callback afterward
        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('verify')->once()->andReturnNull();
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        session()->put('razorpay_orders.order_webhook_first_004', [
            'order_id' => 'order_webhook_first_004',
            'amount' => 149900,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'user_id' => $this->user->id,
            'created_at' => now()->timestamp,
        ]);

        $browserResponse = $this->actingAs($this->user)->post(route('product.payment.success'), [
            'product_id' => $this->product->id,
            'customer_name' => 'Browser Customer',
            'customer_phone' => '9876543210',
            'shipping_address' => 'Flat 12, Tower B',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
            'razorpay_order_id' => 'order_webhook_first_004',
            'razorpay_payment_id' => 'pay_webhook_first_004',
            'razorpay_signature' => 'dummy_sig',
        ]);

        $browserResponse->assertRedirect('/products');
        $browserResponse->assertSessionHas('error', 'This payment has already been processed.');

        // Still exactly one order
        $this->assertSame(1, Order::where('payment_id', 'pay_webhook_first_004')->count());
    }

    public function test_webhook_successfully_reconciles_plan_membership(): void
    {
        $orderId = 'order_plan_005';
        Cache::put("razorpay_order_{$orderId}", [
            'order_id' => $orderId,
            'amount' => 499900,
            'currency' => 'INR',
            'item_type' => 'plan',
            'item_id' => $this->plan->id,
            'user_id' => $this->user->id,
            'subtotal' => 4999.00,
            'discount_amount' => 0.0,
            'final_amount' => 4999.00,
            'created_at' => now()->timestamp,
        ], now()->addHours(48));

        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_plan_webhook_005',
                        'order_id' => $orderId,
                        'amount' => 499900,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'plan',
                            'item_id' => (string) $this->plan->id,
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload);

        $response->assertOk();
        $response->assertJson([
            'status' => 'reconciled',
            'payment_id' => 'pay_plan_webhook_005',
        ]);

        // Verify order created for plan
        $order = Order::where('payment_id', 'pay_plan_webhook_005')->first();
        $this->assertNotNull($order);
        $this->assertSame('plan', $order->item_type);
        $this->assertSame($this->plan->id, $order->item_id);
        $this->assertEquals(4999.00, $order->amount);

        // Verify active membership created
        $membership = Membership::where('user_id', $this->user->id)->where('status', true)->first();
        $this->assertNotNull($membership);
        $this->assertSame($this->plan->id, $membership->plan_id);
        $this->assertTrue($membership->expires_at->isFuture());
    }

    public function test_webhook_rejects_tampered_product_payment_amount(): void
    {
        $orderId = 'order_tampered_001';
        Cache::put("razorpay_order_{$orderId}", [
            'order_id' => $orderId,
            'amount' => 149900,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'user_id' => $this->user->id,
            'final_amount' => 1499.00,
            'created_at' => now()->timestamp,
        ], now()->addHours(48));

        // Product price is 1499, attacker sends captured payment of ₹10 (1000 paise)
        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_tampered_prod_001',
                        'order_id' => $orderId,
                        'amount' => 1000,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'product',
                            'item_id' => (string) $this->product->id,
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Payment amount does not match expected product pricing',
        ]);

        // Zero orders created
        $this->assertDatabaseMissing('orders', ['payment_id' => 'pay_tampered_prod_001']);
    }

    public function test_webhook_rejects_tampered_plan_payment_amount(): void
    {
        $orderId = 'order_tampered_002';
        Cache::put("razorpay_order_{$orderId}", [
            'order_id' => $orderId,
            'amount' => 499900,
            'currency' => 'INR',
            'item_type' => 'plan',
            'item_id' => $this->plan->id,
            'user_id' => $this->user->id,
            'final_amount' => 4999.00,
            'created_at' => now()->timestamp,
        ], now()->addHours(48));

        // Plan price is 4999, attacker sends captured payment of ₹50 (5000 paise)
        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_tampered_plan_001',
                        'order_id' => $orderId,
                        'amount' => 5000,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'plan',
                            'item_id' => (string) $this->plan->id,
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Payment amount does not match expected plan pricing',
        ]);

        $this->assertDatabaseMissing('orders', ['payment_id' => 'pay_tampered_plan_001']);
        $this->assertDatabaseMissing('memberships', ['user_id' => $this->user->id]);
    }

    public function test_webhook_rejects_nonexistent_product(): void
    {
        $orderId = 'order_fake_001';
        Cache::put("razorpay_order_{$orderId}", [
            'order_id' => $orderId,
            'amount' => 149900,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => 999999,
            'user_id' => $this->user->id,
            'final_amount' => 1499.00,
            'created_at' => now()->timestamp,
        ], now()->addHours(48));

        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_nonexistent_prod_001',
                        'order_id' => $orderId,
                        'amount' => 149900,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'product',
                            'item_id' => '999999',
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Product not found for payment',
        ]);

        $this->assertDatabaseMissing('orders', ['payment_id' => 'pay_nonexistent_prod_001']);
    }

    public function test_webhook_reconciles_legitimate_payment_when_product_price_changes_after_checkout(): void
    {
        // 1. Checkout initiated at price 1499.00
        $razorpayOrderId = 'order_locked_price_001';
        Cache::put("razorpay_order_{$razorpayOrderId}", [
            'order_id' => $razorpayOrderId,
            'amount' => 149900,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'user_id' => $this->user->id,
            'offer_id' => null,
            'promo_code' => null,
            'subtotal' => 1499.00,
            'discount_amount' => 0.0,
            'final_amount' => 1499.00,
            'created_at' => now()->timestamp,
        ], now()->addHours(48));

        // 2. Admin alters product price in catalog to ₹1,999 while payment is in-flight!
        $this->product->update(['price' => 1999.00]);

        // 3. Webhook arrives for captured payment at locked checkout price ₹1,499
        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_inflight_price_change_001',
                        'order_id' => $razorpayOrderId,
                        'amount' => 149900,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'product',
                            'item_id' => (string) $this->product->id,
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload);

        $response->assertOk();
        $response->assertJson(['status' => 'reconciled']);

        // Order successfully created at the checkout locked price of 1499
        $order = Order::where('payment_id', 'pay_inflight_price_change_001')->first();
        $this->assertNotNull($order);
        $this->assertEquals(1499.00, $order->amount);
    }

    public function test_webhook_reconciles_legitimate_payment_when_offer_expires_or_is_disabled_after_checkout(): void
    {
        $offer = Offer::create([
            'name' => 'Flash 15',
            'code' => 'FLASH15',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 15.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        // 1. Checkout initiated: 15% off 1499 = 224.85 discount, 1274.15 final
        $razorpayOrderId = 'order_locked_offer_002';
        Cache::put("razorpay_order_{$razorpayOrderId}", [
            'order_id' => $razorpayOrderId,
            'amount' => 127415,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'user_id' => $this->user->id,
            'offer_id' => $offer->id,
            'promo_code' => $offer->code,
            'subtotal' => 1499.00,
            'discount_amount' => 224.85,
            'final_amount' => 1274.15,
            'created_at' => now()->timestamp,
        ], now()->addHours(48));

        // 2. Admin disables the offer while user is on payment screen
        $offer->update([
            'status' => Offer::STATUS_DISABLED,
            'end_date' => now()->subDay(),
        ]);

        // 3. Webhook arrives for captured payment at locked discount total ₹1,274.15
        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_inflight_offer_disabled_002',
                        'order_id' => $razorpayOrderId,
                        'amount' => 127415,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'product',
                            'item_id' => (string) $this->product->id,
                            'user_id' => (string) $this->user->id,
                            'offer_id' => (string) $offer->id,
                            'promo_code' => $offer->code,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload);

        $response->assertOk();
        $response->assertJson(['status' => 'reconciled']);

        // Order created with correct locked discount and redemption recorded
        $order = Order::where('payment_id', 'pay_inflight_offer_disabled_002')->first();
        $this->assertNotNull($order);
        $this->assertEquals(1274.15, $order->amount);
        $this->assertEquals(224.85, $order->discount_amount);
        $this->assertSame($offer->id, $order->offer_id);
        $this->assertDatabaseHas('offer_redemptions', ['order_id' => $order->id, 'offer_id' => $offer->id]);
    }

    public function test_webhook_rejects_tampered_amount_even_with_valid_order_id(): void
    {
        $razorpayOrderId = 'order_tamper_attempt_003';
        Cache::put("razorpay_order_{$razorpayOrderId}", [
            'order_id' => $razorpayOrderId,
            'amount' => 149900,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'user_id' => $this->user->id,
            'final_amount' => 1499.00,
            'created_at' => now()->timestamp,
        ], now()->addHours(48));

        // Attacker pays ₹50 (5000 paise) instead of ₹1499 (149900 paise)
        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_tampered_amount_with_order_003',
                        'order_id' => $razorpayOrderId,
                        'amount' => 5000,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'product',
                            'item_id' => (string) $this->product->id,
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Payment amount does not match expected product pricing',
        ]);

        $this->assertDatabaseMissing('orders', ['payment_id' => 'pay_tampered_amount_with_order_003']);
    }

    public function test_webhook_reconciles_legitimate_plan_payment_when_plan_price_changes_after_checkout(): void
    {
        $razorpayOrderId = 'order_plan_price_change_004';
        Cache::put("razorpay_order_{$razorpayOrderId}", [
            'order_id' => $razorpayOrderId,
            'amount' => 499900,
            'currency' => 'INR',
            'item_type' => 'plan',
            'item_id' => $this->plan->id,
            'user_id' => $this->user->id,
            'final_amount' => 4999.00,
            'created_at' => now()->timestamp,
        ], now()->addHours(48));

        // Admin updates plan price to ₹6,999
        $this->plan->update(['price' => 6999.00]);

        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_plan_inflight_price_change_004',
                        'order_id' => $razorpayOrderId,
                        'amount' => 499900,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'plan',
                            'item_id' => (string) $this->plan->id,
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload);

        $response->assertOk();
        $response->assertJson(['status' => 'reconciled']);

        $order = Order::where('payment_id', 'pay_plan_inflight_price_change_004')->first();
        $this->assertNotNull($order);
        $this->assertEquals(4999.00, $order->amount);
    }

    public function test_webhook_rejects_untrusted_payment_when_checkout_record_is_missing(): void
    {
        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('verifyWebhookSignature')->andReturn(true);
        $mockRazorpay->shouldReceive('fetchOrder')
            ->with('order_unknown_never_checked_out')
            ->once()
            ->andReturnNull();
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        // Untrusted payment arrives: no cache record, Razorpay API order cannot be verified
        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_untrusted_001',
                        'order_id' => 'order_unknown_never_checked_out',
                        'amount' => 149900,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'product',
                            'item_id' => (string) $this->product->id,
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => 'error',
            'message' => 'Trusted checkout record not found or cannot be verified',
        ]);

        $this->assertDatabaseMissing('orders', ['payment_id' => 'pay_untrusted_001']);
    }

    public function test_webhook_reconciles_via_razorpay_api_when_cache_is_evicted(): void
    {
        $orderId = 'order_evicted_from_cache_001';
        // Cache is intentionally empty (simulating cache eviction / expiration)
        Cache::forget("razorpay_order_{$orderId}");

        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('verifyWebhookSignature')->andReturn(true);
        $mockRazorpay->shouldReceive('fetchOrder')
            ->with($orderId)
            ->once()
            ->andReturn([
                'id' => $orderId,
                'amount' => 149900,
                'currency' => 'INR',
                'notes' => [
                    'item_type' => 'product',
                    'item_id' => (string) $this->product->id,
                    'user_id' => (string) $this->user->id,
                    'original_price' => '1499.00',
                    'discount_amount' => '0.0',
                ],
            ]);
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_reconciled_after_eviction_001',
                        'order_id' => $orderId,
                        'amount' => 149900,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'notes' => [
                            'item_type' => 'product',
                            'item_id' => (string) $this->product->id,
                            'user_id' => (string) $this->user->id,
                        ],
                    ],
                ],
            ],
        ]);

        $response = $this->call('POST', route('webhooks.razorpay'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_RAZORPAY_SIGNATURE' => $this->generateSignature($payload),
        ], $payload);

        $response->assertOk();
        $response->assertJson([
            'status' => 'reconciled',
            'payment_id' => 'pay_reconciled_after_eviction_001',
        ]);

        $order = Order::where('payment_id', 'pay_reconciled_after_eviction_001')->first();
        $this->assertNotNull($order);
        $this->assertEquals(1499.00, $order->amount);
        $this->assertSame('razorpay_api_order_fetch', $order->pricing_snapshot['reconciled_via']);
    }
}
