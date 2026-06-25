<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Transformation;

class TransformationController extends Controller
{
    public function index()
    {
        $transformations = Transformation::latest()->get();

        return view('transformations.index', compact('transformations'));
    }

    public function show($id)
    {
        $transformation = Transformation::findOrFail($id);

        return view('transformations.show', compact('transformation'));
    }
}