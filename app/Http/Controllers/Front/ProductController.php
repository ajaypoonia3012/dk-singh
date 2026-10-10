<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\OfferService;

class ProductController extends Controller
{
    public function index(OfferService $offerService)
    {
        $products = Product::where('status', 1)->latest()->get();
        $featuredOffer = $offerService->getFeaturedActiveOffer();

        return view('products.index', compact('products', 'featuredOffer'));
    }

    public function show(string $slug, OfferService $offerService)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $availableOffers = $offerService->getValidOffersForProduct($product, auth()->user());
        $featuredOffer = $offerService->getFeaturedActiveOffer();

        return view('products.show', compact('product', 'availableOffers', 'featuredOffer'));
    }
}
