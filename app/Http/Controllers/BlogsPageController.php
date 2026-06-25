<?php

namespace App\Http\Controllers;

use App\Models\Blog;

class BlogsPageController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->get();

        return view('blog.index', compact('blogs'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        return view('blog.show', compact('blog'));
    }
}