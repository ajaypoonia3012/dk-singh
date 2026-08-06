<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\HeroSetting;
use App\Models\HomepageCard;
use App\Models\Product;
use App\Models\Program;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Transformation;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {

        $programs = Program::latest()->take(6)->get();
        $products = Product::latest()->take(6)->get();

        $services = Service::latest()->take(6)->get();

        $transformations = Transformation::latest()->take(3)->get();

        $testimonials = Testimonial::latest()->take(6)->get();

        $setting = Cache::rememberForever(Setting::CACHE_KEY, fn () => Setting::query()->first());
        $hero = HeroSetting::with('backgroundMedia')->first();
        $homepageCards = HomepageCard::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('home.index', compact(

            'programs',
            'products',
            'services',
            'transformations',
            'testimonials',

            'setting',
            'hero',

            'homepageCards'

        ));
    }
}
