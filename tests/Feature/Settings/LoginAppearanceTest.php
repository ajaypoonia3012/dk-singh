<?php

namespace Tests\Feature\Settings;

use App\Filament\Resources\ThemeSettingResource\Pages\EditThemeSetting;
use App\Models\Media;
use App\Models\ThemeSetting;
use App\Models\User;
use App\Support\LoginAppearance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class LoginAppearanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_public_login_preserves_the_breeze_form_contract_and_theme_tokens(): void
    {
        ThemeSetting::query()->create([
            'theme_name' => 'Login theme',
            'primary_color' => '#2563eb',
            'card_background' => '#f8fafc',
            'input_focus_color' => '#0f766e',
        ]);

        $this->get('/login')
            ->assertOk()
            ->assertSee('method="POST"', false)
            ->assertSee('action="'.route('login').'"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('type="checkbox"', false)
            ->assertSee('name="remember"', false)
            ->assertSee('value="1"', false)
            ->assertSee('class="theme-checkbox"', false)
            ->assertDontSee('type="checkbox" class="theme-form-control', false)
            ->assertSee('--primary-color: #2563eb;', false)
            ->assertSee('--card-background: #f8fafc;', false)
            ->assertSee('--input-focus: #0f766e;', false)
            ->assertSee('data-login-appearance="public"', false)
            ->assertSee('data-background-mode="theme"', false);
    }

    public function test_public_login_renders_configured_media_overlay_and_animation(): void
    {
        $media = $this->createMedia('login/public.webp');

        ThemeSetting::query()->create([
            'theme_name' => 'Media login',
            'public_login_background_mode' => 'image-overlay',
            'public_login_background_media_id' => $media->id,
            'public_login_overlay_color' => '#123456',
            'public_login_overlay_opacity' => 62,
            'public_login_animation' => 'image-zoom',
            'public_login_animation_speed' => 24,
            'public_login_animation_intensity' => 35,
        ]);

        $this->get('/login')
            ->assertOk()
            ->assertSee('data-background-mode="image-overlay"', false)
            ->assertSee('data-login-animation="image-zoom"', false)
            ->assertSee(asset('storage/login/public.webp'), false)
            ->assertSee('--login-overlay-color: #123456;', false)
            ->assertSee('--login-overlay-opacity: 0.62;', false)
            ->assertSee('--login-animation-speed: 24s;', false);
    }

    public function test_admin_login_uses_the_same_persistent_appearance_renderer(): void
    {
        $media = $this->createMedia('login/admin.webp');

        ThemeSetting::query()->create([
            'theme_name' => 'Admin login',
            'admin_login_background_mode' => 'image',
            'admin_login_background_media_id' => $media->id,
            'admin_login_overlay_opacity' => 48,
            'admin_login_animation' => 'orbs',
            'admin_login_animation_speed' => 30,
        ]);

        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('data-login-appearance="admin"', false)
            ->assertSee('data-background-mode="image"', false)
            ->assertSee('data-login-animation="orbs"', false)
            ->assertSee(asset('storage/login/admin.webp'), false)
            ->assertSee('--login-overlay-opacity: 0.48;', false)
            ->assertSee('--login-animation-speed: 30s;', false);
    }

    public function test_public_image_remains_rendered_with_gradient_animation(): void
    {
        $media = $this->createMedia('login/public-gradient.webp');

        ThemeSetting::query()->create([
            'theme_name' => 'Public image gradient',
            'public_login_background_mode' => 'image',
            'public_login_background_media_id' => $media->id,
            'public_login_animation' => 'gradient',
        ]);

        $response = $this->get('/login')
            ->assertOk()
            ->assertSee('login-appearance--image login-appearance--animation-gradient', false)
            ->assertSee('url("'.asset('storage/login/public-gradient.webp').'")', false);

        $markup = $this->loginAppearanceOpeningTag($response->getContent(), 'public');

        $this->assertStringNotContainsString('&amp;quot;', $markup);
        $this->assertStringNotContainsString('&quot;', $markup);

        $this->assertStringContainsString(
            '.login-appearance--image.login-appearance--animation-gradient .login-appearance__overlay',
            file_get_contents(resource_path('css/theme.css')) ?: '',
        );
        $this->assertStringNotContainsString(
            '.login-appearance--animation-gradient .login-appearance__backdrop',
            file_get_contents(resource_path('css/theme.css')) ?: '',
        );
    }

    public function test_admin_image_remains_rendered_with_gradient_animation(): void
    {
        $media = $this->createMedia('login/admin-gradient.webp');

        ThemeSetting::query()->create([
            'theme_name' => 'Admin image gradient',
            'admin_login_background_mode' => 'image',
            'admin_login_background_media_id' => $media->id,
            'admin_login_animation' => 'gradient',
        ]);

        $response = $this->get('/admin/login')
            ->assertOk()
            ->assertSee('login-appearance--image login-appearance--animation-gradient', false)
            ->assertSee('url("'.asset('storage/login/admin-gradient.webp').'")', false);

        $markup = $this->loginAppearanceOpeningTag($response->getContent(), 'admin');

        $this->assertStringNotContainsString('&amp;quot;', $markup);
        $this->assertStringNotContainsString('&quot;', $markup);
    }

    public function test_image_mode_without_animation_still_renders_the_image(): void
    {
        $media = $this->createMedia('login/static.webp');

        ThemeSetting::query()->create([
            'theme_name' => 'Static login image',
            'public_login_background_mode' => 'image',
            'public_login_background_media_id' => $media->id,
            'public_login_animation' => 'none',
        ]);

        $this->get('/login')
            ->assertOk()
            ->assertSee('login-appearance--image login-appearance--animation-none', false)
            ->assertSee('url("'.asset('storage/login/static.webp').'")', false);
    }

    public function test_gradient_mode_without_an_image_remains_available(): void
    {
        ThemeSetting::query()->create([
            'theme_name' => 'Gradient login',
            'public_login_background_mode' => 'gradient',
            'public_login_animation' => 'gradient',
        ]);

        $this->get('/login')
            ->assertOk()
            ->assertSee('login-appearance--gradient login-appearance--animation-gradient', false)
            ->assertSee('--login-background-image: none;', false);

        $css = file_get_contents(resource_path('css/theme.css')) ?: '';

        $this->assertStringContainsString('.login-appearance--gradient .login-appearance__backdrop', $css);
        $this->assertStringContainsString('@keyframes login-gradient', $css);
        $this->assertStringContainsString('@media (prefers-reduced-motion: reduce)', $css);
    }

    public function test_filament_edit_persists_login_settings_and_invalidates_theme_cache(): void
    {
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $media = $this->createMedia('login/persisted.webp');
        $theme = ThemeSetting::query()->create(['theme_name' => 'Before']);
        Cache::put(ThemeSetting::CACHE_KEY, 'stale');

        Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->fillForm([
                'public_login_background_mode' => 'image-overlay',
                'public_login_background_media_id' => $media->id,
                'public_login_overlay_opacity' => 55,
                'public_login_animation' => 'image-zoom',
                'admin_login_background_mode' => 'gradient',
                'admin_login_animation' => 'gradient',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $theme->refresh();

        $this->assertSame('image-overlay', $theme->public_login_background_mode);
        $this->assertSame($media->id, $theme->public_login_background_media_id);
        $this->assertSame(55, $theme->public_login_overlay_opacity);
        $this->assertSame('gradient', $theme->admin_login_background_mode);
        $this->assertSame('gradient', $theme->design_configuration['admin_login_animation']);
        $this->assertFalse(Cache::has(ThemeSetting::CACHE_KEY));
    }

    public function test_login_css_has_reduced_motion_and_protects_checkbox_sizing(): void
    {
        $css = file_get_contents(resource_path('css/theme.css')) ?: '';

        $this->assertStringContainsString('@media (prefers-reduced-motion: reduce)', $css);
        $this->assertStringContainsString('.theme-form-control:not([type="checkbox"]):not([type="radio"])', $css);
        $this->assertStringContainsString('.theme-checkbox {', $css);
        $this->assertStringContainsString('width: 1.125rem;', $css);
        $this->assertStringContainsString('height: 1.125rem;', $css);
        $this->assertStringContainsString('@keyframes login-gradient', $css);
        $this->assertStringContainsString('@keyframes login-image-zoom', $css);
    }

    public function test_public_login_image_can_be_uploaded_into_the_media_library(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $theme = ThemeSetting::query()->create(['theme_name' => 'Public upload']);

        Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->fillForm([
                'public_login_background_mode' => 'image',
                'public_login_background_upload' => UploadedFile::fake()->image('public-login.jpg', 1600, 900),
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $media = Media::query()->sole();
        $theme->refresh();

        $this->assertSame($media->id, $theme->public_login_background_media_id);
        $this->assertSame('public-login', $media->name);
        $this->assertSame('image', $media->type);
        $this->assertTrue($media->active);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_admin_login_image_upload_is_persisted_and_rendered(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $theme = ThemeSetting::query()->create(['theme_name' => 'Admin upload']);

        Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->fillForm([
                'admin_login_background_mode' => 'image-overlay',
                'admin_login_background_upload' => UploadedFile::fake()->image('admin-login.webp', 1600, 900),
                'admin_login_overlay_opacity' => 50,
                'admin_login_animation' => 'image-zoom',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $media = Media::query()->sole();
        $theme->refresh();

        $this->assertSame($media->id, $theme->admin_login_background_media_id);
        Storage::disk('public')->assertExists($media->path);

        $this->assertSame('image-overlay', $theme->admin_login_background_mode);
        $this->assertSame('image-zoom', $theme->admin_login_animation);
    }

    public function test_non_image_login_upload_is_rejected_without_creating_media(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $theme = ThemeSetting::query()->create(['theme_name' => 'Invalid upload']);

        Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->fillForm([
                'public_login_background_upload' => UploadedFile::fake()->create('payload.txt', 10, 'text/plain'),
            ])
            ->call('save')
            ->assertHasFormErrors(['public_login_background_upload']);

        $this->assertDatabaseCount('media', 0);
        $this->assertNull($theme->refresh()->public_login_background_media_id);
        $this->assertSame([], Storage::disk('public')->allFiles('media/originals'));
    }

    public function test_png_upload_is_stored_in_media_library_and_resolves_for_public_login(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $theme = ThemeSetting::query()->create(['theme_name' => 'PNG upload']);

        Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->fillForm([
                'public_login_background_mode' => 'image',
                'public_login_background_upload' => UploadedFile::fake()->image('public-login.png', 1600, 900),
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $media = Media::query()->sole();
        $theme->refresh();

        $this->assertSame($media->id, $theme->public_login_background_media_id);
        $this->assertSame('image/png', $media->mime_type);
        $this->assertSame('media/originals', $media->folder);
        $this->assertStringStartsWith('media/originals/', $media->path);
        Storage::disk('public')->assertExists($media->path);
        $this->assertSame($media->url, app(LoginAppearance::class)->resolve($theme, 'public')['image']);
    }

    public function test_gif_upload_is_stored_and_assigned_to_admin_login(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $theme = ThemeSetting::query()->create(['theme_name' => 'GIF upload']);

        Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->fillForm([
                'admin_login_background_mode' => 'image',
                'admin_login_background_upload' => UploadedFile::fake()->image('admin-login.gif', 1200, 800),
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $media = Media::query()->sole();
        $theme->refresh();

        $this->assertSame($media->id, $theme->admin_login_background_media_id);
        $this->assertSame('image/gif', $media->mime_type);
        $this->assertStringStartsWith('media/originals/', $media->path);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_image_at_twenty_megabytes_is_accepted(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $theme = ThemeSetting::query()->create(['theme_name' => 'Boundary upload']);

        Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->fillForm([
                'public_login_background_upload' => UploadedFile::fake()
                    ->image('allowed.jpg', 1200, 800)
                    ->size(20 * 1024),
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $media = Media::query()->sole();

        $this->assertSame($media->id, $theme->refresh()->public_login_background_media_id);
        Storage::disk('public')->assertExists($media->path);
    }

    public function test_image_over_twenty_megabytes_is_rejected_without_final_file_or_media(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $theme = ThemeSetting::query()->create(['theme_name' => 'Oversized upload']);

        $component = Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
            ->fillForm([
                'public_login_background_upload' => UploadedFile::fake()
                    ->image('oversized.jpg', 1200, 800)
                    ->size((20 * 1024) + 1),
            ]);

        $this->assertEmpty($component->get('data.public_login_background_upload'));

        $component->call('save')->assertHasNoFormErrors();

        $this->assertDatabaseCount('media', 0);
        $this->assertNull($theme->refresh()->public_login_background_media_id);
        $this->assertSame([], Storage::disk('public')->allFiles('media/originals'));
    }

    public function test_media_creation_failure_removes_the_stored_final_file(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->create(['account_type' => 'admin', 'is_admin' => true]));
        $theme = ThemeSetting::query()->create(['theme_name' => 'Failed upload']);
        Media::creating(fn (): never => throw new \RuntimeException('Simulated media persistence failure.'));

        try {
            Livewire::test(EditThemeSetting::class, ['record' => $theme->getRouteKey()])
                ->fillForm([
                    'public_login_background_upload' => UploadedFile::fake()->image('failure.png', 1200, 800),
                ])
                ->call('save');

            $this->fail('The simulated Media persistence failure was not thrown.');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Simulated media persistence failure.', $exception->getMessage());
        }

        $this->assertDatabaseCount('media', 0);
        $this->assertNull($theme->refresh()->public_login_background_media_id);
        $this->assertSame([], Storage::disk('public')->allFiles('media/originals'));
    }

    public function test_existing_authentication_behavior_is_unchanged(): void
    {
        $user = User::factory()->create([
            'account_type' => 'customer',
            'is_admin' => false,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => '1',
        ])->assertRedirect('/member/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_required_login_program_and_filament_routes_are_registered(): void
    {
        $this->assertSame('login', Route::getRoutes()->getByName('login')?->uri());
        $this->assertSame('programs', Route::getRoutes()->getByName('programs.index')?->uri());
        $this->assertSame('programs/{slug}', Route::getRoutes()->getByName('programs.show')?->uri());
        $this->assertSame('admin/login', Route::getRoutes()->getByName('filament.admin.auth.login')?->uri());
    }

    private function createMedia(string $path): Media
    {
        return Media::query()->create([
            'name' => basename($path),
            'file_name' => basename($path),
            'disk' => 'public',
            'folder' => 'login',
            'path' => $path,
            'type' => 'image',
            'active' => true,
        ]);
    }

    private function loginAppearanceOpeningTag(string $html, string $context): string
    {
        $matched = preg_match(
            '/<div\s+data-login-appearance="'.preg_quote($context, '/').'"[\s\S]*?>/',
            $html,
            $matches,
        );

        $this->assertSame(1, $matched, "The {$context} login appearance markup was not rendered.");

        return $matches[0];
    }
}
