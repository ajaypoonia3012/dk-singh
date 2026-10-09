<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Exercise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FitnessMegaMenuAndWellnessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_fitness_wellness_hub_returns_successful_response(): void
    {
        $category = BlogCategory::create(['name' => 'Lifestyle & Wellness', 'slug' => 'lifestyle-and-wellness']);

        BlogPost::create([
            'title' => 'Sleep: The Ultimate Recovery Protocol for Fat Loss',
            'slug' => 'sleep-the-ultimate-recovery-protocol',
            'content' => 'Comprehensive evidence on circadian rhythms and recovery.',
            'excerpt' => 'Circadian sleep optimization.',
            'category_id' => $category->id,
            'blog_category_id' => $category->id,
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get(route('fitness.wellness'));

        $response->assertStatus(200);
        $response->assertSee('Wellness, Mind & Restorative Recovery Hub | DK Singh Fitness', false);
        $response->assertSee('Mind, Recovery, Habits & Sustainable Vitality', false);
        $response->assertSee('Explore Wellness Topics');
        $response->assertSee('Mind & Mental Well-Being', false);
        $response->assertSee('Sleep & Recovery', false);
        $response->assertSee('Stress Management & Resilience', false);
        $response->assertSee('Healthy Habits & Mindfulness', false);
        $response->assertSee('Active Lifestyle & Longevity', false);
    }

    public function test_fitness_wellness_has_full_seo_metadata_and_breadcrumbs(): void
    {
        $response = $this->get(route('fitness.wellness'));

        $response->assertStatus(200);
        $response->assertSee('<link rel="canonical" href="'.route('fitness.wellness').'">', false);
        $response->assertSee('name="description"', false);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('property="og:url" content="'.route('fitness.wellness').'"', false);
        $response->assertSee('@type": "CollectionPage"', false);
        $response->assertSee('Home');
        $response->assertSee('Fitness');
        $response->assertSee('Wellness');
    }

    public function test_fitness_hub_exposes_wellness(): void
    {
        $response = $this->get(route('fitness.index'));

        $response->assertStatus(200);
        $response->assertSee('Wellness Hub');
        $response->assertSee(route('fitness.wellness'));
    }

    public function test_desktop_mega_menu_is_rendered_with_topic_columns(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('id="fitnessMegaMenu"', false);
        $response->assertSee('Exercise & Training', false);
        $response->assertSee('Wellness', false);
        $response->assertSee('Nutrition', false);
        $response->assertSee('Lifestyle & Recovery', false);
        $response->assertSee('Featured & Explore', false);
        $response->assertSee(route('fitness.wellness'));
        $response->assertSee(route('fitness.exercise'));
        $response->assertSee(route('fitness.cardio'));
        $response->assertSee(route('fitness.strength-training'));
        $response->assertSee(route('fitness.yoga'));
        $response->assertSee(route('fitness.holistic-fitness'));
        $response->assertSee(route('fitness.exercise-library'));
    }

    public function test_mobile_fitness_accordion_is_present(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('id="mobileFitnessAccordionToggle"', false);
        $response->assertSee('id="mobileFitnessAccordion"', false);
        $response->assertSee('Explore All Fitness');
    }

    public function test_sitemap_includes_fitness_wellness(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertStatus(200);
        $response->assertSee('<loc>'.route('fitness.wellness').'</loc>', false);
        $response->assertSee('<loc>'.route('fitness.index').'</loc>', false);
        $response->assertSee('<loc>'.route('fitness.exercise').'</loc>', false);
    }

    public function test_products_functionality_is_completely_preserved(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        $homeResponse = $this->get('/');
        $homeResponse->assertSee(route('products.index'));

        $sitemapResponse = $this->get(route('sitemap'));
        $sitemapResponse->assertSee('<loc>'.url('/products').'</loc>', false);

        // Explicit Product Detail, Checkout & Payment regression check
        $product = \App\Models\Product::create([
            'name' => 'DK Premium Whey Isolate',
            'slug' => 'dk-premium-whey-isolate',
            'price' => 2999,
            'description' => 'Ultra-pure microfiltered whey protein isolate.',
            'status' => true,
        ]);

        $detailResponse = $this->get(route('products.show', $product->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('DK Premium Whey Isolate');

        // Guest is redirected to login for checkout
        $guestCheckout = $this->get(route('product.checkout', $product->id));
        $guestCheckout->assertRedirect('/login');

        // Authenticated user with completed profile can access checkout and payment flow
        $user = \App\Models\User::factory()->create(['profile_completed' => true]);

        $checkoutResponse = $this->actingAs($user)->get(route('product.checkout', $product->id));
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('Product Checkout');

        $mockRazorpay = \Mockery::mock(\App\Services\RazorpayService::class);
        $mockRazorpay->shouldReceive('createOrder')->andReturn([
            'id' => 'order_test_123',
            'amount' => $product->price * 100,
            'currency' => 'INR',
        ]);
        $this->app->instance(\App\Services\RazorpayService::class, $mockRazorpay);

        $paymentResponse = $this->actingAs($user)->get(route('product.payment', $product->id));
        $paymentResponse->assertStatus(200);
        $paymentResponse->assertSee('Product Payment');
        $paymentResponse->assertSee('customer_name');
        $paymentResponse->assertSee('shipping_address');
        $paymentResponse->assertSee('rzp-button');
    }

    public function test_mega_menu_anchors_all_exist_on_wellness_page(): void
    {
        $response = $this->get(route('fitness.wellness'));
        $response->assertStatus(200);

        $anchors = [
            'mental-wellbeing',
            'sleep-recovery',
            'stress-management',
            'healthy-habits',
            'longevity',
        ];

        foreach ($anchors as $anchor) {
            $response->assertSee('id="'.$anchor.'"', false);
        }
    }
}
