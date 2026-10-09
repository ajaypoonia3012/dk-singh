<?php

namespace Tests\Feature\Settings;

use App\Models\Media;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ThemeSetting;
use App\Services\Builder\HeroService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HomepageThemeBindingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        Setting::query()->create([
            'site_name' => 'Theme Fitness',
            'program_label' => 'Programs',
            'programs_heading' => 'Training Programs',
            'supplements_label' => 'Supplements',
            'supplements_heading' => 'Products',
            'service_label' => 'Coaching',
            'services_heading' => 'Fitness Solutions',
            'bmi_label' => 'Health Check',
            'about_title' => 'About',
            'transformation_label' => 'Results',
            'transformations_heading' => 'Transformations',
            'testimonials_title' => 'Testimonials',
            'testimonials_heading' => 'Member Stories',
            'contact_title' => 'Get In Touch',
        ]);

        Product::query()->create([
            'name' => 'Performance Protein',
            'price' => 2499,
            'status' => true,
        ]);
    }

    public function test_every_active_homepage_section_renders_through_theme_primitives(): void
    {
        $this->createTheme('classic-gold');

        $response = $this->get('/');

        $response->assertOk();

        foreach ([
            'programs',
            'products',
            'coaching',
            'bmi',
            'about',
            'transformations',
            'testimonials',
            'contact',
        ] as $section) {
            $response->assertSee("data-theme-section=\"{$section}\"", false);
        }

        foreach ([
            'theme-section',
            'theme-section-container',
            'theme-section-heading',
            'theme-section-subtitle',
            'theme-button-primary',
            'theme-button-outline',
            'theme-card',
            'theme-form-control',
            'theme-label',
            'theme-footer',
        ] as $class) {
            $response->assertSee($class, false);
        }

        $source = file_get_contents(resource_path('views/home/index.blade.php')) ?: '';
        $this->assertStringContainsString('<x-theme.badge', $source);
    }

    public function test_hero_content_and_responsive_layout_still_render(): void
    {
        $this->createTheme('classic-gold');

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Transform Your Body, Transform Your Life')
            ->assertSee('theme-hero-overlay', false)
            ->assertSee('lg:grid-cols-2', false)
            ->assertSee('md:grid-cols-2', false)
            ->assertSee('lg:grid-cols-3', false);
    }

    public function test_hero_uses_the_setting_upload_before_the_static_fallback(): void
    {
        $this->createTheme('classic-gold');
        Setting::query()->firstOrFail()->update(['hero_image' => 'settings/custom-hero.webp']);

        $this->get('/')
            ->assertOk()
            ->assertSee(asset('storage/settings/custom-hero.webp'), false);
    }

    public function test_hero_media_library_selection_takes_precedence_over_setting_upload(): void
    {
        $this->createTheme('classic-gold');
        Setting::query()->firstOrFail()->update(['hero_image' => 'settings/direct-upload.webp']);
        $media = Media::query()->create([
            'name' => 'Selected Hero',
            'file_name' => 'selected-hero.webp',
            'path' => 'media/selected-hero.webp',
            'mime_type' => 'image/webp',
            'type' => 'image',
        ]);
        app(HeroService::class)->update(['background_media_id' => $media->id]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee(asset('storage/media/selected-hero.webp'), false)
            ->assertDontSee(asset('storage/settings/direct-upload.webp'), false);
    }

    public function test_hero_photograph_renders_without_a_visual_overlay_or_darkening(): void
    {
        $this->createTheme('corporate-blue');
        $media = Media::query()->create([
            'name' => 'Visible Hero',
            'file_name' => 'visible-hero.webp',
            'disk' => 'public',
            'path' => 'media/visible-hero.webp',
            'mime_type' => 'image/webp',
            'type' => 'image',
        ]);
        app(HeroService::class)->update(['background_media_id' => $media->id]);

        $this->get('/')
            ->assertOk()
            ->assertSee('src="'.asset('storage/media/visible-hero.webp').'"', false)
            ->assertSee('--hero-gradient: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);', false)
            ->assertSee('class="theme-media w-full h-[700px] object-cover"', false)
            ->assertSee('style="opacity: calc(var(--hero-overlay-opacity) / 100);"', false);

        $themeCss = file_get_contents(resource_path('css/theme.css')) ?: '';

        $this->assertMatchesRegularExpression(
            '/\.theme-hero-overlay\s*\{\s*background-color:\s*transparent;\s*background-image:\s*none;\s*\}/',
            $themeCss,
        );
        $this->assertStringNotContainsString('filter:', $this->heroOverlayRule($themeCss));
        $this->assertStringNotContainsString('mix-blend-mode:', $this->heroOverlayRule($themeCss));
    }

    private function heroOverlayRule(string $themeCss): string
    {
        preg_match('/\.theme-hero-overlay\s*\{[^}]*\}/', $themeCss, $matches);

        return $matches[0] ?? '';
    }

    public function test_legacy_hero_background_remains_available_as_a_final_stored_fallback(): void
    {
        $this->createTheme('classic-gold');
        app(HeroService::class)->update(['background' => 'hero/legacy-background.webp']);

        $this->get('/')
            ->assertOk()
            ->assertSee(asset('storage/hero/legacy-background.webp'), false);
    }

    #[DataProvider('transformativePalettes')]
    public function test_palette_transforms_the_same_homepage_sections(
        string $palette,
        string $primary,
        string $secondary,
    ): void {
        $this->createTheme($palette);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee("--primary-color: {$primary}", false)
            ->assertSee("--secondary-color: {$secondary}", false)
            ->assertSee('data-theme-section="programs"', false)
            ->assertSee('data-theme-section="contact"', false)
            ->assertSee('theme-footer', false);
    }

    public function test_manual_theme_overrides_reach_all_shared_homepage_consumers(): void
    {
        $theme = $this->createTheme('corporate-blue');
        $theme->update([
            'primary_color' => '#123abc',
            'card_background' => '#fefefe',
            'button_radius' => '0.5rem',
            'section_padding' => 112,
            'input_focus_color' => '#456def',
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('--primary-color: #123abc', false)
            ->assertSee('--card-background: #fefefe', false)
            ->assertSee('--button-radius: 0.5rem', false)
            ->assertSee('--section-padding: 112px', false)
            ->assertSee('--input-focus: #456def', false);
    }

    public function test_homepage_theme_changes_invalidate_the_cached_theme(): void
    {
        $theme = $this->createTheme('classic-gold');
        Cache::put(ThemeSetting::CACHE_KEY, 'stale');

        $theme->update(['primary_color' => '#abcdef']);

        $this->assertFalse(Cache::has(ThemeSetting::CACHE_KEY));
        $this->get('/')->assertOk()->assertSee('--primary-color: #abcdef', false);
    }

    public function test_active_homepage_views_do_not_contain_hardcoded_palette_utilities(): void
    {
        $files = [
            resource_path('views/home/index.blade.php'),
            resource_path('views/home/sections/hero.blade.php'),
            resource_path('views/partials/footer.blade.php'),
            resource_path('views/partials/navbar.blade.php'),
            resource_path('views/partials/navbar/desktop-menu.blade.php'),
            resource_path('views/partials/navbar/right-menu.blade.php'),
        ];

        $source = implode("\n", array_map(
            static fn (string $file): string => file_get_contents($file) ?: '',
            $files,
        ));

        $this->assertDoesNotMatchRegularExpression(
            '/(?:bg|text|border|ring)-(?:yellow|amber|black|white|gray|green|red|blue|purple)-?[^\s"\']*/',
            $source,
        );
        $this->assertDoesNotMatchRegularExpression('/#[0-9a-fA-F]{3,8}/', $source);
        $this->assertDoesNotMatchRegularExpression('/\b(?:max-w-7xl|py-16|py-20|py-24|rounded-(?:xl|2xl|3xl)|shadow-(?:sm|md|lg|xl|2xl))\b/', $source);
    }

    public function test_classic_gold_preserves_the_established_visual_token_contract(): void
    {
        $this->createTheme('classic-gold');

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('--primary-color: #facc15', false)
            ->assertSee('--secondary-color: #111111', false)
            ->assertSee('--accent-color: #ffffff', false)
            ->assertSee('--button-radius: 1rem', false)
            ->assertSee('--card-background: #ffffff', false)
            ->assertSee('--container-width: 1280px', false)
            ->assertSee('--section-padding: 96px', false)
            ->assertSee('grid md:grid-cols-2 lg:grid-cols-3', false)
            ->assertSee('grid lg:grid-cols-2', false);
    }

    private function createTheme(string $palette): ThemeSetting
    {
        $theme = ThemeSetting::query()->create([
            'theme_name' => 'Homepage Theme',
            'show_hero' => true,
            'show_programs' => true,
        ]);

        return $theme->applyPalette($palette)->save() ? $theme->refresh() : $theme;
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function transformativePalettes(): array
    {
        return [
            'corporate blue' => ['corporate-blue', '#2563eb', '#0f172a'],
            'luxury black' => ['luxury-black', '#d4af37', '#050505'],
            'emerald' => ['emerald', '#10b981', '#064e3b'],
            'purple energy' => ['purple-energy', '#8b5cf6', '#2e1065'],
        ];
    }
}
