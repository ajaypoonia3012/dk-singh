<?php

namespace Tests\Feature\Settings;

use App\Models\ThemeSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ThemeRenderingFoundationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array<string, string|int|float>
     */
    private array $tokens = [
        'primary_color' => '#010101',
        'secondary_color' => '#020202',
        'accent_color' => '#030303',
        'success_color' => '#040404',
        'warning_color' => '#050505',
        'danger_color' => '#060606',
        'info_color' => '#070707',
        'neutral_color' => '#080808',
        'heading_font' => 'Inter',
        'body_font' => 'Poppins',
        'font_scale' => 1.125,
        'heading_weight' => 700,
        'body_weight' => 500,
        'letter_spacing' => 0.25,
        'line_height' => 1.75,
        'primary_button_text_color' => '#090909',
        'secondary_button_background' => '#101010',
        'secondary_button_text_color' => '#111112',
        'button_radius' => '0.75rem',
        'button_shadow' => '0 1px 2px rgba(1,2,3,.4)',
        'button_hover_animation' => 'translateY(-3px)',
        'card_background' => '#121212',
        'card_radius' => '1.25rem',
        'card_shadow' => '0 2px 4px rgba(4,5,6,.4)',
        'navbar_height' => 88,
        'navbar_background' => '#131313',
        'navbar_text_color' => '#141414',
        'navbar_hover_color' => '#151515',
        'hero_overlay_color' => '#161616',
        'hero_overlay_opacity' => 55,
        'hero_gradient' => 'linear-gradient(90deg, transparent, #171717)',
        'footer_background' => '#181818',
        'footer_text_color' => '#191919',
        'footer_link_color' => '#202020',
        'input_radius' => '0.625rem',
        'input_border_color' => '#212121',
        'input_focus_color' => '#222222',
        'container_width' => 1440,
        'section_padding' => 104,
        'spacing_scale' => 1.2,
        'sidebar_width' => 360,
        'dark_background' => '#232323',
        'dark_surface' => '#242424',
        'dark_text' => '#252525',
    ];

    public function test_shared_emitter_outputs_every_theme_token_value(): void
    {
        $theme = ThemeSetting::query()->create([
            'theme_name' => 'Token coverage',
            ...$this->tokens,
        ])->refresh();

        $html = Blade::render('<x-theme.tokens :theme="$theme" />', compact('theme'));

        $expectedVariables = $this->expectedVariables();

        foreach ($expectedVariables as $variable => $value) {
            $this->assertStringContainsString("{$variable}: {$value};", $html, $variable);
        }

        $this->assertSame(1, substr_count($html, ':root'));
        $this->assertStringContainsString('data-theme-token-emitter', $html);
    }

    public function test_every_emitted_css_variable_has_a_runtime_consumer(): void
    {
        $consumerFiles = [
            resource_path('css/app.css'),
            resource_path('css/theme.css'),
            resource_path('views/layouts/app.blade.php'),
            resource_path('views/partials/navbar.blade.php'),
            resource_path('views/partials/navbar/desktop-menu.blade.php'),
            resource_path('views/partials/navbar/right-menu.blade.php'),
            resource_path('views/partials/footer.blade.php'),
            resource_path('views/home/sections/hero.blade.php'),
        ];

        $consumers = implode("\n", array_map(
            static fn (string $file): string => file_get_contents($file) ?: '',
            $consumerFiles,
        ));

        $reservedVariables = ['--hero-gradient', '--hero-overlay-color'];

        foreach (array_diff(array_keys($this->expectedVariables()), $reservedVariables) as $variable) {
            $this->assertStringContainsString("var({$variable})", $consumers, $variable);
        }
    }

    public function test_typography_tokens_and_supported_palette_fonts_are_loaded(): void
    {
        $themeCss = file_get_contents(resource_path('css/theme.css')) ?: '';
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php')) ?: '';

        foreach ([
            '--heading-font',
            '--body-font',
            '--font-scale',
            '--heading-weight',
            '--body-weight',
            '--letter-spacing',
            '--line-height',
        ] as $variable) {
            $this->assertStringContainsString("var({$variable})", $themeCss);
        }

        $this->assertStringContainsString('family=Inter:', $layout);
        $this->assertStringContainsString('family=Poppins:', $layout);
    }

    public function test_shared_button_card_and_form_components_use_theme_primitives(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-theme.button>Primary</x-theme.button>
            <x-theme.button variant="secondary">Secondary</x-theme.button>
            <x-theme.button variant="outline">Outline</x-theme.button>
            <x-theme.card>Card</x-theme.card>
            <x-theme.input name="name" />
            <x-theme.textarea name="message">Message</x-theme.textarea>
            <x-theme.select name="choice"><option>Choice</option></x-theme.select>
            <x-theme.label value="Label" />
            BLADE);

        foreach ([
            'theme-button-primary',
            'theme-button-secondary',
            'theme-button-outline',
            'theme-card',
            'theme-form-control',
            'theme-label',
        ] as $class) {
            $this->assertStringContainsString($class, $html);
        }

        $css = file_get_contents(resource_path('css/theme.css')) ?: '';
        $this->assertStringContainsString('border-radius: var(--button-radius)', $css);
        $this->assertStringContainsString('box-shadow: var(--button-shadow)', $css);
        $this->assertStringContainsString('background: var(--card-background)', $css);
        $this->assertStringContainsString('border-radius: var(--card-radius)', $css);
        $this->assertStringContainsString('border: 1px solid var(--input-border)', $css);
        $this->assertStringContainsString('border-radius: var(--input-radius)', $css);
    }

    public function test_default_emitter_preserves_existing_theme_fallbacks(): void
    {
        $html = Blade::render('<x-theme.tokens />');

        foreach ([
            '--primary-color: #facc15;',
            '--secondary-color: #111111;',
            '--accent-color: #ffffff;',
            '--heading-font: "Poppins", sans-serif;',
            '--body-font: "Poppins", sans-serif;',
            '--button-radius: 1rem;',
            '--card-radius: 1.5rem;',
            '--container-width: 1280px;',
            '--section-padding: 96px;',
        ] as $declaration) {
            $this->assertStringContainsString($declaration, $html);
        }
    }

    public function test_palette_application_and_theme_cache_invalidation_still_work(): void
    {
        $theme = ThemeSetting::query()->create(['theme_name' => 'Palette']);
        Cache::put(ThemeSetting::CACHE_KEY, 'stale');

        $theme->applyPalette('corporate-blue')->save();
        $theme->refresh();

        $this->assertSame('#2563eb', $theme->primary_color);
        $this->assertSame('Inter', $theme->heading_font);
        $this->assertSame('corporate-blue', $theme->theme_palette);
        $this->assertFalse(Cache::has(ThemeSetting::CACHE_KEY));
    }

    /**
     * @return array<string, string>
     */
    private function expectedVariables(): array
    {
        return [
            '--primary-color' => '#010101',
            '--secondary-color' => '#020202',
            '--accent-color' => '#030303',
            '--success-color' => '#040404',
            '--warning-color' => '#050505',
            '--danger-color' => '#060606',
            '--info-color' => '#070707',
            '--neutral-color' => '#080808',
            '--heading-font' => '"Inter", sans-serif',
            '--body-font' => '"Poppins", sans-serif',
            '--font-scale' => '1.125',
            '--heading-weight' => '700',
            '--body-weight' => '500',
            '--letter-spacing' => '0.25px',
            '--line-height' => '1.75',
            '--primary-button-text' => '#090909',
            '--secondary-button-bg' => '#101010',
            '--secondary-button-text' => '#111112',
            '--button-radius' => '0.75rem',
            '--button-shadow' => '0 1px 2px rgba(1,2,3,.4)',
            '--button-hover-transform' => 'translateY(-3px)',
            '--card-background' => '#121212',
            '--card-radius' => '1.25rem',
            '--card-shadow' => '0 2px 4px rgba(4,5,6,.4)',
            '--navbar-height' => '88px',
            '--navbar-background' => '#131313',
            '--navbar-text' => '#141414',
            '--navbar-hover' => '#151515',
            '--hero-overlay-color' => '#161616',
            '--hero-overlay-opacity' => '55',
            '--hero-gradient' => 'linear-gradient(90deg, transparent, #171717)',
            '--footer-background' => '#181818',
            '--footer-text' => '#191919',
            '--footer-link' => '#202020',
            '--input-radius' => '0.625rem',
            '--input-border' => '#212121',
            '--input-focus' => '#222222',
            '--container-width' => '1440px',
            '--section-padding' => '104px',
            '--spacing-scale' => '1.2',
            '--sidebar-width' => '360px',
            '--dark-background' => '#232323',
            '--dark-surface' => '#242424',
            '--dark-text' => '#252525',
        ];
    }
}
