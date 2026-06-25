<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Service;
use App\Models\Transformation;
use App\Models\Testimonial;
use App\Models\Setting;
use App\Models\Program;

class HomeController extends Controller
{
    public function index()
    {
$programs = Program::latest()->take(6)->get();
        $products = Product::latest()->take(6)->get();

        $services = Service::latest()->take(6)->get();

       $transformations = Transformation::latest()->take(3)->get();

        $testimonials = Testimonial::latest()->take(6)->get();

        $setting = Setting::first();

        return view('home.index', compact(
            'programs',
            'products',
            'services',
            'transformations',
            'testimonials',
            'setting'

        ));
    }
}