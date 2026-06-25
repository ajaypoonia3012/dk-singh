<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServicesPageController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();

        return view('services.index', compact('services'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();

        return view('services.show', compact('service'));
    }
}