<?php

namespace Tests\Feature\WebsiteBuilder;

use App\Livewire\Builder\WebsiteBuilder;
use App\Models\Blog;
use App\Models\HeroSetting;
use App\Models\HomepageCard;
use App\Models\Media;
use App\Models\Product;
use App\Models\Program;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Transformation;
use App\Models\User;
use App\Models\WebsiteSection;
use App\Services\Builder\BlogService;
use App\Services\Builder\ProductService;
use App\Services\Builder\ProgramService;
use App\Services\Builder\TestimonialService;
use App\Services\Builder\TransformationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class WebsiteBuilderSafetyTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->admin = User::factory()->create([
            'account_type' => 'admin',
            'is_admin' => true,
        ]);

        Setting::query()->create(['site_name' => 'DK Singh Fitness']);
        WebsiteSection::query()->create([
            'page' => 'home',
            'section' => 'hero',
            'title' => 'Hero',
            'enabled' => true,
            'sort_order' => 1,
            'settings' => [],
            'template' => 'default',
        ]);
    }

    public function test_admin_can_save_contact_and_refresh_persisted_values(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(WebsiteBuilder::class)
            ->set('contact.contact_title', 'Contact our coaching team')
            ->set('contact.contact_heading', 'Start your transformation')
            ->set('contact.contact_description', 'Tell us how we can help.')
            ->set('contact.map_embed_url', 'https://maps.google.com/maps?q=Delhi')
            ->set('contact.map_link', 'https://maps.google.com/')
            ->set('contact.contact_map_text', 'Visit our coaching centre.')
            ->call('saveContact')
            ->assertHasNoErrors()
            ->assertNotified();

        $this->assertDatabaseHas('settings', ['contact_title' => 'Contact our coaching team']);

        Livewire::test(WebsiteBuilder::class)
            ->assertSet('contact.contact_title', 'Contact our coaching team');
    }

    public function test_validation_failure_is_visible_and_does_not_persist_contact(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(WebsiteBuilder::class)
            ->set('contact.contact_title', 'Invalid contact')
            ->set('contact.map_link', 'not-a-url')
            ->call('saveContact')
            ->assertHasErrors(['contact.map_link'])
            ->assertNotified();

        $this->assertDatabaseMissing('settings', ['contact_title' => 'Invalid contact']);
    }

    public function test_homepage_card_save_delete_and_notifications_are_authorized(): void
    {
        $this->actingAs($this->admin);

        $card = HomepageCard::query()->create([
            'title' => 'Coaching',
            'subtitle' => 'Personalized',
            'description' => 'Original',
            'button_text' => 'Learn more',
            'button_link' => 'https://example.com/coaching',
            'icon' => 'star',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Livewire::test(WebsiteBuilder::class)
            ->call('selectHomepageCard', $card->id)
            ->set('homepageCard.description', 'Updated safely')
            ->call('saveHomepageCard')
            ->assertHasNoErrors()
            ->assertNotified()
            ->call('deleteHomepageCard', $card->id)
            ->assertNotified();

        $this->assertDatabaseMissing('homepage_cards', ['id' => $card->id]);
    }

    public function test_program_can_be_duplicated_and_ordered_transactionally(): void
    {
        $this->actingAs($this->admin);

        $first = (new ProgramService)->create();
        $first->update(['title' => 'First program', 'sort_order' => 1]);
        $second = $first->replicate();
        $second->title = 'Second program';
        $second->slug = 'second-program';
        $second->sort_order = 2;
        $second->save();

        Livewire::test(WebsiteBuilder::class)
            ->call('duplicateProgram', $first->id)
            ->assertNotified()
            ->call('moveProgramUp', $second->id)
            ->assertHasNoErrors();

        $this->assertSame(3, Program::query()->count());
        $this->assertSame(1, $second->fresh()->sort_order);
    }

    public function test_secure_program_upload_is_validated_and_persisted(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $program = (new ProgramService)->create();

        Livewire::test(WebsiteBuilder::class)
            ->call('selectProgram', $program->id)
            ->set('programImage', UploadedFile::fake()->image('program.jpg', 1200, 800)->size(500))
            ->call('saveProgram')
            ->assertHasNoErrors()
            ->assertNotified();

        $path = $program->fresh()->image;
        $this->assertNotNull($path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_invalid_upload_is_rejected_before_storage(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $program = (new ProgramService)->create();

        Livewire::test(WebsiteBuilder::class)
            ->call('selectProgram', $program->id)
            ->set('programImage', UploadedFile::fake()->create('payload.php', 10, 'application/x-php'))
            ->call('saveProgram')
            ->assertHasErrors(['programImage'])
            ->assertNotified();

        $this->assertNull($program->fresh()->image);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_non_admin_cannot_execute_builder_mutations(): void
    {
        $this->actingAs(User::factory()->create([
            'account_type' => 'customer',
            'is_admin' => false,
        ]));

        Livewire::test(WebsiteBuilder::class)
            ->set('contact.contact_title', 'Unauthorized change')
            ->call('saveContact')
            ->assertForbidden();

        $this->assertDatabaseMissing('settings', ['contact_title' => 'Unauthorized change']);
    }

    public function test_hero_and_media_validation_failures_are_visible(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(WebsiteBuilder::class)
            ->set('hero.heading', '')
            ->set('heroBackgroundMediaId', 999999)
            ->call('saveHero')
            ->assertHasErrors(['hero.heading', 'heroBackgroundMediaId'])
            ->assertNotified();
    }

    public function test_builder_media_picker_uses_filament_modal_events_for_supported_targets(): void
    {
        $this->actingAs($this->admin);
        $media = Media::query()->create([
            'name' => 'Builder media',
            'file_name' => 'builder-media.webp',
            'path' => 'media/builder-media.webp',
            'type' => 'image',
        ]);

        Livewire::test(WebsiteBuilder::class)
            ->assertSeeHtml('data-fi-modal-id="website-builder-media-picker"')
            ->call('openMediaPicker', 'hero')
            ->assertSet('mediaTarget', 'hero')
            ->assertSet('showMediaPicker', true)
            ->assertDispatched('open-modal', id: 'website-builder-media-picker')
            ->call('mediaSelected', $media->id)
            ->assertSet('heroBackgroundMediaId', $media->id)
            ->assertSet('showMediaPicker', false)
            ->assertDispatched('close-modal', id: 'website-builder-media-picker')
            ->call('openMediaPicker', 'hero')
            ->call('closeMediaPicker')
            ->assertSet('mediaTarget', null)
            ->assertSet('showMediaPicker', false)
            ->assertDispatched('close-modal', id: 'website-builder-media-picker')
            ->call('openMediaPicker', 'homepage-card')
            ->assertSet('mediaTarget', 'homepage-card')
            ->assertDispatched('open-modal', id: 'website-builder-media-picker');
    }

    public function test_product_validation_rejects_invalid_commerce_values(): void
    {
        $this->actingAs($this->admin);
        $product = (new ProductService)->create();

        Livewire::test(WebsiteBuilder::class)
            ->call('selectProduct', $product->id)
            ->set('product.price', -1)
            ->set('product.weight', -1)
            ->call('saveProduct')
            ->assertHasErrors(['product.price', 'product.weight'])
            ->assertNotified();

        $this->assertSame(0.0, (float) Product::query()->findOrFail($product->id)->price);
    }

    public function test_testimonial_validation_enforces_rating_range(): void
    {
        $this->actingAs($this->admin);
        $testimonial = (new TestimonialService)->create();

        Livewire::test(WebsiteBuilder::class)
            ->call('selectTestimonial', $testimonial->id)
            ->set('testimonial.review', 'A valid review')
            ->set('testimonial.rating', 6)
            ->call('saveTestimonial')
            ->assertHasErrors(['testimonial.rating'])
            ->assertNotified();

        $this->assertSame(5, Testimonial::query()->findOrFail($testimonial->id)->rating);
    }

    public function test_blog_validation_prevents_empty_content(): void
    {
        $this->actingAs($this->admin);
        $blog = (new BlogService)->create();

        Livewire::test(WebsiteBuilder::class)
            ->call('selectBlog', $blog->id)
            ->set('blog.content', '')
            ->call('saveBlog')
            ->assertHasErrors(['blog.content'])
            ->assertNotified();

        $this->assertSame('', Blog::query()->findOrFail($blog->id)->content);
    }

    public function test_transformation_validation_rejects_negative_weights(): void
    {
        $this->actingAs($this->admin);
        $transformation = (new TransformationService)->create();

        Livewire::test(WebsiteBuilder::class)
            ->call('selectTransformation', $transformation->id)
            ->set('transformation.before_weight', -5)
            ->call('saveTransformation')
            ->assertHasErrors(['transformation.before_weight'])
            ->assertNotified();

        $this->assertSame(0.0, (float) Transformation::query()->findOrFail($transformation->id)->before_weight);
    }

    public function test_all_existing_hero_fields_save_and_preview_from_live_state(): void
    {
        $this->actingAs($this->admin);
        $media = Media::query()->create([
            'name' => 'Hero',
            'file_name' => 'hero.webp',
            'path' => 'media/hero.webp',
            'type' => 'image',
        ]);

        Livewire::test(WebsiteBuilder::class)
            ->set('hero.heading', 'Live preview heading')
            ->set('hero.subheading', 'Updated subtitle')
            ->set('hero.button_text', 'Join today')
            ->set('hero.button_link', '/plans')
            ->set('hero.view_button_text', 'View results')
            ->set('hero.view_button_link', '/transformations')
            ->set('hero.video', 'https://example.com/hero.mp4')
            ->set('hero.overlay_color', '#112233')
            ->set('hero.overlay_opacity', 35)
            ->set('hero.template', 'modern')
            ->set('hero.enabled', false)
            ->set('hero.followers_label', 'Community')
            ->set('hero.years_label', 'Years coaching')
            ->set('hero.transformations_label', 'Results')
            ->set('heroBackgroundMediaId', $media->id)
            ->assertSee('Live preview heading')
            ->call('saveHero')
            ->assertHasNoErrors()
            ->assertNotified();

        $hero = HeroSetting::query()->firstOrFail();
        $this->assertSame('Live preview heading', $hero->heading);
        $this->assertSame('https://example.com/hero.mp4', $hero->video);
        $this->assertSame('#112233', $hero->overlay_color);
        $this->assertSame(35, $hero->overlay_opacity);
        $this->assertFalse($hero->enabled);
        $this->assertSame($media->id, $hero->background_media_id);
    }

    public function test_homepage_card_upload_duplicate_and_live_preview_are_complete(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);
        $card = HomepageCard::query()->create([
            'title' => 'Original card',
            'button_link' => 'https://example.com/card',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $section = WebsiteSection::query()->create([
            'page' => 'home',
            'section' => 'homepage_cards',
            'title' => 'Homepage Cards',
            'enabled' => true,
            'sort_order' => 2,
            'settings' => [],
            'template' => 'default',
        ]);

        Livewire::test(WebsiteBuilder::class)
            ->call('selectSection', $section->id)
            ->call('selectHomepageCard', $card->id)
            ->set('homepageCard.title', 'Preview card')
            ->set('homepageCardImage', UploadedFile::fake()->image('card.jpg', 1200, 800))
            ->assertSee('Preview card')
            ->call('saveHomepageCard')
            ->assertHasNoErrors()
            ->assertNotified()
            ->call('duplicateHomepageCard', $card->id)
            ->assertNotified();

        $path = $card->fresh()->background_image;
        Storage::disk('public')->assertExists($path);
        $this->assertSame(2, HomepageCard::query()->count());
    }

    public function test_product_and_testimonial_edit_upload_duplicate_and_delete_flows(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);
        $product = (new ProductService)->create();
        $testimonial = (new TestimonialService)->create();
        $productSection = WebsiteSection::query()->create([
            'page' => 'home',
            'section' => 'products',
            'title' => 'Products',
            'enabled' => true,
            'sort_order' => 2,
            'settings' => [],
            'template' => 'default',
        ]);
        $testimonialSection = WebsiteSection::query()->create([
            'page' => 'home',
            'section' => 'testimonials',
            'title' => 'Testimonials',
            'enabled' => true,
            'sort_order' => 3,
            'settings' => [],
            'template' => 'default',
        ]);

        Livewire::test(WebsiteBuilder::class)
            ->call('selectSection', $productSection->id)
            ->call('selectProduct', $product->id)
            ->set('product.name', 'Protein Mix')
            ->set('product.price', 1499)
            ->set('product.category', 'Supplements')
            ->set('productImage', UploadedFile::fake()->image('product.webp', 900, 900))
            ->assertSee('Protein Mix')
            ->call('saveProduct')
            ->assertNotified()
            ->call('duplicateProduct', $product->id)
            ->assertNotified();

        Livewire::test(WebsiteBuilder::class)
            ->call('selectSection', $testimonialSection->id)
            ->call('selectTestimonial', $testimonial->id)
            ->set('testimonial.name', 'Verified Client')
            ->set('testimonial.review', 'Excellent coaching and support.')
            ->set('testimonial.rating', 5)
            ->set('testimonialImage', UploadedFile::fake()->image('client.jpg', 600, 600))
            ->assertSee('Verified Client')
            ->call('saveTestimonial')
            ->assertNotified()
            ->call('duplicateTestimonial', $testimonial->id)
            ->assertNotified()
            ->call('deleteTestimonial', $testimonial->id)
            ->assertNotified();

        $this->assertDatabaseHas('products', ['name' => 'Protein Mix', 'price' => 1499]);
        $this->assertSame(2, Product::query()->count());
        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
    }

    public function test_transformation_and_blog_complete_upload_and_crud_flows(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);
        $transformation = (new TransformationService)->create();
        $blog = (new BlogService)->create();
        $transformationSection = WebsiteSection::query()->create([
            'page' => 'home',
            'section' => 'transformations',
            'title' => 'Transformations',
            'enabled' => true,
            'sort_order' => 2,
            'settings' => [],
            'template' => 'default',
        ]);
        $blogSection = WebsiteSection::query()->create([
            'page' => 'home',
            'section' => 'blogs',
            'title' => 'Blog',
            'enabled' => true,
            'sort_order' => 3,
            'settings' => [],
            'template' => 'default',
        ]);

        Livewire::test(WebsiteBuilder::class)
            ->call('selectSection', $transformationSection->id)
            ->call('selectTransformation', $transformation->id)
            ->set('transformation.name', 'Twelve Week Result')
            ->set('beforeImage', UploadedFile::fake()->image('before.jpg', 700, 900))
            ->set('afterImage', UploadedFile::fake()->image('after.jpg', 700, 900))
            ->assertSee('Twelve Week Result')
            ->call('saveTransformation')
            ->assertNotified()
            ->call('duplicateTransformation', $transformation->id)
            ->assertNotified();

        Livewire::test(WebsiteBuilder::class)
            ->call('selectSection', $blogSection->id)
            ->call('selectBlog', $blog->id)
            ->set('blog.title', 'Builder Blog Article')
            ->set('blog.content', 'A complete article managed through the existing Blog model.')
            ->set('blogImage', UploadedFile::fake()->image('blog.jpg', 1200, 700))
            ->assertSee('Builder Blog Article')
            ->call('saveBlog')
            ->assertNotified()
            ->call('duplicateBlog', $blog->id)
            ->assertNotified()
            ->call('deleteBlog', $blog->id)
            ->assertNotified();

        $this->assertNotNull($transformation->fresh()->before_image);
        $this->assertNotNull($transformation->fresh()->after_image);
        $this->assertSame(2, Transformation::query()->count());
        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
    }
}
