<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogMigrationVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::query()->create([
            'site_name' => 'DK Singh Fitness',
            'email' => 'coach@dksinghfitness.com',
            'contact_title' => 'Get in Touch',
        ]);
    }

    public function test_blog_index_loads_with_articles(): void
    {
        $category = BlogCategory::create([
            'name' => 'Indian Diet',
            'slug' => 'indian-diet',
            'status' => true,
        ]);

        $post = BlogPost::create([
            'blog_category_id' => $category->id,
            'title' => 'How to Build Muscle on an Indian Vegetarian Diet',
            'slug' => 'how-to-build-muscle-on-an-indian-vegetarian-diet',
            'excerpt' => 'High protein Indian nutrition guide.',
            'content' => '<p>DK Singh Fitness original coaching guide.</p>',
            'author' => 'DK Singh',
            'reading_time' => 5,
            'views' => 120,
            'featured' => true,
            'status' => true,
            'published_at' => now(),
            'seo_title' => 'Build Muscle on Indian Veg Diet',
            'seo_description' => 'Evidence based Indian diet guide.',
        ]);

        $response = $this->get('/blog');
        $response->assertStatus(200);
        $response->assertSee('Fitness Blog');
        $response->assertSee($post->title);
        $response->assertDontSee('Alpha Coach');
    }

    public function test_blog_category_filter(): void
    {
        $category = BlogCategory::create([
            'name' => 'Indian Diet',
            'slug' => 'indian-diet',
            'status' => true,
        ]);

        $post = BlogPost::create([
            'blog_category_id' => $category->id,
            'title' => 'Protein in Paneer Guide',
            'slug' => 'protein-in-paneer-guide',
            'excerpt' => 'Paneer nutrition guide.',
            'content' => '<p>DK Singh Fitness paneer guide.</p>',
            'author' => 'DK Singh Fitness Editorial Team',
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/blog/category/' . $category->slug);
        $response->assertStatus(200);
        $response->assertSee('Indian Diet');
        $response->assertSee('Protein in Paneer Guide');
    }

    public function test_blog_tag_filter(): void
    {
        $category = BlogCategory::create([
            'name' => 'Nutrition',
            'slug' => 'nutrition',
            'status' => true,
        ]);

        $tag = BlogTag::create([
            'name' => 'High Protein',
            'slug' => 'high-protein',
        ]);

        $post = BlogPost::create([
            'blog_category_id' => $category->id,
            'title' => 'Protein in Eggs Breakdown',
            'slug' => 'protein-in-eggs-breakdown',
            'excerpt' => 'Egg nutrition facts.',
            'content' => '<p>DK Singh Fitness egg guide.</p>',
            'author' => 'DK Singh Fitness Editorial Team',
            'status' => true,
            'published_at' => now(),
        ]);

        $post->tags()->attach($tag->id);

        $response = $this->get('/blog/tag/' . $tag->slug);
        $response->assertStatus(200);
        $response->assertSee('High Protein');
        $response->assertSee('Protein in Eggs Breakdown');
    }

    public function test_blog_article_detail_page(): void
    {
        $category = BlogCategory::create([
            'name' => 'Workout & Training',
            'slug' => 'workout-and-training',
            'status' => true,
        ]);

        $post = BlogPost::create([
            'blog_category_id' => $category->id,
            'title' => 'Walking vs Jogging vs Running for Fat Loss',
            'slug' => 'walking-vs-jogging-vs-running-for-fat-loss',
            'excerpt' => 'Which burns more fat?',
            'content' => '<p>Quick Key Takeaways: Brisk walking preserves recovery.</p>',
            'author' => 'DK Singh',
            'reading_time' => 6,
            'views' => 450,
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/blog/' . $post->slug);
        $response->assertStatus(200);
        $response->assertSee($post->title);
        $response->assertSee('DK Singh');
        $response->assertSee('Quick Key Takeaways');
        $response->assertDontSee('Alpha Coach');
    }

    public function test_legacy_blog_301_redirects(): void
    {
        $response = $this->get('/blog/laboriosam-voluptatem-veniam-praesentium-voluptatem-ab-amet-ea');
        $response->assertStatus(301);
        $response->assertRedirect('/blog/category/workout-and-training');

        $response2 = $this->get('/blog/category/veritatis-distinctio');
        $response2->assertStatus(301);
        $response2->assertRedirect('/blog/category/fitness');
    }

    public function test_sitemap_returns_valid_xml(): void
    {
        $category = BlogCategory::create([
            'name' => 'Indian Diet',
            'slug' => 'indian-diet',
            'status' => true,
        ]);

        $post = BlogPost::create([
            'blog_category_id' => $category->id,
            'title' => 'How to Build Muscle on an Indian Vegetarian Diet',
            'slug' => 'how-to-build-muscle-on-an-indian-vegetarian-diet',
            'excerpt' => 'Guide.',
            'content' => '<p>Guide.</p>',
            'author' => 'DK Singh',
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $this->assertStringContainsString('/blog/how-to-build-muscle-on-an-indian-vegetarian-diet', $response->getContent());
    }
}
