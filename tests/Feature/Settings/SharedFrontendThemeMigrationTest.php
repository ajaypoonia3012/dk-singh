<?php

namespace Tests\Feature\Settings;

use App\Models\ThemeSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SharedFrontendThemeMigrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_guest_authentication_pages_render_with_theme_foundation(): void
    {
        ThemeSetting::query()->create([
            'theme_name' => 'Authentication theme',
            'primary_color' => '#123456',
        ]);

        foreach (['/login', '/register', '/forgot-password', '/reset-password/token?email=test@example.com'] as $uri) {
            $this->get($uri)
                ->assertOk()
                ->assertSee('data-theme-token-emitter', false)
                ->assertSee('--primary-color: #123456;', false)
                ->assertSee('theme-card', false)
                ->assertSee('theme-form-control', false);
        }
    }

    public function test_authenticated_security_pages_render_with_theme_foundation(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get('/verify-email')
            ->assertOk()
            ->assertSee('data-theme-token-emitter', false)
            ->assertSee('theme-button-primary', false);

        $this->actingAs($user)->get('/confirm-password')
            ->assertOk()
            ->assertSee('theme-section-subtitle', false)
            ->assertSee('theme-form-control', false);
    }

    public function test_breeze_components_preserve_their_apis_and_use_theme_primitives(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-primary-button>Save</x-primary-button>
            <x-secondary-button type="button">Cancel</x-secondary-button>
            <x-danger-button>Delete</x-danger-button>
            <x-text-input name="email" />
            <x-input-label for="email" value="Email" />
            <x-input-error :messages="['Invalid']" />
            <x-auth-session-status status="Updated" />
            BLADE);

        foreach ([
            'theme-button-primary',
            'theme-button-secondary',
            'theme-status-danger',
            'theme-form-control',
            'theme-label',
            'theme-text-danger',
            'theme-status-success',
        ] as $class) {
            $this->assertStringContainsString($class, $html);
        }

        $this->assertStringContainsString('name="email"', $html);
        $this->assertStringContainsString('type="button"', $html);
    }

    public function test_palette_values_are_emitted_on_authentication_pages(): void
    {
        $theme = ThemeSetting::query()->create(['theme_name' => 'Palette']);
        $theme->applyPalette('corporate-blue')->save();

        $this->get('/login')
            ->assertOk()
            ->assertSee('--primary-color: #2563eb;', false)
            ->assertSee('--heading-font: "Inter", sans-serif;', false)
            ->assertSee('theme-button-primary', false);
    }

    public function test_manual_theme_overrides_are_emitted_on_authentication_pages(): void
    {
        ThemeSetting::query()->create([
            'theme_name' => 'Manual override',
            'primary_color' => '#a1b2c3',
            'card_background' => '#d4e5f6',
            'input_focus_color' => '#102030',
        ]);

        $this->get('/login')
            ->assertOk()
            ->assertSee('--primary-color: #a1b2c3;', false)
            ->assertSee('--card-background: #d4e5f6;', false)
            ->assertSee('--input-focus: #102030;', false);
    }

    public function test_theme_cache_is_invalidated_after_an_override_is_saved(): void
    {
        $theme = ThemeSetting::query()->create(['theme_name' => 'Cached theme']);
        Cache::put(ThemeSetting::CACHE_KEY, $theme);

        $theme->update(['primary_color' => '#abcdef']);

        $this->assertFalse(Cache::has(ThemeSetting::CACHE_KEY));
        $this->assertSame('#abcdef', $theme->refresh()->primary_color);
    }

    public function test_scoped_views_do_not_reintroduce_fixed_palette_or_surface_utilities(): void
    {
        $files = [
            resource_path('views/layouts/guest.blade.php'),
            resource_path('views/layouts/navigation.blade.php'),
            ...glob(resource_path('views/auth/*.blade.php')),
            ...glob(resource_path('views/profile/*.blade.php')),
            ...glob(resource_path('views/profile/partials/*.blade.php')),
        ];

        $forbidden = '/(?:bg-(?:yellow|white|black)|text-(?:black|gray|zinc)|ring-indigo|border-gray|rounded-(?:md|lg|xl|2xl|3xl)|shadow-(?:sm|md|lg|xl|2xl))[^\s"\']*/';

        foreach ($files as $file) {
            $this->assertDoesNotMatchRegularExpression(
                $forbidden,
                file_get_contents($file) ?: '',
                $file,
            );
        }
    }
}
