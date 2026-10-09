<?php

namespace Tests\Feature\Settings;

use App\Filament\Resources\ThemeSettingResource\Pages\EditThemeSetting;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Models\User;
use App\Services\ThemePaletteRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ThemePaletteSystemTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'account_type' => 'admin',
            'is_admin' => true,
        ]);
    }

    public function test_registry_contains_complete_default_palettes(): void
    {
        $registry = app(ThemePaletteRegistry::class);

        $this->assertCount(10, $registry->all());
        $this->assertSame([
            'classic-gold',
            'modern-dark',
            'minimal-white',
            'corporate-blue',
            'fitness-red',
            'ocean-blue',
            'luxury-black',
            'emerald',
            'purple-energy',
            'sunset-orange',
        ], array_keys($registry->all()));

        foreach ($registry->all() as $palette) {
            $this->assertCount(3, $palette['preview']);
            $this->assertArrayHasKey('primary_color', $palette['tokens']);
            $this->assertArrayHasKey('heading_font', $palette['tokens']);
            $this->assertArrayHasKey('button_radius', $palette['tokens']);
            $this->assertArrayHasKey('section_padding', $palette['tokens']);
            $this->assertArrayHasKey('animations_enabled', $palette['tokens']);
        }
    }

    public function test_model_applies_palette_to_existing_theme_attributes_and_persists_it(): void
    {
        $theme = ThemeSetting::query()->create(['theme_name' => 'Default']);

        $theme->applyPalette('corporate-blue')->save();
        $theme->refresh();

        $this->assertSame('corporate-blue', $theme->theme_palette);
        $this->assertSame('#2563eb', $theme->primary_color);
        $this->assertSame('#0f172a', $theme->secondary_color);
        $this->assertSame('Inter', $theme->heading_font);
        $this->assertSame('corporate-blue', $theme->design_configuration['theme_palette']);
    }

    public function test_filament_selection_populates_fields_and_normal_save_pipeline_persists_them(): void
    {
        $this->actingAs($this->admin);
        $theme = ThemeSetting::query()->create(['theme_name' => 'Default']);

        Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->set('data.theme_palette', 'modern-dark')
            ->assertFormSet([
                'theme_palette' => 'modern-dark',
                'primary_color' => '#ffd400',
                'accent_color' => '#18181b',
                'dark_mode_enabled' => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $theme->refresh();

        $this->assertSame('modern-dark', $theme->theme_palette);
        $this->assertSame('#ffd400', $theme->primary_color);
        $this->assertSame('#18181b', $theme->accent_color);
        $this->assertTrue($theme->dark_mode_enabled);
    }

    public function test_manual_override_after_palette_changes_only_the_overridden_value(): void
    {
        $this->actingAs($this->admin);
        $theme = ThemeSetting::query()->create(['theme_name' => 'Default']);

        Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->set('data.theme_palette', 'modern-dark')
            ->set('data.primary_color', '#ff0000')
            ->call('save')
            ->assertHasNoFormErrors();

        $theme->refresh();

        $this->assertSame('#ff0000', $theme->primary_color);
        $this->assertSame('#18181b', $theme->accent_color);
        $this->assertSame('#111111', $theme->navbar_background);
    }

    public function test_selecting_another_palette_reapplies_all_applicable_tokens(): void
    {
        $this->actingAs($this->admin);
        $theme = ThemeSetting::query()->create(['theme_name' => 'Default']);

        Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->set('data.theme_palette', 'modern-dark')
            ->set('data.primary_color', '#ff0000')
            ->set('data.theme_palette', 'corporate-blue')
            ->assertFormSet([
                'theme_palette' => 'corporate-blue',
                'primary_color' => '#2563eb',
                'secondary_color' => '#0f172a',
                'heading_font' => 'Inter',
                'dark_mode_enabled' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('#2563eb', $theme->fresh()->primary_color);
    }

    public function test_existing_theme_without_palette_remains_backward_compatible(): void
    {
        $theme = ThemeSetting::query()->create([
            'theme_name' => 'Existing',
            'primary_color' => '#123456',
            'heading_font' => 'Poppins',
        ])->refresh();

        $this->assertNull($theme->theme_palette);
        $this->assertSame('#123456', $theme->primary_color);
        $this->assertSame('Poppins', $theme->heading_font);
    }

    public function test_frontend_continues_rendering_existing_theme_attributes_after_palette_save(): void
    {
        $this->withoutVite();

        $setting = Setting::query()->create(['site_name' => 'Palette Fitness']);
        $theme = ThemeSetting::query()->create(['theme_name' => 'Default']);
        $theme->applyPalette('emerald')->save();

        $html = view('layouts.app', compact('setting', 'theme'))->render();

        $this->assertStringContainsString('--primary-color: #10b981', $html);
        $this->assertStringContainsString('--secondary-color: #064e3b', $html);
        $this->assertStringContainsString('--button-radius: 1rem', $html);
    }
}
