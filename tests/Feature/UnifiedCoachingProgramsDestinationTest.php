<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Program;
use App\Models\Service;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnifiedCoachingProgramsDestinationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        Setting::query()->create([
            'site_name' => 'DK Singh Fitness',
            'home_label' => 'Home',
            'coaching_programs_label' => 'Coaching & Programs',
            'transformation_label' => 'Transformations',
            'blog_label' => 'Blog',
            'about_label' => 'About',
            'product_label' => 'Products',
            'contact_label' => 'Contact',
            'login_label' => 'Sign In',
            'register_label' => 'Join Now',
            'admin_panel_label' => 'Dashboard',
            'my_plan_label' => 'My Program',
            'my_orders_label' => 'Orders',
            'logout_label' => 'Sign Out',
        ]);

        ThemeSetting::query()->create([
            'theme_name' => 'Ocean Blue',
            'palette' => 'ocean-blue',
            'show_hero' => true,
        ]);
    }

    public function test_unified_coaching_programs_page_loads_with_http_200_and_sections(): void
    {
        $program = Program::query()->create([
            'title' => '12 Week Fat Loss Transformation',
            'slug' => '12-week-fat-loss-transformation',
            'description' => 'Targeted fat reduction curriculum.',
            'category' => 'Fat Loss',
            'duration' => '12 Weeks',
            'price' => 4999.00,
            'status' => true,
            'sort_order' => 1,
        ]);

        $service = Service::query()->create([
            'title' => 'Personal Online Coaching',
            'slug' => 'personal-online-coaching',
            'description' => '1-on-1 personal mentorship with Coach DK Singh.',
            'price' => 4999.00,
            'duration' => 'Month',
            'button_text' => 'Apply for 1-on-1 Coaching',
            'features' => ['Custom Nutrition', 'Form Checks'],
            'status' => 1,
            'sort_order' => 1,
        ]);

        $plan = Plan::query()->create([
            'name' => 'Pro Plan',
            'slug' => 'pro-plan',
            'description' => 'All-access training and diet membership.',
            'price' => 2999.00,
            'discount_price' => 2499.00,
            'duration' => '1 Month',
            'billing_cycle' => 'month',
            'access_type' => 'Pro Access',
            'button_text' => 'Join Pro Plan',
            'status' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get(route('coaching-programs.index'));

        $response->assertOk();
        $response->assertSee('Coaching & <span class="theme-text-primary">Programs</span>', false);
        $response->assertSee('id="programs"', false);
        $response->assertSee('id="coaching"', false);
        $response->assertSee('id="plans"', false);

        // Verify Program section & CTA
        $response->assertSee($program->title);
        $response->assertSee(route('programs.show', $program->slug));

        // Verify Coaching Service section & lead enquiry CTA
        $response->assertSee($service->title);
        $response->assertSee(route('contact', ['service' => $service->slug]));
        $response->assertSee(route('services.show', $service->slug));

        // Verify Membership Plan section & Razorpay checkout URL
        $response->assertSee($plan->name);
        $response->assertSee(url('/checkout/' . $plan->id));
    }

    public function test_standalone_routes_remain_functional_and_accessible(): void
    {
        $this->get('/programs')->assertOk();
        $this->get('/services')->assertOk();
        $this->get('/plans')->assertOk();
    }

    public function test_desktop_navbar_contains_unified_coaching_programs_destination(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('coaching-programs.index'));
        $response->assertSee('Coaching &amp; Programs', false);

        // Verify desktop top navbar does not duplicate old overlapping standalone links in main nav container
        $desktopMenuContent = view('partials.navbar.desktop-menu', [
            'setting' => Setting::first(),
        ])->render();

        $this->assertStringContainsString(route('coaching-programs.index'), $desktopMenuContent);
        $this->assertStringNotContainsString('href="/programs"', $desktopMenuContent);
        $this->assertStringNotContainsString('href="/services"', $desktopMenuContent);
        $this->assertStringNotContainsString('href="/plans"', $desktopMenuContent);
    }

    public function test_mobile_navbar_contains_unified_destination_and_dashboard_fallback(): void
    {
        // Unauthenticated mobile drawer test
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('id="mobileMenu"', false);
        $response->assertSee(route('coaching-programs.index'));
        $response->assertSee('href="/login"', false);
        $response->assertSee('Sign In');
        $response->assertSee('href="/register"', false);
        $response->assertSee('Join Now');

        // Authenticated admin mobile test
        $admin = User::factory()->create([
            'account_type' => 'admin',
        ]);

        $adminResponse = $this->actingAs($admin)->get('/');
        $adminResponse->assertOk();
        $adminResponse->assertSee('href="/admin"', false);
        $adminResponse->assertSee('Dashboard');
        $adminResponse->assertDontSee('Admin Panel');
    }

    public function test_fitness_mega_menu_and_mobile_accordion_remain_intact(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('id="fitnessMegaMenu"', false);
        $response->assertSee('Exercise & Training', false);
        $response->assertSee('Wellness', false);
        $response->assertSee('Nutrition', false);
        $response->assertSee('Lifestyle & Recovery', false);
        $response->assertSee('Featured & Explore', false);

        $response->assertSee('id="mobileFitnessAccordionToggle"', false);
        $response->assertSee('id="mobileFitnessAccordion"', false);
        $response->assertSee('Explore All Fitness');
    }

    public function test_contact_map_embed_url_cleans_iframe_and_never_renders_internal_404(): void
    {
        $setting = Setting::first();
        $rawIframe = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3560.690443219362!2d75.81822389999999!3d26.817983599999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396dc9faad788bbd%3A0xfd628648262f0d27!2sR-Square%20Fitness!5e0!3m2!1sen!2sin!4v1785958196794!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>';
        $setting->update([
            'map_embed_url' => $rawIframe,
            'address' => 'Jaipur, Rajasthan, India',
            'business_display_name' => 'Dk Singh Fitness And Nutritions',
        ]);

        $this->assertEquals(
            'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3560.690443219362!2d75.81822389999999!3d26.817983599999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396dc9faad788bbd%3A0xfd628648262f0d27!2sR-Square%20Fitness!5e0!3m2!1sen!2sin!4v1785958196794!5m2!1sen!2sin',
            $setting->fresh()->map_embed_url
        );

        $response = $this->get('/');
        $response->assertOk();
        $response->assertDontSee('src="<iframe', false);
        $response->assertSee('src="https://www.google.com/maps/embed?', false);

        // Test fallback when map_embed_url is null or empty
        $setting->update(['map_embed_url' => null]);
        $fallbackResponse = $this->get('/');
        $fallbackResponse->assertOk();
        $fallbackResponse->assertDontSee('<iframe', false);
        $fallbackResponse->assertSee('Jaipur, Rajasthan, India');
        $fallbackResponse->assertSee('Open in Google Maps');
    }

    public function test_homepage_cards_resolve_corrupted_question_marks_and_render_clean_icons(): void
    {
        \App\Models\HomepageCard::query()->create([
            'id' => 1,
            'title' => 'Personal Training',
            'subtitle' => 'Train with DK Singh',
            'icon' => '????',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        \App\Models\HomepageCard::query()->create([
            'id' => 2,
            'title' => 'Weight Loss Programs',
            'subtitle' => 'Lose Fat Naturally',
            'icon' => '????',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        \App\Models\HomepageCard::query()->create([
            'id' => 3,
            'title' => 'Muscle Building',
            'subtitle' => 'Build Lean Muscle',
            'icon' => '???????',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        \App\Models\HomepageCard::query()->create([
            'id' => 4,
            'title' => 'Nutrition Coaching',
            'subtitle' => 'Eat Smart',
            'icon' => '????',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $card1 = \App\Models\HomepageCard::find(1);
        $this->assertEquals('🏋️', $card1->icon);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertDontSee('????');
        $response->assertDontSee('???????');
        $response->assertSee('Weight Loss Programs');
        $response->assertSee('Personal Training');
        $response->assertSee('Muscle Building');
        $response->assertSee('Nutrition Coaching');
    }
}
