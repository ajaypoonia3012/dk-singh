<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function index()
    {
        $setting = \App\Models\Setting::first();

        return view(
            'about.index',
            compact('setting')
        );
    }
}