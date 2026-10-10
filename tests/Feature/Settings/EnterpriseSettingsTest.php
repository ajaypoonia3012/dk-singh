<?php

namespace Tests\Feature\Settings;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\SettingResource\Pages\EditSetting;
use App\Filament\Resources\ThemeSettingResource;
use App\Filament\Resources\ThemeSettingResource\Pages\EditThemeSetting;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\Livewire;
use Tests\TestCase;

class EnterpriseSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_enterprise_configuration_uses_grouped_json_columns(): void
    {
        $this->assertTrue(Schema::hasColumn('settings', 'enterprise_configuration'));
        $this->assertTrue(Schema::hasColumn('theme_settings', 'design_configuration'));
        $this->assertFalse(Schema::hasColumn('settings', 'dark_logo'));
        $this->assertFalse(Schema::hasColumn('theme_settings', 'success_color'));

        $this->assertCount(96, (new Setting)->getFillable());
        $this->assertCount(98, (new ThemeSetting)->getFillable());
    }

    public function test_grouped_configuration_preserves_field_level_access_and_native_types(): void
    {
        $setting = Setting::query()->create([
            'site_name' => 'Dynamic Fitness',
            'fitness_hub_label' => 'Knowledge Centre',
            'maintenance_enabled' => true,
        ])->refresh();

        $theme = ThemeSetting::query()->create([
            'theme_name' => 'Enterprise',
            'font_scale' => '1.25',
            'navbar_height' => '88',
            'animations_enabled' => false,
            'custom_css' => str_repeat('.utility{}', 1000),
        ])->refresh();

        $this->assertSame('Knowledge Centre', $setting->fitness_hub_label);
        $this->assertTrue($setting->maintenance_enabled);
        $this->assertSame(1.25, $theme->font_scale);
        $this->assertSame(88, $theme->navbar_height);
        $this->assertFalse($theme->animations_enabled);
        $this->assertIsArray($theme->design_configuration);
        $this->assertStringContainsString('.utility{}', $theme->custom_css);
    }

    public function test_setting_and_theme_writes_invalidate_cached_configuration(): void
    {
        Cache::put(Setting::CACHE_KEY, 'stale');
        Setting::query()->create(['site_name' => 'Dynamic Fitness']);
        $this->assertFalse(Cache::has(Setting::CACHE_KEY));

        Cache::put(ThemeSetting::CACHE_KEY, 'stale');
        ThemeSetting::query()->create(['theme_name' => 'Enterprise']);
        $this->assertFalse(Cache::has(ThemeSetting::CACHE_KEY));
    }

    public function test_filament_setting_edit_persists_relational_and_grouped_values(): void
    {
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $setting = Setting::query()->create([
            'site_name' => 'Before',
            'meta_keywords' => 'fitness, coaching',
        ]);
        Cache::put(Setting::CACHE_KEY, 'stale');

        Livewire::test(EditSetting::class, ['record' => $setting->getRouteKey()])
            ->fillForm([
                'site_name' => 'After',
                'fitness_hub_label' => 'Training Library',
                'maintenance_enabled' => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $setting->refresh();

        $this->assertSame('After', $setting->site_name);
        $this->assertSame('fitness, coaching', $setting->meta_keywords);
        $this->assertSame('Training Library', $setting->fitness_hub_label);
        $this->assertTrue($setting->maintenance_enabled);
        $this->assertSame('Training Library', $setting->enterprise_configuration['fitness_hub_label']);
        $this->assertFalse(Cache::has(Setting::CACHE_KEY));
    }

    public function test_filament_theme_edit_persists_relational_and_grouped_values(): void
    {
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $theme = ThemeSetting::query()->create(['theme_name' => 'Before']);
        Cache::put(ThemeSetting::CACHE_KEY, 'stale');

        Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->fillForm([
                'theme_name' => 'After',
                'primary_color' => '#123456',
                'heading_font' => 'Inter',
                'navbar_height' => 88,
                'animations_enabled' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $theme->refresh();

        $this->assertSame('After', $theme->theme_name);
        $this->assertSame('#123456', $theme->primary_color);
        $this->assertSame('Inter', $theme->heading_font);
        $this->assertSame(88, $theme->navbar_height);
        $this->assertFalse($theme->animations_enabled);
        $this->assertSame('Inter', $theme->design_configuration['heading_font']);
        $this->assertFalse(Cache::has(ThemeSetting::CACHE_KEY));
    }

    public function test_shared_layout_uses_database_branding_and_design_tokens(): void
    {
        $this->withoutVite();

        $setting = Setting::query()->create([
            'site_name' => 'Dynamic Fitness',
            'site_tagline' => 'Built from settings',
            'fitness_hub_label' => 'Knowledge Centre',
            'copyright_text' => 'Dynamic Fitness Inc.',
        ]);
        $theme = ThemeSetting::query()->create([
            'theme_name' => 'Enterprise',
            'primary_color' => '#123456',
            'heading_font' => 'Poppins',
            'body_font' => 'Poppins',
        ]);

        $html = view('layouts.app', compact('setting', 'theme'))->render();

        $this->assertStringContainsString('Dynamic Fitness', $html);
        $this->assertStringContainsString('Knowledge Centre', $html);
        $this->assertStringContainsString('--primary-color: #123456', $html);
        $this->assertStringNotContainsString('$themeSetting', $html);
    }

    public function test_enterprise_filament_forms_build_successfully(): void
    {
        $livewire = new class extends Component implements HasForms
        {
            use InteractsWithForms;

            public function render(): string
            {
                return '';
            }
        };

        $this->assertNotEmpty(SettingResource::form(Form::make($livewire))->getComponents());
        $this->assertNotEmpty(ThemeSettingResource::form(Form::make($livewire))->getComponents());
    }

    public function test_powder_promo_banner_defaults_and_mutation_in_filament_admin(): void
    {
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $setting = Setting::query()->create(['site_name' => 'DK Singh Fitness']);

        $this->assertFalse($setting->powder_promo_enabled);
        $this->assertSame('POWDER15', $setting->powder_promo_code);
        $this->assertSame('HERBAL & WELLNESS', $setting->powder_promo_discount_text);

        Livewire::test(EditSetting::class, ['record' => $setting->getRouteKey()])
            ->fillForm([
                'powder_promo_enabled' => true,
                'powder_promo_badge' => 'EXCLUSIVE MEGA SALE',
                'powder_promo_discount_text' => 'FLAT 25% OFF',
                'powder_promo_code' => 'DKPOWDER25',
                'powder_promo_text' => 'Get 25% discount on all Ayurvedic Weight Loss & Protein Powders!',
                'powder_promo_button_text' => 'Claim Powder Discount',
                'powder_promo_button_link' => '/products',
                'powder_promo_theme' => 'emerald-wellness',
                'powder_promo_placement' => 'all_plus_product_card',
                'powder_promo_dismissible' => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $setting->refresh();

        $this->assertTrue($setting->powder_promo_enabled);
        $this->assertSame('EXCLUSIVE MEGA SALE', $setting->powder_promo_badge);
        $this->assertSame('FLAT 25% OFF', $setting->powder_promo_discount_text);
        $this->assertSame('DKPOWDER25', $setting->powder_promo_code);
        $this->assertSame('Claim Powder Discount', $setting->powder_promo_button_text);
        $this->assertSame('emerald-wellness', $setting->powder_promo_theme);
    }

    public function test_powder_promo_banner_renders_in_public_layout_when_enabled_and_hides_when_disabled(): void
    {
        $this->withoutVite();

        $setting = Setting::query()->create([
            'site_name' => 'DK Singh Fitness',
            'powder_promo_enabled' => true,
            'powder_promo_badge' => 'SPECIAL POWDER DISCOUNT',
            'powder_promo_discount_text' => 'FLAT 20% OFF',
            'powder_promo_code' => 'POWDER20',
            'powder_promo_text' => 'Exclusive savings on high-quality herbal powders.',
            'powder_promo_button_text' => 'Order Powder Now',
        ]);
        $this->assertSame('FLAT 20% OFF', $setting->powder_promo_discount_text);
        $theme = ThemeSetting::query()->create(['theme_name' => 'Default']);

        $html = view('layouts.app', compact('setting', 'theme'))->render();

        $this->assertStringContainsString('SPECIAL POWDER DISCOUNT', $html);
        $this->assertStringContainsString('FLAT 20% OFF', $html);
        $this->assertStringContainsString('POWDER20', $html);
        $this->assertStringContainsString('Exclusive savings on high-quality herbal powders.', $html);
        $this->assertStringContainsString('Order Powder Now', $html);

        // When disabled
        $setting->powder_promo_enabled = false;
        $setting->save();

        $htmlDisabled = view('layouts.app', compact('setting', 'theme'))->render();
        $this->assertStringNotContainsString('SPECIAL POWDER DISCOUNT', $htmlDisabled);
        $this->assertStringNotContainsString('POWDER20', $htmlDisabled);
    }

    public function test_powder_promo_cta_button_link_validation_rejects_unsafe_destinations_and_accepts_valid_paths(): void
    {
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $setting = Setting::query()->create(['site_name' => 'DK Singh Fitness']);

        // Test rejecting javascript: scheme
        Livewire::test(EditSetting::class, ['record' => $setting->getRouteKey()])
            ->fillForm([
                'powder_promo_button_link' => 'javascript:alert(1)',
            ])
            ->call('save')
            ->assertHasFormErrors(['powder_promo_button_link']);

        // Test rejecting protocol-relative // URL
        Livewire::test(EditSetting::class, ['record' => $setting->getRouteKey()])
            ->fillForm([
                'powder_promo_button_link' => '//malicious-site.test/phishing',
            ])
            ->call('save')
            ->assertHasFormErrors(['powder_promo_button_link']);

        // Test rejecting insecure http: URL
        Livewire::test(EditSetting::class, ['record' => $setting->getRouteKey()])
            ->fillForm([
                'powder_promo_button_link' => 'http://insecure.test/products',
            ])
            ->call('save')
            ->assertHasFormErrors(['powder_promo_button_link']);

        // Test accepting valid relative path
        Livewire::test(EditSetting::class, ['record' => $setting->getRouteKey()])
            ->fillForm([
                'powder_promo_button_link' => '/products/herbal-blend',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $setting->refresh();
        $this->assertSame('/products/herbal-blend', $setting->powder_promo_button_link);

        // Test accepting valid https URL
        Livewire::test(EditSetting::class, ['record' => $setting->getRouteKey()])
            ->fillForm([
                'powder_promo_button_link' => 'https://dksinghfitness.com/store',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $setting->refresh();
        $this->assertSame('https://dksinghfitness.com/store', $setting->powder_promo_button_link);
    }

    public function test_powder_promo_components_render_honest_copy_and_do_not_render_unbacked_claims(): void
    {
        $setting = Setting::query()->create([
            'site_name' => 'DK Singh Fitness',
            'powder_promo_enabled' => true,
        ]);

        $cardHtml = view('components.promo.powder-product-card', compact('setting'))->render();

        $this->assertStringNotContainsString('Lab Verified Potency', $cardHtml);
        $this->assertStringContainsString('Product Range', $cardHtml);
        $this->assertStringNotContainsString('Apply this promo code at checkout', $cardHtml);

        $bannerHtml = view('components.promo.powder-banner', compact('setting'))->render();
        $this->assertStringNotContainsString('discount code', strtolower($bannerHtml));
    }

    public function test_product_payment_page_does_not_contain_misleading_promo_prompts(): void
    {
        $this->withoutVite();

        $user = User::factory()->create();
        $product = \App\Models\Product::create([
            'name' => 'Ayurvedic Weight Loss Powder',
            'slug' => 'ayurvedic-weight-loss-powder',
            'price' => 1499,
            'description' => 'Ayurvedic wellness powder.',
            'status' => true,
        ]);

        $setting = Setting::query()->create([
            'site_name' => 'DK Singh Fitness',
            'powder_promo_enabled' => true,
            'powder_promo_code' => 'POWDER15',
        ]);
        $theme = ThemeSetting::query()->create(['theme_name' => 'Default']);

        $response = $this->actingAs($user)->get(route('product.checkout', $product->id));

        $response->assertOk();
        $response->assertSee('Pay &#8377;1,499', false);
        $response->assertDontSee('Coupon Code:');
        $response->assertDontSee('Apply this promo code at checkout');
    }
}
