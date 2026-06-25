<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Product;
use App\Models\Program;
use App\Models\Service;

class SitemapController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->get();

        $services = Service::latest()->get();

        $programs = Program::latest()->get();

        $products = Product::latest()->get();

        $xml = view('sitemap', compact(
            'blogs',
            'services',
            'programs',
            'products'
        ))->render();

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}