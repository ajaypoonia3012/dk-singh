<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class PurchaseController extends Controller
{
    public function checkout($id)
    {
        $product = Product::findOrFail($id);

        return view(
            'checkout.product',
            compact('product')
        );
    }
}