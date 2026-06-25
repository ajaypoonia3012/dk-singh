<?php

namespace App\Http\Controllers;

use App\Models\Transformation;

class TransformationPageController extends Controller
{
    public function index()
    {
        $transformations = Transformation::latest()->get();

        return view('transformations.index', compact('transformations'));
    }
}