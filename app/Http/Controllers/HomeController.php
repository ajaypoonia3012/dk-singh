<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Transformation;
use App\Models\Testimonial;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $programs = Program::latest()->get();

        $transformations = Transformation::latest()->get();

        $testimonials = Testimonial::latest()->get();

	$services = Service::latest()->get();

        return view('home.index', compact(
    'programs',
    'transformations',
    'testimonials',
    'services'
));
    }
}