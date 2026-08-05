<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;

class BlogController extends Controller
{
    public function index()
    {
        $featured = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->where('featured', true)
            ->latest('published_at')
            ->first();

        $posts = BlogPost::with(['category', 'media'])
            ->where('status', true)
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogCategory::where('status', true)
            ->orderBy('sort_order')
            ->get();

        $tags = BlogTag::orderBy('name')->get();

        return view('blog.index', compact(
            'featured',
            'posts',
            'categories',
            'tags'
        ));
    }

    public function show(string $slug)
    {
        $post = BlogPost::with([
                'category',
                'media',
                'tags',
            ])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        $post->increment('views');

        $relatedPosts = BlogPost::with(['media'])
            ->where('blog_category_id', $post->blog_category_id)
            ->where('id', '!=', $post->id)
            ->where('status', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact(
            'post',
            'relatedPosts'
        ));
    }

    public function category(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)
            ->firstOrFail();

        $posts = BlogPost::with(['category', 'media'])
            ->where('blog_category_id', $category->id)
            ->where('status', true)
            ->latest('published_at')
            ->paginate(9);

        return view('blog.category', compact(
            'category',
            'posts'
        ));
    }

    public function tag(string $slug)
    {
        $tag = BlogTag::where('slug', $slug)
            ->firstOrFail();

        $posts = $tag->posts()
            ->with(['category', 'media'])
            ->where('status', true)
            ->latest('published_at')
            ->paginate(9);

        return view('blog.tag', compact(
            'tag',
            'posts'
        ));
    }
}