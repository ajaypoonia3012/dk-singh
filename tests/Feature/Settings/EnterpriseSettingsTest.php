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
        $this->assertCount(77, (new ThemeSetting)->getFillable());
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
}
