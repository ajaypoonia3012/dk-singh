<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Product;
use App\Models\Program;
use App\Models\Service;

class SitemapController extends Controller
{
    public function index()
    {
        $blogs = BlogPost::where('status', true)->latest('published_at')->get();

        $services = Service::latest()->get();

        $programs = Program::latest()->get();

        $products = Product::latest()->get();

        $exercises = \App\Models\Exercise::published()->latest()->get();

        $xml = view('sitemap', compact(
            'blogs',
            'services',
            'programs',
            'products',
            'exercises'
        ))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}
