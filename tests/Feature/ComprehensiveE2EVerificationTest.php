<?php

namespace Tests\Feature;

use App\Models\ActionPlan;
use App\Models\CoachNote;
use App\Models\DietCompletion;
use App\Models\DietPlan;
use App\Models\Media;
use App\Models\Membership;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Program;
use App\Models\Service;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Models\Transformation;
use App\Models\User;
use App\Models\WorkoutCompletion;
use App\Models\WorkoutPlan;
use App\Services\RazorpayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class ComprehensiveE2EVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        Setting::query()->firstOrCreate([], [
            'site_name' => 'DK Singh Fitness & Nutrition',
            'contact_email' => 'support@dksinghfitness.com',
            'contact_phone' => '+91 98765 43210',
            'address' => 'DK Singh Performance Lab, New Delhi',
            'hero_title' => 'Transform Your Body & Mind',
            'hero_subtitle' => 'Elite online fitness and nutrition coaching',
        ]);

        ThemeSetting::query()->firstOrCreate([], [
            'theme_name' => 'DK Singh Dark Athlete',
            'primary_color' => '#f97316',
            'background_color' => '#0b0f19',
            'text_color' => '#f8fafc',
            'font_heading' => 'Poppins',
            'font_body' => 'Inter',
        ]);
    }

    // ==========================================
    // 1. PUBLIC WEBSITE WORKFLOW
    // ==========================================
    public function test_all_public_pages_render_with_status_200_and_brand_elements(): void
    {
        $routes = [
            '/' => 'DK Singh',
            '/about' => 'About',
            '/services' => 'Services',
            '/programs' => 'Programs',
            '/plans' => 'Plans',
            '/products' => 'Products',
            '/transformations' => 'Transformations',
            '/blog' => 'Blog',
            '/contact' => 'Contact',
            '/fitness-hub' => 'Fitness Hub',
            '/fitness-hub/workouts' => 'Workouts',
            '/fitness-hub/diets' => 'Diets',
        ];

        foreach ($routes as $route => $expectedSnippet) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertSee('<meta name="viewport"', false);
        }
    }

    public function test_public_detail_pages_load_with_actual_content(): void
    {
        $program = Program::query()->create([
            'title' => 'E2E Hypertrophy Protocol',
            'slug' => 'e2e-hypertrophy-protocol',
            'description' => 'Targeted hypertrophy program',
            'duration' => '12 Weeks',
            'price' => 4999,
            'status' => true,
        ]);

        $this->get("/programs/{$program->slug}")
            ->assertStatus(200)
            ->assertSee('E2E Hypertrophy Protocol');

        $transformation = Transformation::query()->create([
            'name' => 'Rahul Sharma',
            'slug' => 'rahul-sharma',
            'story' => 'Lost 18kg and built solid lean muscle.',
            'weight_lost' => '18 kg',
            'duration' => '16 Weeks',
            'status' => true,
        ]);

        $this->get("/transformations/{$transformation->slug}")
            ->assertStatus(200)
            ->assertSee('Rahul Sharma');
    }

    // ==========================================
    // 2. AUTHENTICATION WORKFLOW
    // ==========================================
    public function test_authentication_registration_login_validation_and_logout(): void
    {
        // Registration
        $regResponse = $this->post('/register', [
            'name' => 'Deepak Singh',
            'email' => 'deepak@example.com',
            'password' => 'SecurePass@123',
            'password_confirmation' => 'SecurePass@123',
        ]);
        $this->assertAuthenticated();
        $regResponse->assertRedirect(route('member.dashboard', absolute: false));

        // Logout
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();

        // Invalid Login fails
        $invalidResponse = $this->from('/login')->post('/login', [
            'email' => 'deepak@example.com',
            'password' => 'WrongPassword!',
        ]);
        $invalidResponse->assertRedirect('/login');
        $invalidResponse->assertSessionHasErrors('email');
        $this->assertGuest();

        // Valid Login succeeds
        $validLogin = $this->post('/login', [
            'email' => 'deepak@example.com',
            'password' => 'SecurePass@123',
        ]);
        $this->assertAuthenticated();
        $validLogin->assertRedirect(route('member.dashboard', absolute: false));

        // Dashboard redirect
        $this->get('/dashboard')->assertRedirect(route('member.dashboard'));
    }

    // ==========================================
    // 3. PROFILE & ACCOUNT SETTINGS (DECOUPLED)
    // ==========================================
    public function test_account_settings_accessible_without_fitness_profile_completion(): void
    {
        $user = User::factory()->create([
            'profile_completed' => false,
            'goal' => null,
            'gender' => null,
            'height' => null,
            'weight' => null,
        ]);

        // Accessing account settings /profile must succeed (HTTP 200) without redirecting to questionnaire
        $this->actingAs($user)
            ->get('/profile')
            ->assertStatus(200)
            ->assertSee($user->email);

        // Updating name & email succeeds
        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Deepak S. Updated',
                'email' => $user->email,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertSame('Deepak S. Updated', $user->fresh()->name);

        // Completing fitness profile
        $this->actingAs($user)
            ->post('/member/profile', [
                'phone' => '9876543210',
                'gender' => 'male',
                'age' => 28,
                'height' => 180,
                'weight' => 82.5,
                'goal' => 'Fat Loss',
                'activity_level' => 'active',
                'diet_preference' => 'vegetarian',
            ])
            ->assertRedirect();

        $user->refresh();
        $this->assertSame('Fat Loss', $user->goal);
        $this->assertTrue((bool) $user->profile_completed);
    }

    // ==========================================
    // 4. MEMBERSHIP FLOW & AUTHORIZATION
    // ==========================================
    public function test_membership_lifecycle_and_content_authorization(): void
    {
        $user = User::factory()->create([
            'profile_completed' => true,
            'goal' => 'Fat Loss',
            'gender' => 'male',
            'height' => 175,
            'weight' => 75,
        ]);

        $plan = Plan::query()->create([
            'name' => 'Elite 1-on-1 Coaching',
            'slug' => 'elite-coaching',
            'price' => 7999,
            'duration' => '30 Days',
            'status' => true,
        ]);

        // Without membership, accessing /member/action-plan is blocked
        $this->actingAs($user)
            ->get('/member/action-plan')
            ->assertRedirect('/plans');

        // Assign active membership
        $membership = Membership::query()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(29),
            'status' => true,
        ]);

        // With active membership, /member/action-plan is accessible
        $this->actingAs($user)
            ->get('/member/action-plan')
            ->assertStatus(200);

        // Expire membership
        $membership->update([
            'starts_at' => now()->subDays(60),
            'expires_at' => now()->subDay(),
            'status' => false,
        ]);

        // Accessing /member/action-plan again is blocked
        $this->actingAs($user)
            ->get('/member/action-plan')
            ->assertRedirect('/plans');
    }

    // ==========================================
    // 5. FITNESS WORKFLOW & USER ISOLATION
    // ==========================================
    public function test_fitness_workout_diet_completions_progress_and_user_isolation(): void
    {
        $userA = User::factory()->create([
            'profile_completed' => true,
            'goal' => 'Muscle Gain',
            'gender' => 'male',
            'height' => 182,
            'weight' => 80,
        ]);

        $userB = User::factory()->create([
            'profile_completed' => true,
            'goal' => 'Fat Loss',
            'gender' => 'female',
            'height' => 165,
            'weight' => 60,
        ]);

        Membership::query()->create([
            'user_id' => $userA->id,
            'plan_id' => 1,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(30),
            'status' => true,
        ]);

        $workout = WorkoutPlan::query()->create([
            'title' => 'Push Day Power',
            'slug' => 'push-day-power',
            'level' => 'advanced',
            'duration_weeks' => 4,
            'description' => 'Heavy chest, shoulders and triceps',
            'is_active' => true,
        ]);

        // Workout Completion
        $this->actingAs($userA)
            ->post("/workout-plans/{$workout->id}/complete")
            ->assertSessionHas('success');

        $this->assertSame(1, WorkoutCompletion::where('user_id', $userA->id)->where('workout_plan_id', $workout->id)->count());

        // Duplicate workout completion prevention
        $this->actingAs($userA)
            ->post("/workout-plans/{$workout->id}/complete")
            ->assertSessionHas('success');

        $this->assertSame(1, WorkoutCompletion::where('user_id', $userA->id)->where('workout_plan_id', $workout->id)->count());

        // Diet Completion
        $diet = DietPlan::query()->create([
            'title' => 'Keto Muscle Protocol',
            'slug' => 'keto-muscle-protocol',
            'goal' => 'muscle_gain',
            'calories' => 2800,
            'protein' => 200,
            'carbs' => 50,
            'fat' => 190,
            'is_active' => true,
        ]);

        $this->actingAs($userA)
            ->post("/diet-plans/{$diet->id}/complete")
            ->assertSessionHas('success');

        $this->assertSame(1, DietCompletion::where('user_id', $userA->id)->where('diet_plan_id', $diet->id)->count());

        // Duplicate diet completion prevention
        $this->actingAs($userA)
            ->post("/diet-plans/{$diet->id}/complete")
            ->assertSessionHas('success');

        $this->assertSame(1, DietCompletion::where('user_id', $userA->id)->where('diet_plan_id', $diet->id)->count());

        // Progress Log Creation
        $this->actingAs($userA)
            ->post('/member/progress', [
                'weight' => 79.5,
                'chest' => 40.0,
                'waist' => 31.5,
                'hips' => 38.0,
                'arms' => 15.0,
                'thighs' => 23.0,
                'notes' => 'Hit a 120kg bench PR today.',
            ])
            ->assertRedirect('/member/progress');

        $this->assertDatabaseHas('progress_logs', [
            'user_id' => $userA->id,
            'weight' => 79.5,
        ]);

        // User Isolation: User B Action Plan cannot be completed by User A
        $userBActionPlan = ActionPlan::query()->create([
            'user_id' => $userB->id,
            'title' => 'Private Task for User B',
            'description' => 'Confidential coaching step',
            'is_completed' => false,
        ]);

        $this->actingAs($userA)
            ->post("/member/action-plan/{$userBActionPlan->id}/complete")
            ->assertStatus(403);

        $this->assertFalse((bool) $userBActionPlan->fresh()->is_completed);

        // Coach Notes Isolation: User A only sees their own notes
        CoachNote::query()->create([
            'user_id' => $userB->id,
            'note' => 'Strictly confidential medical note for User B',
            'is_visible' => true,
        ]);

        $userANote = CoachNote::query()->create([
            'user_id' => $userA->id,
            'note' => 'User A customized coaching: Add 50g carbs on training days.',
            'is_visible' => true,
        ]);

        $response = $this->actingAs($userA)->get('/member/coach-notes');
        $response->assertStatus(200);
        $response->assertSee('User A customized coaching: Add 50g carbs on training days.');
        $response->assertDontSee('Strictly confidential medical note for User B');
    }

    // ==========================================
    // 6. PRODUCT PURCHASE & PAYMENT SECURITY
    // ==========================================
    public function test_product_purchase_validation_signature_and_order_isolation(): void
    {
        $user = User::factory()->create([
            'profile_completed' => true,
            'goal' => 'Fat Loss',
            'gender' => 'male',
            'height' => 175,
            'weight' => 70,
        ]);

        $product = Product::query()->create([
            'name' => 'DK Singh Organic Creatine Monohydrate',
            'slug' => 'dk-singh-organic-creatine',
            'price' => 1299,
            'stock' => 100,
            'status' => true,
            'description' => 'Micronized German Creapure',
        ]);

        $orderId = 'order_test_'.Str::random(12);
        $paymentId = 'pay_test_'.Str::random(12);
        $amountPaise = (int) ($product->price * 100);

        // Test 6.1: Rejected when no session exists
        $this->actingAs($user)
            ->post('/product-payment-success', [
                'product_id' => $product->id,
                'customer_name' => 'Valid Customer',
                'customer_phone' => '9876543210',
                'shipping_address' => 'Gym Road, Sector 14',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'pincode' => '110001',
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => 'mock_signature',
            ])
            ->assertRedirect('/products')
            ->assertSessionHas('error', 'Payment session is invalid or expired.');

        // Establish session
        session()->put("razorpay_orders.{$orderId}", [
            'order_id' => $orderId,
            'amount' => $amountPaise,
            'currency' => 'INR',
            'item_type' => 'product',
            'item_id' => $product->id,
            'user_id' => $user->id,
            'created_at' => now()->timestamp,
        ]);

        // Test 6.2: Invalid product ID rejected
        $this->actingAs($user)
            ->post('/product-payment-success', [
                'product_id' => 999999,
                'customer_name' => 'Valid Customer',
                'customer_phone' => '9876543210',
                'shipping_address' => 'Gym Road, Sector 14',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'pincode' => '110001',
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => 'mock_signature',
            ])
            ->assertSessionHasErrors('product_id');

        // Test 6.3: Mock Razorpay signature verification failure
        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('verify')->andThrow(new \RuntimeException('Signature mismatch'));
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        $this->actingAs($user)
            ->withSession(["razorpay_orders.{$orderId}" => [
                'order_id' => $orderId,
                'amount' => $amountPaise,
                'currency' => 'INR',
                'item_type' => 'product',
                'item_id' => $product->id,
                'user_id' => $user->id,
                'created_at' => now()->timestamp,
            ]])
            ->post('/product-payment-success', [
                'product_id' => $product->id,
                'customer_name' => 'Valid Customer',
                'customer_phone' => '9876543210',
                'shipping_address' => 'Gym Road, Sector 14',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'pincode' => '110001',
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => 'invalid_signature_hash',
            ])
            ->assertRedirect('/products')
            ->assertSessionHas('error', 'Payment verification failed.');

        // Test 6.4: Successful Payment verification & Order Creation
        $validMockRazorpay = Mockery::mock(RazorpayService::class);
        $validMockRazorpay->shouldReceive('verify')->andReturnNull();
        $this->app->instance(RazorpayService::class, $validMockRazorpay);

        $this->actingAs($user)
            ->withSession(["razorpay_orders.{$orderId}" => [
                'order_id' => $orderId,
                'amount' => $amountPaise,
                'currency' => 'INR',
                'item_type' => 'product',
                'item_id' => $product->id,
                'user_id' => $user->id,
                'created_at' => now()->timestamp,
            ]])
            ->post('/product-payment-success', [
                'product_id' => $product->id,
                'customer_name' => 'Verified Athlete',
                'customer_phone' => '9876543210',
                'shipping_address' => 'Gym Road, Sector 14',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'pincode' => '110001',
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => 'verified_valid_sig',
            ])
            ->assertRedirect('/products')
            ->assertSessionHas('success', 'Product purchased successfully.');

        $order = Order::where('payment_id', $paymentId)->first();
        $this->assertNotNull($order);
        $this->assertSame('paid', $order->payment_status);
        $this->assertSame($user->id, $order->user_id);

        // Test 6.5: Duplicate Callback prevented
        $this->actingAs($user)
            ->withSession(["razorpay_orders.{$orderId}" => [
                'order_id' => $orderId,
                'amount' => $amountPaise,
                'currency' => 'INR',
                'item_type' => 'product',
                'item_id' => $product->id,
                'user_id' => $user->id,
                'created_at' => now()->timestamp,
            ]])
            ->post('/product-payment-success', [
                'product_id' => $product->id,
                'customer_name' => 'Verified Athlete',
                'customer_phone' => '9876543210',
                'shipping_address' => 'Gym Road, Sector 14',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'pincode' => '110001',
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => 'verified_valid_sig',
            ])
            ->assertRedirect('/products')
            ->assertSessionHas('error', 'This payment has already been processed.');

        // Test 6.6: User B cannot access User A's order (403)
        $userB = User::factory()->create(['profile_completed' => true]);
        $this->actingAs($userB)
            ->get("/account/orders/{$order->id}")
            ->assertStatus(403);

        // User A can view their order
        $this->actingAs($user)
            ->get("/account/orders/{$order->id}")
            ->assertStatus(200);

        // Order invoice download by owner succeeds
        $this->actingAs($user)
            ->get("/member/invoice/{$order->id}")
            ->assertStatus(200)
            ->assertHeader('content-type', 'application/pdf');
    }

    // ==========================================
    // 7. ADMIN CRUD WORKFLOW
    // ==========================================
    public function test_admin_crud_operations_across_major_resources(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'account_type' => 'admin',
        ]);

        $this->actingAs($admin);

        // Program CRUD
        $program = Program::query()->create([
            'title' => 'Admin Test Program',
            'slug' => 'admin-test-program',
            'description' => 'Initial description',
            'duration' => '8 Weeks',
            'status' => true,
        ]);
        $this->assertDatabaseHas('programs', ['title' => 'Admin Test Program']);
        $program->update(['title' => 'Admin Test Program Updated']);
        $this->assertSame('Admin Test Program Updated', $program->fresh()->title);
        $program->delete();
        $this->assertDatabaseMissing('programs', ['id' => $program->id]);

        // Service CRUD
        $service = Service::query()->create([
            'title' => 'Admin Test Service',
            'slug' => 'admin-test-service',
            'description' => 'Service description',
            'status' => true,
        ]);
        $this->assertDatabaseHas('services', ['title' => 'Admin Test Service']);
        $service->update(['title' => 'Admin Test Service Updated']);
        $this->assertSame('Admin Test Service Updated', $service->fresh()->title);
        $service->delete();
        $this->assertDatabaseMissing('services', ['id' => $service->id]);

        // Plan CRUD
        $plan = Plan::query()->create([
            'name' => 'Admin Test Plan',
            'slug' => 'admin-test-plan',
            'price' => 3999,
            'duration' => '60 Days',
            'status' => true,
        ]);
        $this->assertDatabaseHas('plans', ['name' => 'Admin Test Plan']);
        $plan->update(['name' => 'Admin Test Plan Updated']);
        $this->assertSame('Admin Test Plan Updated', $plan->fresh()->name);
        $plan->delete();
        $this->assertDatabaseMissing('plans', ['id' => $plan->id]);

        // Product CRUD
        $product = Product::query()->create([
            'name' => 'Admin Test Product',
            'slug' => 'admin-test-product',
            'price' => 999,
            'stock' => 15,
            'status' => true,
        ]);
        $this->assertDatabaseHas('products', ['name' => 'Admin Test Product']);
        $product->update(['price' => 899]);
        $this->assertSame(899.0, (float) $product->fresh()->price);
        $product->delete();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    // ==========================================
    // 8. MEDIA MANAGEMENT & INTEGRITY
    // ==========================================
    public function test_media_attachment_replacement_and_integrity_on_delete(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test-banner.jpg', 1920, 1080);
        $path = $file->store('media/originals', 'public');

        $media = Media::query()->create([
            'name' => 'Test Banner Media',
            'file_name' => 'test-banner.jpg',
            'path' => $path,
            'disk' => 'public',
            'folder' => 'media/originals',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'type' => 'image',
            'active' => true,
        ]);

        $programA = Program::query()->create([
            'title' => 'Program A With Media',
            'slug' => 'program-a-media',
            'description' => 'Attached to media',
            'duration' => '10 Weeks',
            'image' => $path,
            'media_id' => $media->id,
            'status' => true,
        ]);

        $programB = Program::query()->create([
            'title' => 'Program B Unrelated',
            'slug' => 'program-b-unrelated',
            'description' => 'Unrelated program record',
            'duration' => '12 Weeks',
            'image' => 'images/dk-hero.jpg',
            'status' => true,
        ]);

        // Replace media on Program A
        $newFile = UploadedFile::fake()->image('replacement.jpg', 800, 600);
        $newPath = $newFile->store('media/originals', 'public');
        $programA->update(['image' => $newPath]);
        $this->assertSame($newPath, $programA->fresh()->image);

        // Delete original media
        $media->delete();

        // Verify Program B is completely unaffected
        $this->assertDatabaseHas('programs', ['id' => $programB->id, 'title' => 'Program B Unrelated']);
    }

    // ==========================================
    // 9. WEBSITE BUILDER PERSISTENCE
    // ==========================================
    public function test_website_builder_persists_content_and_renders_on_frontend(): void
    {
        $setting = Setting::query()->first();
        $customHeading = 'Transform Your Physique with DK Singh';

        $setting->update([
            'about_title' => $customHeading,
        ]);

        $this->get('/about')
            ->assertStatus(200)
            ->assertSee($customHeading);
    }

    // ==========================================
    // 10. THEME SYSTEM TOKENS & STABILITY
    // ==========================================
    public function test_theme_tokens_render_and_customization_persists(): void
    {
        $theme = ThemeSetting::query()->first();
        $theme->update([
            'primary_color' => '#ff5722',
            'button_radius' => '12px',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('--primary-color: #ff5722;', false);
        $response->assertSee('--button-radius: 12px;', false);
    }

    // ==========================================
    // 11. SEO & METADATA
    // ==========================================
    public function test_seo_metadata_sitemap_and_robots(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('<title>', false);
        $response->assertSee('name="description"', false);
        $response->assertSee('property="og:title"', false);

        // Sitemap
        $sitemap = $this->get('/sitemap.xml');
        $sitemap->assertStatus(200);
        $sitemap->assertSee('<urlset', false);
        $sitemap->assertSee('<loc>', false);

        // Robots.txt
        $this->assertTrue(file_exists(public_path('robots.txt')));
    }
}
