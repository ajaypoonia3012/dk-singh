<?php

namespace Tests\Feature\Offers;

use App\Models\Offer;
use App\Models\OfferRedemption;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Models\User;
use App\Services\RazorpayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ProductOfferCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $this->user = User::factory()->create([
            'name' => 'Ajay Test',
            'email' => 'ajay@test.com',
            'phone' => '9876543210',
            'account_type' => 'customer',
        ]);

        $this->product = Product::create([
            'name' => 'Ayurvedic Herbal Powder 500g',
            'slug' => 'ayurvedic-herbal-powder-500g',
            'price' => 1499.00,
            'description' => 'Premium natural wellness powder.',
            'status' => true,
        ]);

        Setting::create([
            'site_name' => 'DK Singh Fitness',
            'powder_promo_enabled' => true,
        ]);

        ThemeSetting::create(['theme_name' => 'Default']);
    }

    public function test_product_detail_page_displays_active_applicable_offers(): void
    {
        $offer = Offer::create([
            'name' => 'Super Saver 15%',
            'code' => 'SAVER15',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 15.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $response = $this->get(route('products.show', $this->product->slug));

        $response->assertOk();
        $response->assertSee('SAVER15');
        $response->assertSee('Save 15%');
        $response->assertSee(route('product.checkout', ['id' => $this->product->id, 'code' => 'SAVER15']));
    }

    public function test_checkout_page_renders_with_available_offers_and_initial_price(): void
    {
        $offer = Offer::create([
            'name' => 'Flat ₹200 Off',
            'code' => 'FLAT200',
            'discount_type' => Offer::TYPE_FIXED,
            'discount_value' => 200.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('createOrder')
            ->once()
            ->with('product', $this->product->id, $this->user->id, 1499.00)
            ->andReturn([
                'id' => 'order_initial_123',
                'amount' => 149900,
                'currency' => 'INR',
            ]);
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        $response = $this->actingAs($this->user)->get(route('product.checkout', $this->product->id));

        $response->assertOk();
        $response->assertSee('Product Checkout');
        $response->assertSee('FLAT200');
        $response->assertSee('Pay &#8377;1,499', false);
        $this->assertArrayHasKey('order_initial_123', session('razorpay_orders'));
    }

    public function test_applying_promo_code_via_ajax_calculates_discount_and_recreates_razorpay_order(): void
    {
        $offer = Offer::create([
            'name' => '15% Off Promo',
            'code' => 'SUMMER15',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 15.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        // 15% off 1499.00 = 224.85 discount, 1274.15 final amount
        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('createOrder')
            ->once()
            ->with('product', $this->product->id, $this->user->id, 1274.15)
            ->andReturn([
                'id' => 'order_discounted_456',
                'amount' => 127415,
                'currency' => 'INR',
            ]);
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        // Case-insensitive test: user enters lowercase 'summer15'
        $response = $this->actingAs($this->user)->postJson(
            route('product.checkout.apply-offer', $this->product->id),
            ['code' => 'summer15']
        );

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'applied' => true,
            'pricing' => [
                'original_price' => 1499.00,
                'discount_amount' => 224.85,
                'final_amount' => 1274.15,
                'amount_in_paise' => 127415,
                'promo_code' => 'SUMMER15',
            ],
            'razorpay_order' => [
                'id' => 'order_discounted_456',
                'amount' => 127415,
            ],
        ]);

        $sessionData = session('razorpay_orders.order_discounted_456');
        $this->assertNotNull($sessionData);
        $this->assertSame($offer->id, $sessionData['offer_id']);
        $this->assertSame('SUMMER15', $sessionData['promo_code']);
        $this->assertEquals(224.85, $sessionData['discount_amount']);
        $this->assertEquals(1274.15, $sessionData['final_amount']);
    }

    public function test_flat_discount_application_via_ajax(): void
    {
        $offer = Offer::create([
            'name' => 'Flat ₹500 Discount',
            'code' => 'FLAT500',
            'discount_type' => Offer::TYPE_FIXED,
            'discount_value' => 500.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('createOrder')
            ->once()
            ->with('product', $this->product->id, $this->user->id, 999.00)
            ->andReturn([
                'id' => 'order_flat_789',
                'amount' => 99900,
                'currency' => 'INR',
            ]);
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        $response = $this->actingAs($this->user)->postJson(
            route('product.checkout.apply-offer', $this->product->id),
            ['code' => 'FLAT500']
        );

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'applied' => true,
            'pricing' => [
                'original_price' => 1499.00,
                'discount_amount' => 500.00,
                'final_amount' => 999.00,
                'amount_in_paise' => 99900,
            ],
        ]);
    }

    public function test_removing_offer_reverts_to_full_price(): void
    {
        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('createOrder')
            ->once()
            ->with('product', $this->product->id, $this->user->id, 1499.00)
            ->andReturn([
                'id' => 'order_reverted_101',
                'amount' => 149900,
                'currency' => 'INR',
            ]);
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        $response = $this->actingAs($this->user)->postJson(
            route('product.checkout.apply-offer', $this->product->id),
            ['code' => '__remove__']
        );

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'applied' => false,
            'pricing' => [
                'original_price' => 1499.00,
                'discount_amount' => 0.00,
                'final_amount' => 1499.00,
            ],
        ]);
    }

    public function test_rejection_of_expired_disabled_and_future_offers(): void
    {
        Offer::create([
            'name' => 'Expired',
            'code' => 'EXPIRED',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 10.00,
            'status' => Offer::STATUS_ACTIVE,
            'ends_at' => now()->subHour(),
        ]);

        Offer::create([
            'name' => 'Future',
            'code' => 'FUTURE',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 10.00,
            'status' => Offer::STATUS_ACTIVE,
            'starts_at' => now()->addWeek(),
        ]);

        Offer::create([
            'name' => 'Disabled',
            'code' => 'DISABLED',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 10.00,
            'status' => Offer::STATUS_DISABLED,
        ]);

        // 1. Expired
        $res1 = $this->actingAs($this->user)->postJson(
            route('product.checkout.apply-offer', $this->product->id),
            ['code' => 'EXPIRED']
        );
        $res1->assertStatus(422)->assertJson(['success' => false, 'message' => 'This promo code has expired.']);

        // 2. Future
        $res2 = $this->actingAs($this->user)->postJson(
            route('product.checkout.apply-offer', $this->product->id),
            ['code' => 'FUTURE']
        );
        $res2->assertStatus(422)->assertJson(['success' => false, 'message' => 'This promo code is not active yet.']);

        // 3. Disabled
        $res3 = $this->actingAs($this->user)->postJson(
            route('product.checkout.apply-offer', $this->product->id),
            ['code' => 'DISABLED']
        );
        $res3->assertStatus(422)->assertJson(['success' => false, 'message' => 'This offer has been disabled.']);
    }

    public function test_rejection_when_minimum_order_value_is_not_met(): void
    {
        Offer::create([
            'name' => 'High Value Only',
            'code' => 'HIGHVAL',
            'discount_type' => Offer::TYPE_FIXED,
            'discount_value' => 500.00,
            'min_order_value' => 3000.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $response = $this->actingAs($this->user)->postJson(
            route('product.checkout.apply-offer', $this->product->id),
            ['code' => 'HIGHVAL']
        );

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('minimum order subtotal', $response->json('message'));
    }

    public function test_rejection_when_redemption_limits_are_reached(): void
    {
        $offer = Offer::create([
            'name' => 'Limited Uses',
            'code' => 'LIMITED',
            'discount_type' => Offer::TYPE_FIXED,
            'discount_value' => 100.00,
            'max_redemptions' => 1,
            'total_redemptions' => 1,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $response = $this->actingAs($this->user)->postJson(
            route('product.checkout.apply-offer', $this->product->id),
            ['code' => 'LIMITED']
        );

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'This offer has reached its maximum redemption limit.',
        ]);
    }

    public function test_successful_payment_persists_immutable_pricing_snapshot_and_increments_redemption(): void
    {
        $offer = Offer::create([
            'name' => 'Ayurvedic 15% Off',
            'code' => 'AYUR15',
            'discount_type' => Offer::TYPE_PERCENTAGE,
            'discount_value' => 15.00,
            'status' => Offer::STATUS_ACTIVE,
        ]);

        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('verify')->once()->andReturnNull();
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        // Populate valid session with offer pricing
        session()->put('razorpay_orders.order_paid_123', [
            'order_id' => 'order_paid_123',
            'amount' => 127415,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'user_id' => $this->user->id,
            'offer_id' => $offer->id,
            'promo_code' => 'AYUR15',
            'subtotal' => 1499.00,
            'discount_amount' => 224.85,
            'final_amount' => 1274.15,
            'pricing_snapshot' => [
                'original_price' => 1499.00,
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'discount_amount' => 224.85,
                'final_amount' => 1274.15,
                'offer_id' => $offer->id,
                'promo_code' => 'AYUR15',
            ],
            'created_at' => now()->timestamp,
        ]);

        $this->assertSame(0, $offer->fresh()->total_redemptions);

        $response = $this->actingAs($this->user)->post(route('product.payment.success'), [
            'product_id' => $this->product->id,
            'customer_name' => 'Ajay Test',
            'customer_phone' => '9876543210',
            'shipping_address' => 'Flat 402, Green Avenue',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
            'razorpay_order_id' => 'order_paid_123',
            'razorpay_payment_id' => 'pay_abc_999',
            'razorpay_signature' => 'valid_sig_xyz',
        ]);

        $response->assertRedirect('/products');
        $response->assertSessionHas('success', 'Product purchased successfully.');

        // Verify Order in database
        $order = Order::where('payment_id', 'pay_abc_999')->first();
        $this->assertNotNull($order);
        $this->assertEquals(1274.15, $order->amount);
        $this->assertEquals(1499.00, $order->subtotal);
        $this->assertEquals(224.85, $order->discount_amount);
        $this->assertSame($offer->id, $order->offer_id);
        $this->assertSame('AYUR15', $order->promo_code);
        $this->assertIsArray($order->pricing_snapshot);
        $this->assertSame('AYUR15', $order->pricing_snapshot['promo_code']);

        // Verify Redemption was recorded
        $this->assertSame(1, $offer->fresh()->total_redemptions);
        $this->assertTrue(OfferRedemption::where('offer_id', $offer->id)->where('order_id', $order->id)->exists());
    }

    public function test_payment_idempotency_prevents_duplicate_order_processing(): void
    {
        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('verify')->once()->andReturnNull();
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        Order::create([
            'user_id' => $this->user->id,
            'customer_name' => 'Ajay Test',
            'customer_email' => 'ajay@test.com',
            'customer_phone' => '9876543210',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'amount' => 1499.00,
            'payment_status' => 'paid',
            'payment_gateway' => 'razorpay',
            'payment_id' => 'pay_existing_111',
            'shipping_address' => 'Test address',
            'city' => 'City',
            'state' => 'State',
            'pincode' => '123456',
            'order_status' => 'pending',
        ]);

        session()->put('razorpay_orders.order_dup_111', [
            'order_id' => 'order_dup_111',
            'amount' => 149900,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $this->product->id,
            'user_id' => $this->user->id,
            'created_at' => now()->timestamp,
        ]);

        $response = $this->actingAs($this->user)->post(route('product.payment.success'), [
            'product_id' => $this->product->id,
            'customer_name' => 'Ajay Test',
            'customer_phone' => '9876543210',
            'shipping_address' => 'Flat 402, Green Avenue',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
            'razorpay_order_id' => 'order_dup_111',
            'razorpay_payment_id' => 'pay_existing_111',
            'razorpay_signature' => 'valid_sig_xyz',
        ]);

        $response->assertRedirect('/products');
        $response->assertSessionHas('error', 'This payment has already been processed.');
    }
}
