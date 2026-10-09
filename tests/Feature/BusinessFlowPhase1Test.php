<?php

namespace Tests\Feature;

use App\Models\ContactLead;
use App\Models\Membership;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Program;
use App\Models\Service;
use App\Models\User;
use App\Services\RazorpayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class BusinessFlowPhase1Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        \App\Models\Setting::query()->create([
            'site_name' => 'DK Singh Fitness',
            'email' => 'coach@dksinghfitness.com',
            'contact_title' => 'Get in Touch',
            'program_label' => 'Programs',
        ]);

        \App\Models\ThemeSetting::query()->create([
            'theme_name' => 'Default',
            'show_hero' => true,
            'show_programs' => true,
            'show_services' => true,
            'show_products' => true,
        ]);

        Program::query()->create([
            'title' => '12 Week Fat Loss Transformation',
            'slug' => '12-week-fat-loss',
            'description' => 'Comprehensive fat loss program with science-backed nutrition.',
            'category' => 'fat-loss',
            'duration' => '12 Weeks',
            'price' => 4999.00,
            'featured' => true,
            'status' => true,
            'sort_order' => 1,
        ]);

        Plan::query()->create([
            'name' => 'Pro Membership',
            'slug' => 'pro-membership',
            'description' => 'Full access to training and nutrition guidance.',
            'price' => 2999.00,
            'discount_price' => 2499.00,
            'duration' => '3 Months',
            'billing_cycle' => 'quarterly',
            'access_type' => 'pro',
            'badge' => 'Most Popular',
            'button_text' => 'Get Started',
            'status' => true,
            'featured' => true,
            'features' => ['Custom Workout', 'Custom Diet', 'Weekly Check-ins'],
        ]);

        Service::query()->create([
            'title' => '1-on-1 Online Coaching',
            'slug' => '1-on-1-online-coaching',
            'description' => 'Personalized guidance from DK Singh directly.',
            'price' => 9999.00,
            'duration' => '3 Months',
            'features' => ['Direct WhatsApp support', 'Custom Macro adjustments'],
            'button_text' => 'Get Started',
            'featured' => true,
            'status' => true,
            'sort_order' => 1,
        ]);

        Product::query()->create([
            'name' => 'Premium Whey Protein Isolate',
            'slug' => 'premium-whey-protein-isolate',
            'sku' => 'WHEY-ISO-1KG',
            'description' => 'High quality ultra-filtered whey protein isolate.',
            'price' => 2899.00,
            'weight' => '1.2kg',
            'category' => 'Supplements',
            'featured' => true,
            'status' => true,
            'sort_order' => 1,
        ]);
    }

    /**
     * 1. Homepage Program card -> Program detail
     */
    public function test_homepage_program_card_links_to_program_detail(): void
    {
        $program = Program::query()->first();
        $this->assertNotNull($program, 'Seed must provide at least one program.');

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee(route('programs.show', $program->slug), false);
    }

    /**
     * 2. Program -> correct plan/access flow
     */
    public function test_program_detail_links_to_plans_with_program_context(): void
    {
        $program = Program::query()->first();
        $this->assertNotNull($program);

        $response = $this->get(route('programs.show', $program->slug));
        $response->assertStatus(200);
        $response->assertSee(url('/plans?program=' . $program->slug), false);
    }

    /**
     * 3. Selected Program context preserved on plans and checkout
     */
    public function test_selected_program_context_preserved_on_plans_and_checkout(): void
    {
        $program = Program::query()->first();
        $plan = Plan::query()->first();
        $this->assertNotNull($program);
        $this->assertNotNull($plan);

        // On /plans?program={slug}
        $plansResponse = $this->get('/plans?program=' . $program->slug);
        $plansResponse->assertStatus(200);
        $plansResponse->assertSee('Selected Program Curriculum');
        $plansResponse->assertSee($program->title);
        $plansResponse->assertSee(url('/checkout/' . $plan->id . '?program=' . $program->slug), false);

        // On /checkout/{id}?program={slug}
        $user = User::factory()->create(['profile_completed' => true]);

        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('createOrder')->andReturn([
            'id' => 'order_plan_mock_123',
            'amount' => ($plan->discount_price ?? $plan->price) * 100,
            'currency' => 'INR',
        ]);
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        $checkoutResponse = $this->actingAs($user)->get('/checkout/' . $plan->id . '?program=' . $program->slug);
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('Target Curriculum');
        $checkoutResponse->assertSee($program->title);
    }

    /**
     * 4. Product checkout does not require fitness profile
     */
    public function test_product_checkout_does_not_require_fitness_profile(): void
    {
        $product = Product::query()->first();
        $this->assertNotNull($product);

        $unprofiledUser = User::factory()->create([
            'profile_completed' => false,
            'height' => null,
            'weight' => null,
            'goal' => null,
        ]);

        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('createOrder')->andReturn([
            'id' => 'order_prod_mock_456',
            'amount' => $product->price * 100,
            'currency' => 'INR',
        ]);
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        // Accessing product.checkout without completed profile succeeds (does not redirect to /member/profile)
        $checkoutResponse = $this->actingAs($unprofiledUser)->get(route('product.checkout', $product->id));
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('Product Checkout');
        $checkoutResponse->assertSee($product->name);

        // Accessing product.payment without completed profile succeeds
        $paymentResponse = $this->actingAs($unprofiledUser)->get(route('product.payment', $product->id));
        $paymentResponse->assertStatus(200);
        $paymentResponse->assertSee('Product Checkout');
    }

    /**
     * 5. Product checkout creates correct order
     */
    public function test_product_checkout_creates_correct_order(): void
    {
        $product = Product::query()->first();
        $user = User::factory()->create(['profile_completed' => false]);

        $razorpayOrderId = 'order_prod_valid_789';
        $razorpayPaymentId = 'pay_prod_valid_789';

        session()->put("razorpay_orders.{$razorpayOrderId}", [
            'order_id' => $razorpayOrderId,
            'amount' => $product->price * 100,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $product->id,
            'user_id' => $user->id,
            'created_at' => now()->timestamp,
        ]);

        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('verify')->once()->andReturn(true);
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        $response = $this->actingAs($user)->post(route('product.payment.success'), [
            'product_id' => $product->id,
            'customer_name' => 'Ajay Test',
            'customer_phone' => '9876543210',
            'shipping_address' => '123 Fitness Street, Civil Lines',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
            'razorpay_order_id' => $razorpayOrderId,
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_signature' => 'mock_signature_hash',
        ]);

        $response->assertRedirect('/products');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'customer_name' => 'Ajay Test',
            'item_type' => 'product',
            'item_id' => $product->id,
            'amount' => $product->price,
            'payment_status' => 'paid',
            'payment_id' => $razorpayPaymentId,
        ]);
    }

    /**
     * 6. Product payment remains protected against unauthenticated users
     */
    public function test_product_payment_remains_protected_against_guests(): void
    {
        $product = Product::query()->first();
        $this->assertNotNull($product);

        $checkoutResponse = $this->get(route('product.checkout', $product->id));
        $checkoutResponse->assertRedirect('/login');

        $paymentResponse = $this->get(route('product.payment', $product->id));
        $paymentResponse->assertRedirect('/login');
    }

    /**
     * 7. Coaching service context preserved in lead
     */
    public function test_coaching_service_context_preserved_in_lead(): void
    {
        $service = Service::query()->first();
        $this->assertNotNull($service);

        // Service detail has link to contact with service slug
        $serviceResponse = $this->get(route('services.show', $service->slug));
        $serviceResponse->assertStatus(200);
        $serviceResponse->assertSee(route('contact', ['service' => $service->slug]), false);

        // Contact page displays service banner
        $contactResponse = $this->get(route('contact', ['service' => $service->slug]));
        $contactResponse->assertStatus(200);
        $contactResponse->assertSee('Interested Coaching Service');
        $contactResponse->assertSee($service->title);

        // Submitting contact form saves service context in lead
        $submitResponse = $this->post(route('contact.submit'), [
            'name' => 'Rahul Sharma',
            'email' => 'rahul@example.com',
            'phone' => '9876500000',
            'service' => $service->title,
            'message' => 'I would like to start 1-on-1 coaching.',
        ]);

        $submitResponse->assertSessionHas('success');

        $this->assertDatabaseHas('contact_leads', [
            'name' => 'Rahul Sharma',
            'email' => 'rahul@example.com',
            'source' => "service: {$service->title}",
            'notes' => "Interested Service: {$service->title}",
        ]);

        $lead = ContactLead::where('email', 'rahul@example.com')->first();
        $this->assertStringContainsString($service->title, $lead->message);
    }

    /**
     * 8. Payment failure does not grant access
     */
    public function test_payment_failure_does_not_grant_access(): void
    {
        $user = User::factory()->create(['profile_completed' => true]);

        // Simulating failed payment redirect
        $failedResponse = $this->actingAs($user)->get('/payment-failed');
        $failedResponse->assertRedirect('/plans');
        $failedResponse->assertSessionHas('error', 'Payment failed.');

        // User has no active membership
        $this->assertFalse($user->isMember());
        $this->assertFalse($user->hasBasicAccess());

        // Attempting to access protected workout plans redirects to plans
        $workoutAccess = $this->actingAs($user)->get(route('workout-plans.index'));
        $workoutAccess->assertRedirect('/plans');

        // Attempting to access member action plan redirects to plans
        $actionPlanAccess = $this->actingAs($user)->get(route('member.action-plan'));
        $actionPlanAccess->assertRedirect('/plans');
    }

    /**
     * 9. Existing membership access remains intact
     */
    public function test_existing_membership_access_remains_intact(): void
    {
        $plan = Plan::query()->where('access_type', 'pro')->first() ?? Plan::query()->first();
        $user = User::factory()->create(['profile_completed' => true]);

        Membership::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => now(),
            'expires_at' => now()->addMonth(),
            'status' => true,
        ]);

        $this->assertTrue($user->isMember());

        // Can access member dashboard
        $dashboardResponse = $this->actingAs($user)->get(route('member.dashboard'));
        $dashboardResponse->assertStatus(200);

        // Can access my-plan
        $myPlanResponse = $this->actingAs($user)->get(route('member.my-plan'));
        $myPlanResponse->assertStatus(200);
        $myPlanResponse->assertSee($plan->name);

        // Can access workout-plans
        $workoutResponse = $this->actingAs($user)->get(route('workout-plans.index'));
        $workoutResponse->assertStatus(200);
    }
}
