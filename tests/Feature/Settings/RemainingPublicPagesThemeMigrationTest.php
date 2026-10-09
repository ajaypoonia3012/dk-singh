<?php

namespace Tests\Feature\Settings;

use App\Models\Setting;
use App\Models\ThemeSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Tests\TestCase;

class RemainingPublicPagesThemeMigrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        Setting::query()->create([
            'site_name' => 'DK Singh Fitness',
            'site_tagline' => 'Transform your fitness',
        ]);

        $theme = ThemeSetting::query()->create([
            'theme_name' => 'Phase 6D',
            'show_hero' => true,
        ]);
        $theme->applyPalette('corporate-blue')->save();
    }

    #[DataProvider('publicPageProvider')]
    public function test_remaining_public_pages_render_with_canonical_theme_tokens(string $uri): void
    {
        $this->get($uri)
            ->assertOk()
            ->assertSee('data-theme-token-emitter', false)
            ->assertSee('--primary-color: #2563eb;', false);
    }

    public function test_phase_6d_views_use_existing_theme_consumers_without_fixed_palette_styles(): void
    {
        $files = $this->phase6dViewFiles();

        $this->assertNotEmpty($files);

        $forbidden = '/(?:bg|text|border|ring)-(?:yellow|amber|white|black|gray|zinc|blue|green|red|emerald)-?[^\s"\']*|rounded-(?:md|lg|xl|2xl|3xl)|shadow-(?:sm|md|lg|xl|2xl)|(?<!&)#[0-9a-fA-F]{3,8}/';

        foreach ($files as $file) {
            $source = file_get_contents($file) ?: '';

            $this->assertDoesNotMatchRegularExpression($forbidden, $source, $file);
        }
    }

    public function test_blog_styles_consume_the_canonical_theme_contract(): void
    {
        $css = file_get_contents(resource_path('css/blog.css')) ?: '';

        foreach ([
            'var(--primary-color)',
            'var(--secondary-color)',
            'var(--neutral-color)',
            'var(--card-background)',
            'var(--card-radius)',
            'var(--card-shadow)',
            'var(--input-radius)',
        ] as $token) {
            $this->assertStringContainsString($token, $css);
        }

        $this->assertStringNotContainsString('--dk-', $css);
        $this->assertDoesNotMatchRegularExpression('/#[0-9a-fA-F]{3,8}/', $css);
    }

    /** @return array<string, array{string}> */
    public static function publicPageProvider(): array
    {
        return [
            'about' => ['/about'],
            'contact' => ['/contact'],
            'plans' => ['/plans'],
            'products' => ['/products'],
            'programs' => ['/programs'],
            'services' => ['/services'],
            'transformations' => ['/transformations'],
            'fitness hub' => ['/fitness-hub'],
            'fitness diets' => ['/fitness-hub/diets'],
            'fitness workouts' => ['/fitness-hub/workouts'],
            'blog' => ['/blog'],
        ];
    }

    /** @return array<int, string> */
    private function phase6dViewFiles(): array
    {
        $directories = [
            'about', 'contact', 'plans', 'products', 'programs', 'services',
            'transformations', 'diet-plans', 'workout-plans', 'fitness-hub',
            'premium', 'checkout', 'account', 'dashboard', 'member', 'blog',
        ];
        $files = [resource_path('views/dashboard.blade.php')];

        foreach ($directories as $directory) {
            $path = resource_path("views/{$directory}");

            if (! is_dir($path)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

            foreach ($iterator as $file) {
                if (! $file instanceof SplFileInfo || ! $file->isFile() || $file->getExtension() !== 'php') {
                    continue;
                }

                $pathname = $file->getPathname();

                if (str_contains($pathname, 'my-plan-backup.blade.php') || str_contains($pathname, DIRECTORY_SEPARATOR.'reports'.DIRECTORY_SEPARATOR)) {
                    continue;
                }

                $files[] = $pathname;
            }
        }

        return array_values(array_unique($files));
    }
}
