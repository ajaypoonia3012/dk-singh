<?php

namespace Tests\Feature\Settings;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ThemeSettingResource;
use App\Models\Setting;
use App\Models\ThemeSetting;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Tests\TestCase;

class EnterpriseSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_enterprise_setting_and_theme_columns_are_available(): void
    {
        $this->assertTrue(Schema::hasColumns('settings', [
            'dark_logo',
            'apple_touch_icon',
            'support_email',
            'legal_business_name',
            'copyright_text',
            'fitness_hub_label',
            'supplements_label',
            'maintenance_enabled',
        ]));

        $this->assertTrue(Schema::hasColumns('theme_settings', [
            'success_color',
            'heading_font',
            'button_radius',
            'card_background',
            'navbar_background',
            'footer_background',
            'dark_mode_enabled',
            'custom_css',
            'custom_js',
        ]));

        $this->assertCount(96, (new Setting)->getFillable());
        $this->assertCount(77, (new ThemeSetting)->getFillable());
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
}
