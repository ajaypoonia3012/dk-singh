<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $featured = BlogPost::with(['category', 'media'])
            ->published()
            ->where('featured', true)
            ->latest('published_at')
            ->first();

        $posts = BlogPost::with(['category', 'media'])
            ->published()
            ->when($featured, fn (Builder $query) => $query->whereKeyNot($featured->getKey()))
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->latest('published_at')
            ->paginate(8)
            ->withQueryString();

        return view('blog.index', array_merge(compact(
            'featured',
            'posts',
            'search'
        ), $this->sidebarData()));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::with([
            'category',
            'media',
            'tags',
        ])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $post->increment('views');
        $post->refresh();
        $post->loadMissing(['category', 'media', 'tags']);

        $relatedPosts = BlogPost::with(['category', 'media'])
            ->where('blog_category_id', $post->blog_category_id)
            ->where('id', '!=', $post->id)
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', array_merge(compact(
            'post',
            'relatedPosts'
        ), $this->sidebarData()));
    }

    public function category(string $slug): View
    {
        $category = BlogCategory::where('slug', $slug)
            ->firstOrFail();

        $posts = BlogPost::with(['category', 'media'])
            ->where('blog_category_id', $category->id)
            ->published()
            ->latest('published_at')
            ->paginate(9);

        return view('blog.category', array_merge(compact(
            'category',
            'posts'
        ), $this->sidebarData()));
    }

    public function tag(string $slug): View
    {
        $tag = BlogTag::where('slug', $slug)
            ->firstOrFail();

        $posts = $tag->posts()
            ->with(['category', 'media'])
            ->published()
            ->latest('published_at')
            ->paginate(9);

        return view('blog.tag', array_merge(compact(
            'tag',
            'posts'
        ), $this->sidebarData()));
    }

    private function sidebarData(): array
    {
        return [
            'categories' => BlogCategory::query()
                ->where('status', true)
                ->withCount(['posts as published_posts_count' => fn (Builder $query) => $query->published()])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'tags' => BlogTag::query()
                ->whereHas('posts', fn (Builder $query) => $query->published())
                ->withCount(['posts as published_posts_count' => fn (Builder $query) => $query->published()])
                ->orderByDesc('published_posts_count')
                ->orderBy('name')
                ->limit(20)
                ->get(),
            'popularPosts' => BlogPost::query()
                ->with(['category', 'media'])
                ->published()
                ->orderByDesc('views')
                ->latest('published_at')
                ->limit(5)
                ->get(),
        ];
    }
}
