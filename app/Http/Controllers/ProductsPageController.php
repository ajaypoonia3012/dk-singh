<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductsPageController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return view('products.show', compact('product'));
    }
}