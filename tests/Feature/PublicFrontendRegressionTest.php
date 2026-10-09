<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\ThemeSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Tests\TestCase;

class PublicFrontendRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        Setting::query()->create([
            'site_name' => 'DK Singh Fitness',
            'program_label' => 'Programs',
        ]);

        $theme = ThemeSetting::query()->create([
            'theme_name' => 'Public regression',
            'show_hero' => true,
        ]);
        $theme->applyPalette('classic-gold')->save();
    }

    public function test_public_program_routes_are_registered_without_affecting_filament_routes(): void
    {
        $this->assertTrue(Route::has('programs.index'));
        $this->assertTrue(Route::has('programs.show'));
        $this->assertSame('/programs', route('programs.index', absolute: false));
        $this->assertTrue(Route::has('filament.admin.resources.programs.index'));
    }

    #[DataProvider('publicRouteProvider')]
    public function test_public_navigation_destinations_do_not_return_not_found(string $uri): void
    {
        $this->assertNotSame(404, $this->get($uri)->getStatusCode(), $uri);
    }

    public function test_public_blade_sources_are_valid_utf8_without_mojibake(): void
    {
        foreach ($this->publicBladeFiles() as $file) {
            $source = file_get_contents($file) ?: '';

            $this->assertTrue(mb_check_encoding($source, 'UTF-8'), $file);
            $this->assertDoesNotMatchRegularExpression('/(?:â|ð|Γ|Ã|Â|�)/u', $source, $file);
        }
    }

    public function test_public_currency_and_replacement_icons_use_stable_markup(): void
    {
        $programs = file_get_contents(resource_path('views/programs/index.blade.php')) ?: '';
        $contact = file_get_contents(resource_path('views/contact/index.blade.php')) ?: '';

        $this->assertStringContainsString('&#8377;', $programs);
        $this->assertStringContainsString('<svg', $contact);
        $this->assertStringContainsString('aria-hidden="true"', $contact);
    }

    public function test_homepage_still_emits_theme_tokens_and_a_hero_image_source(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('data-theme-token-emitter', false)
            ->assertSee('--primary-color: #facc15;', false)
            ->assertSee('src="'.asset('images/dk-hero.jpeg').'"', false);
    }

    /** @return array<string, array{string}> */
    public static function publicRouteProvider(): array
    {
        return [
            'home' => ['/'],
            'programs' => ['/programs'],
            'services' => ['/services'],
            'products' => ['/products'],
            'plans' => ['/plans'],
            'about' => ['/about'],
            'blog' => ['/blog'],
            'fitness hub' => ['/fitness-hub'],
            'transformations' => ['/transformations'],
            'contact' => ['/contact'],
            'login' => ['/login'],
            'register' => ['/register'],
            'profile' => ['/profile'],
        ];
    }

    /** @return array<int, string> */
    private function publicBladeFiles(): array
    {
        $roots = [
            'about', 'account', 'auth', 'blog', 'checkout', 'contact', 'dashboard',
            'diet-plans', 'fitness-hub', 'home', 'layouts', 'member', 'partials',
            'plans', 'premium', 'products', 'profile', 'programs', 'services',
            'transformations', 'workout-plans',
        ];
        $files = [resource_path('views/dashboard.blade.php')];

        foreach ($roots as $root) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(resource_path("views/{$root}")),
            );

            foreach ($iterator as $file) {
                if ($file instanceof SplFileInfo && $file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                    $files[] = $file->getPathname();
                }
            }
        }

        return array_values(array_unique($files));
    }
}
