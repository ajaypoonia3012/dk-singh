<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class AboutController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        return view(
            'about.index',
            compact('setting')
        );
    }
}
