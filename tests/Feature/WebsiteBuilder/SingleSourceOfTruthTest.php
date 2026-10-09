<?php

namespace Tests\Feature\WebsiteBuilder;

use App\Livewire\Builder\WebsiteBuilder;
use App\Models\HeroSetting;
use App\Models\HomepageCard;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Models\User;
use App\Models\WebsiteSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Tests\TestCase;

class SingleSourceOfTruthTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->admin = User::factory()->create([
            'account_type' => 'admin',
            'is_admin' => true,
        ]);

        Setting::query()->create([
            'site_name' => 'DK Singh Fitness & Nutrition',
            'hero_title' => 'Transform Your Body, Transform Your Life',
            'hero_subtitle' => 'Expert fitness coaching and personalized nutrition plans.',
            'cta_button_text' => 'Start Your Journey',
            'cta_button_link' => '/plans',
            'email' => 'admin@dksinghfitness.com',
            'phone' => '+91 99999 88888',
        ]);

        HeroSetting::query()->create([
            'heading' => 'Transform Your Body, Transform Your Life',
            'subheading' => 'Expert fitness coaching and personalized nutrition plans.',
            'button_text' => 'Start Your Journey',
            'button_link' => '/plans',
            'overlay_color' => '#000000',
            'overlay_opacity' => 40,
            'template' => 'modern',
            'enabled' => true,
        ]);

        ThemeSetting::query()->create([
            'theme_name' => 'Default',
            'primary_color' => '#facc15',
            'secondary_color' => '#111111',
            'accent_color' => '#ffffff',
            'heading_font' => 'Poppins',
            'body_font' => 'Poppins',
            'button_radius' => '1rem',
            'card_radius' => '1.5rem',
            'show_hero' => true,
            'show_programs' => true,
            'show_services' => true,
            'show_products' => true,
            'show_blogs' => true,
            'show_transformations' => true,
            'show_testimonials' => true,
            'show_contact' => true,
        ]);

        WebsiteSection::query()->create([
            'page' => 'home',
            'section' => 'hero',
            'title' => 'Hero',
            'enabled' => true,
            'sort_order' => 1,
            'settings' => [],
            'template' => 'default',
        ]);

        WebsiteSection::query()->create([
            'page' => 'home',
            'section' => 'programs',
            'title' => 'Programs',
            'enabled' => true,
            'sort_order' => 2,
            'settings' => [],
            'template' => 'default',
        ]);
    }

    public function test_website_builder_general_settings_persists_to_setting_model_and_reflects_in_public_frontend(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(WebsiteBuilder::class)
            ->set('general.site_name', 'DK Singh Elite Performance')
            ->set('general.site_tagline', 'The Ultimate Transformation Standard')
            ->set('general.email', 'vip@dksinghfitness.com')
            ->set('general.phone', '+91 11111 22222')
            ->call('saveGeneral')
            ->assertHasNoErrors()
            ->assertNotified();

        $this->assertDatabaseHas('settings', [
            'site_name' => 'DK Singh Elite Performance',
            'site_tagline' => 'The Ultimate Transformation Standard',
            'email' => 'vip@dksinghfitness.com',
            'phone' => '+91 11111 22222',
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('DK Singh Elite Performance');
    }

    public function test_bidirectional_hero_sync_between_setting_and_hero_setting(): void
    {
        $setting = Setting::first();
        $setting->update([
            'hero_title' => 'Crush Your Fitness Plateau',
            'hero_subtitle' => 'Proven science-backed hypertrophy & fat loss protocols.',
        ]);

        $hero = HeroSetting::first();
        $this->assertSame('Crush Your Fitness Plateau', $hero->heading);
        $this->assertSame('Proven science-backed hypertrophy & fat loss protocols.', $hero->subheading);

        $hero->update([
            'button_text' => 'Claim Your Protocol',
            'button_link' => '/vip-coaching',
        ]);

        $setting->refresh();
        $this->assertSame('Claim Your Protocol', $setting->cta_button_text);
        $this->assertSame('/vip-coaching', $setting->cta_button_link);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Crush Your Fitness Plateau');
        $response->assertSee('Claim Your Protocol');
    }

    public function test_bidirectional_section_visibility_sync_between_website_section_and_theme_setting(): void
    {
        $heroSection = WebsiteSection::where('section', 'hero')->first();
        $heroSection->update(['enabled' => false]);

        $theme = ThemeSetting::first();
        $this->assertFalse($theme->show_hero);

        $theme->update(['show_programs' => false]);

        $programsSection = WebsiteSection::where('section', 'programs')->first();
        $this->assertFalse($programsSection->enabled);
    }

    public function test_website_builder_toggle_section_action_syncs_with_theme_setting(): void
    {
        $this->actingAs($this->admin);

        $heroSection = WebsiteSection::where('section', 'hero')->first();

        Livewire::test(WebsiteBuilder::class)
            ->call('toggleSection', $heroSection->id)
            ->assertNotified();

        $heroSection->refresh();
        $this->assertFalse($heroSection->enabled);

        $theme = ThemeSetting::first();
        $this->assertFalse($theme->show_hero);

        Livewire::test(WebsiteBuilder::class)
            ->call('toggleSection', $heroSection->id)
            ->assertNotified();

        $heroSection->refresh();
        $this->assertTrue($heroSection->enabled);

        $theme->refresh();
        $this->assertTrue($theme->show_hero);
    }

    public function test_theme_preset_application_persists_canonical_theme_setting_and_reflects_in_public_frontend(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(WebsiteBuilder::class)
            ->call('applyThemePreset', 'bold-performance')
            ->assertNotified();

        $theme = ThemeSetting::first();
        $this->assertSame('#ef4444', $theme->primary_color);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('#ef4444');
    }

    public function test_website_builder_theme_settings_manual_save_persists_and_emits_styles(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(WebsiteBuilder::class)
            ->set('theme.primary_color', '#10b981')
            ->set('theme.secondary_color', '#064e3b')
            ->set('theme.accent_color', '#ecfdf5')
            ->set('theme.heading_font', 'Montserrat')
            ->set('theme.body_font', 'Inter')
            ->set('theme.button_radius', '0.5rem')
            ->set('theme.card_radius', '1rem')
            ->call('saveThemeSettings')
            ->assertHasNoErrors()
            ->assertNotified();

        $theme = ThemeSetting::first();
        $this->assertSame('#10b981', $theme->primary_color);
        $this->assertSame('Montserrat', $theme->heading_font);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('#10b981');
    }

    public function test_homepage_card_single_source_of_truth(): void
    {
        $this->actingAs($this->admin);

        $card = HomepageCard::query()->create([
            'title' => 'VIP 1-on-1 Coaching',
            'subtitle' => 'Strict accountability',
            'description' => 'Direct WhatsApp access with DK Singh.',
            'button_text' => 'Join Now',
            'button_link' => 'https://dksinghfitness.com/plans',
            'icon' => '🔥',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Livewire::test(WebsiteBuilder::class)
            ->call('loadHomepageCard', $card->id)
            ->set('homepageCard.title', 'Updated VIP 1-on-1 Coaching')
            ->call('saveHomepageCard')
            ->assertHasNoErrors()
            ->assertNotified();

        $this->assertDatabaseHas('homepage_cards', [
            'id' => $card->id,
            'title' => 'Updated VIP 1-on-1 Coaching',
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Updated VIP 1-on-1 Coaching');
    }

    public function test_unauthorized_user_cannot_mutate_settings_or_theme_in_website_builder(): void
    {
        $regularUser = User::factory()->create([
            'account_type' => 'customer',
            'is_admin' => false,
        ]);

        $this->actingAs($regularUser);

        Livewire::test(WebsiteBuilder::class)
            ->call('saveGeneral')
            ->assertForbidden();
    }
}
