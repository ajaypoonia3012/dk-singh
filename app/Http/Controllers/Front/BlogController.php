<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $featured = null;
        if (! $search) {
            $featured = BlogPost::with(['category', 'media'])
                ->where('status', true)
                ->where('featured', true)
                ->latest('published_at')
                ->first();
        }

        $query = BlogPost::with(['category', 'media'])
            ->where('status', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = $this->getCategories();
        $tags = $this->getTags();

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

        $relatedPosts = BlogPost::with(['media', 'category'])
            ->where('blog_category_id', $post->blog_category_id)
            ->where('id', '!=', $post->id)
            ->where('status', true)
            ->latest('published_at')
            ->take(3)
            ->get();

        $relatedExercises = \App\Models\Exercise::with('media')
            ->where('status', true)
            ->where(function ($q) use ($post) {
                if ($post->category) {
                    $q->where('exercise_category', 'like', "%{$post->category->name}%");
                }
                $q->orWhere('primary_muscle', 'like', "%{$post->title}%");
            })
            ->take(3)
            ->get();

        if ($relatedExercises->isEmpty()) {
            $relatedExercises = \App\Models\Exercise::with('media')->where('status', true)->take(3)->get();
        }

        $categories = $this->getCategories();
        $tags = $this->getTags();

        $ctaService = app(\App\Services\ArticleCtaService::class);
        $articleCta = $ctaService->forPost($post);

        return view('blog.show', compact(
            'post',
            'relatedPosts',
            'relatedExercises',
            'categories',
            'tags',
            'articleCta'
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

        $categories = $this->getCategories();
        $tags = $this->getTags();

        return view('blog.category', compact(
            'category',
            'posts',
            'categories',
            'tags'
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

        $categories = $this->getCategories();
        $tags = $this->getTags();

        return view('blog.tag', compact(
            'tag',
            'posts',
            'categories',
            'tags'
        ));
    }

    protected function getCategories()
    {
        return BlogCategory::where('status', true)
            ->withCount(['posts' => function ($q) {
                $q->where('status', true);
            }])
            ->orderBy('sort_order')
            ->get();
    }

    protected function getTags()
    {
        return BlogTag::orderBy('name')->get();
    }
}
