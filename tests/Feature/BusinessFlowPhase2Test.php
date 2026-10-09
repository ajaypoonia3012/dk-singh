<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\ContactLead;
use App\Models\DietPlan;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Program;
use App\Models\Service;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Models\User;
use App\Models\WorkoutPlan;
use App\Services\ArticleCtaService;
use App\Services\RazorpayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class BusinessFlowPhase2Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        Setting::query()->create([
            'site_name' => 'DK Singh Fitness',
            'email' => 'coach@dksinghfitness.com',
            'contact_title' => 'Get in Touch',
            'program_label' => 'Programs',
        ]);

        ThemeSetting::query()->create([
            'theme_name' => 'Default',
            'show_hero' => true,
            'show_programs' => true,
            'show_services' => true,
            'show_products' => true,
        ]);

        Program::query()->create([
            'title' => '12 Week Fat Loss Transformation',
            'slug' => '12-week-fat-loss-transformation',
            'description' => 'Comprehensive fat loss program with science-backed nutrition.',
            'category' => 'Fat Loss',
            'duration' => '12 Weeks',
            'price' => 4999.00,
            'featured' => true,
            'status' => true,
            'sort_order' => 1,
        ]);

        Program::query()->create([
            'title' => 'Lean Muscle Gain Program',
            'slug' => 'lean-muscle-gain-program',
            'description' => 'Hypertrophy program for natural lifters.',
            'category' => 'Muscle Building',
            'duration' => '12 Weeks',
            'price' => 6999.00,
            'featured' => true,
            'status' => true,
            'sort_order' => 2,
        ]);

        Service::query()->create([
            'title' => 'Personal Online Coaching',
            'slug' => 'personal-online-coaching',
            'description' => 'Personalized guidance from DK Singh directly.',
            'price' => 4999.00,
            'duration' => '12 Weeks',
            'features' => ['Direct WhatsApp support', 'Custom Macro adjustments'],
            'button_text' => 'Get Started',
            'featured' => true,
            'status' => true,
            'sort_order' => 1,
        ]);

        Service::query()->create([
            'title' => 'Diet & Nutrition Coaching',
            'slug' => 'diet-nutrition-coaching',
            'description' => 'Authentic Indian macronutrient and meal planning.',
            'price' => 2999.00,
            'duration' => '8 Weeks',
            'features' => ['Custom Indian Diet Chart', 'Weekly Macro adjustments'],
            'button_text' => 'Get Started',
            'featured' => true,
            'status' => true,
            'sort_order' => 2,
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

        WorkoutPlan::query()->create([
            'title' => '1 Week Fat Loss Routine',
            'slug' => '1-week-fat-loss-routine',
            'description' => 'Full body resistance and cardio protocol.',
            'content' => '<p>Day 1: Full body circuit. Day 2: Cardio & Core.</p>',
            'difficulty' => 'Intermediate',
            'category' => 'weight_loss',
            'required_access' => 'public',
            'status' => true,
        ]);

        DietPlan::query()->create([
            'title' => 'Sustainable High Protein Diet',
            'slug' => 'sustainable-high-protein-diet',
            'description' => 'Indian macro balanced meal plan.',
            'content' => '<p>Breakfast: Poha with sprouts. Lunch: Dal, roti, paneer.</p>',
            'goal' => 'Fat Loss',
            'category' => 'weight_loss',
            'diet_type' => 'veg',
            'required_access' => 'public',
            'status' => true,
        ]);
    }

    /**
     * 1. Correct CTA destinations for representative article categories.
     */
    public function test_article_cta_service_maps_categories_to_contextual_destinations(): void
    {
        $author = User::factory()->create();
        $ctaService = app(ArticleCtaService::class);

        // Case A: Weight Loss -> 12-Week Fat Loss Program
        $weightLossCat = BlogCategory::query()->create(['name' => 'Weight Loss', 'slug' => 'weight-loss', 'status' => true]);
        $weightLossPost = BlogPost::query()->create([
            'title' => 'Science of Calorie Deficits',
            'slug' => 'science-of-calorie-deficits',
            'content' => '<p>Content about fat loss.</p>',
            'blog_category_id' => $weightLossCat->id,
            'status' => true,
            'user_id' => $author->id,
        ]);
        $cta = $ctaService->forPost($weightLossPost);
        $this->assertStringContainsString('Transformation Program', $cta['badge']);
        $this->assertStringContainsString('12-week-fat-loss-transformation', $cta['button_url']);

        // Case B: Workout & Training -> Workout Library
        $workoutCat = BlogCategory::query()->create(['name' => 'Workout & Training', 'slug' => 'workout-and-training', 'status' => true]);
        $workoutPost = BlogPost::query()->create([
            'title' => 'Push Pull Legs Guide',
            'slug' => 'push-pull-legs-guide',
            'content' => '<p>Workout splits.</p>',
            'blog_category_id' => $workoutCat->id,
            'status' => true,
            'user_id' => $author->id,
        ]);
        $cta = $ctaService->forPost($workoutPost);
        $this->assertStringContainsString('Structured Protocols', $cta['badge']);
        $this->assertStringContainsString('/fitness-hub/workouts', $cta['button_url']);

        // Case C: Muscle Building -> Lean Muscle Program
        $muscleCat = BlogCategory::query()->create(['name' => 'Muscle Building', 'slug' => 'muscle-building', 'status' => true]);
        $musclePost = BlogPost::query()->create([
            'title' => 'Hypertrophy Fundamentals',
            'slug' => 'hypertrophy-fundamentals',
            'content' => '<p>Hypertrophy volume.</p>',
            'blog_category_id' => $muscleCat->id,
            'status' => true,
            'user_id' => $author->id,
        ]);
        $cta = $ctaService->forPost($musclePost);
        $this->assertStringContainsString('Hypertrophy Program', $cta['badge']);
        $this->assertStringContainsString('lean-muscle-gain-program', $cta['button_url']);

        // Case D: Indian Diet -> Diet & Nutrition Coaching
        $dietCat = BlogCategory::query()->create(['name' => 'Indian Diet', 'slug' => 'indian-diet', 'status' => true]);
        $dietPost = BlogPost::query()->create([
            'title' => 'Vegetarian Protein Sources',
            'slug' => 'vegetarian-protein-sources',
            'content' => '<p>Paneer and lentils.</p>',
            'blog_category_id' => $dietCat->id,
            'status' => true,
            'user_id' => $author->id,
        ]);
        $cta = $ctaService->forPost($dietPost);
        $this->assertStringContainsString('Personalized Nutrition', $cta['badge']);
        $this->assertStringContainsString('diet-nutrition-coaching', $cta['button_url']);

        // Case E: Fitness / Wellness -> 1-on-1 Personal Coaching
        $fitnessCat = BlogCategory::query()->create(['name' => 'Fitness', 'slug' => 'fitness', 'status' => true]);
        $fitnessPost = BlogPost::query()->create([
            'title' => 'Daily Step Count Habits',
            'slug' => 'daily-step-count-habits',
            'content' => '<p>Cardio health.</p>',
            'blog_category_id' => $fitnessCat->id,
            'status' => true,
            'user_id' => $author->id,
        ]);
        $cta = $ctaService->forPost($fitnessPost);
        $this->assertStringContainsString('1-on-1 Mentorship', $cta['badge']);
        $this->assertStringContainsString('personal-online-coaching', $cta['button_url']);
    }

    /**
     * 2. Fallback CTA behavior for uncategorized or unmatched articles.
     */
    public function test_fallback_cta_behavior_for_uncategorized_articles(): void
    {
        $author = User::factory()->create();
        $ctaService = app(ArticleCtaService::class);

        // Case 1: Unmatched category slug falls back to default
        $miscCat = BlogCategory::query()->create(['name' => 'Archive Notes', 'slug' => 'archive-notes', 'status' => true]);
        $unmatchedPost = BlogPost::query()->create([
            'title' => 'General Fitness Insight',
            'slug' => 'general-fitness-insight',
            'content' => '<p>General fitness thoughts.</p>',
            'blog_category_id' => $miscCat->id,
            'status' => true,
            'user_id' => $author->id,
        ]);

        $cta = $ctaService->forPost($unmatchedPost);
        $this->assertEquals('DK Singh Coaching', $cta['badge']);
        $this->assertEquals(route('programs.index'), $cta['button_url']);

        // Case 2: Post with no category relationship loaded
        $inMemoryPost = new BlogPost();
        $inMemoryCta = $ctaService->forPost($inMemoryPost);
        $this->assertEquals('DK Singh Coaching', $inMemoryCta['badge']);
        $this->assertEquals(route('programs.index'), $inMemoryCta['button_url']);
    }

    /**
     * 3. Article page renders contextual CTA in actual HTTP response without broken links.
     */
    public function test_article_page_renders_contextual_cta_in_rendered_html(): void
    {
        $author = User::factory()->create();
        $dietCat = BlogCategory::query()->create(['name' => 'Nutrition', 'slug' => 'nutrition', 'status' => true]);
        $post = BlogPost::query()->create([
            'title' => 'Mastering Daily Macros',
            'slug' => 'mastering-daily-macros',
            'content' => '<h2>Understanding Protein</h2><p>Article body.</p>',
            'blog_category_id' => $dietCat->id,
            'status' => true,
            'user_id' => $author->id,
        ]);

        $response = $this->get(route('blog.show', $post->slug));
        $response->assertStatus(200);
        $response->assertSee('Personalized Nutrition');
        $response->assertSee('Custom Indian Macro & Meal Coaching');
        $response->assertSee(route('services.show', 'diet-nutrition-coaching'), false);
    }

    /**
     * 4. Free resource access remains consistent (publicly viewable without auth).
     */
    public function test_free_workout_and_diet_resources_remain_freely_accessible(): void
    {
        $workout = WorkoutPlan::first();
        $diet = DietPlan::first();

        // Workout index & show
        $this->get(route('fitness-hub.workouts.index'))->assertStatus(200);
        $workoutResponse = $this->get(route('fitness-hub.workouts.show', $workout->slug));
        $workoutResponse->assertStatus(200);
        $workoutResponse->assertSee($workout->title);
        $workoutResponse->assertSee(route('programs.show', '12-week-fat-loss-transformation'), false);

        // Diet index & show
        $this->get(route('fitness-hub.diets.index'))->assertStatus(200);
        $dietResponse = $this->get(route('fitness-hub.diets.show', $diet->slug));
        $dietResponse->assertStatus(200);
        $dietResponse->assertSee($diet->title);
        $dietResponse->assertSee(route('services.show', 'diet-nutrition-coaching'), false);
    }

    /**
     * 5. Optional lead capture from coaching/service context persists through existing ContactLead system.
     */
    public function test_service_context_survives_contact_lead_submission(): void
    {
        $service = Service::where('slug', 'diet-nutrition-coaching')->first();

        $response = $this->post(route('contact.submit'), [
            'name' => 'Vikram Patel',
            'email' => 'vikram@example.com',
            'phone' => '9876543210',
            'service' => $service->title,
            'message' => 'Interested in starting a customized Indian meal chart.',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_leads', [
            'name' => 'Vikram Patel',
            'email' => 'vikram@example.com',
            'source' => "service: {$service->title}",
            'notes' => "Interested Service: {$service->title}",
        ]);

        $lead = ContactLead::where('email', 'vikram@example.com')->first();
        $this->assertStringContainsString($service->title, $lead->message);
    }

    /**
     * 6. Program context survives the journey to Plans and checkout (Regression check for Phase 1).
     */
    public function test_program_context_survives_journey_to_plans_and_checkout(): void
    {
        $program = Program::first();
        $plan = Plan::first();
        $user = User::factory()->create(['profile_completed' => true]);

        // Plans catalog preserves program
        $plansResponse = $this->get('/plans?program=' . $program->slug);
        $plansResponse->assertStatus(200);
        $plansResponse->assertSee('Selected Program Curriculum');
        $plansResponse->assertSee($program->title);

        // Checkout preserves program
        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('createOrder')->andReturn([
            'id' => 'order_plan_mock_999',
            'amount' => 249900,
            'currency' => 'INR',
        ]);
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        $checkoutResponse = $this->actingAs($user)->get('/checkout/' . $plan->id . '?program=' . $program->slug);
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('Target Curriculum');
        $checkoutResponse->assertSee($program->title);
    }

    /**
     * 7. Product checkout remains independent of fitness profile (Regression check for Phase 1).
     */
    public function test_product_checkout_remains_independent_of_fitness_profile(): void
    {
        $product = Product::first();
        $unprofiledUser = User::factory()->create(['profile_completed' => false]);

        $mockRazorpay = Mockery::mock(RazorpayService::class);
        $mockRazorpay->shouldReceive('createOrder')->andReturn([
            'id' => 'order_prod_mock_888',
            'amount' => $product->price * 100,
            'currency' => 'INR',
        ]);
        $this->app->instance(RazorpayService::class, $mockRazorpay);

        $checkoutResponse = $this->actingAs($unprofiledUser)->get(route('product.checkout', $product->id));
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('Product Checkout');
    }
}
