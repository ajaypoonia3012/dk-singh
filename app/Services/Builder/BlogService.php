<?php

namespace App\Services\Builder;

use App\Models\Blog;
use Illuminate\Support\Str;

class BlogService
{
    public function all()
    {
        return Blog::orderBy('sort_order')->get();
    }

    public function active()
    {
        return Blog::where('status', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function find(int $id): Blog
    {
        return Blog::findOrFail($id);
    }

    public function create(): Blog
{
    $title = 'New Blog ' . Str::random(6);

    return Blog::create([

        'title' => $title,

        'slug' => Str::slug($title),

        'content' => '',

        'excerpt' => '',

        'category' => '',

        'author' => 'DK Singh',

        'reading_time' => '5 min read',

        'featured_image' => null,

        'featured' => false,

        'status' => true,

        'sort_order' => Blog::max('sort_order') + 1,

        'seo_title' => '',

        'seo_description' => '',

    ]);
}
    public function update(Blog $blog, array $data): Blog
    {
        if (
            isset($data['title']) &&
            $blog->title !== $data['title']
        ) {
            $data['slug'] = Str::slug($data['title']);
        }

        $blog->update($data);

        return $blog->fresh();
    }

    public function delete(Blog $blog): void
    {
        $blog->delete();

        Blog::orderBy('sort_order')
            ->get()
            ->values()
            ->each(function ($item, $index) {

                $item->update([
                    'sort_order' => $index + 1,
                ]);

            });
    }

    public function duplicate(Blog $blog): Blog
    {
        $copy = $blog->replicate();

        $copy->title .= ' Copy';

        $copy->slug = Str::slug($copy->title);

        $copy->sort_order = Blog::max('sort_order') + 1;

        $copy->save();

        return $copy;
    }

    public function toggleStatus(Blog $blog): Blog
    {
        $blog->update([
            'status' => !$blog->status,
        ]);

        return $blog->fresh();
    }

    public function toggleFeatured(Blog $blog): Blog
    {
        $blog->update([
            'featured' => !$blog->featured,
        ]);

        return $blog->fresh();
    }
}