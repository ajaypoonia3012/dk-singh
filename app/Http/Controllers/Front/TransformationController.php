<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Transformation;

class TransformationController extends Controller
{
    public function index()
    {
        $transformations = Transformation::where('status', true)
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('transformations.index', compact('transformations'));
    }

    public function show($slug)
    {
        $transformation = Transformation::where('status', true)
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug);
                if (is_numeric($slug)) {
                    $q->orWhere('id', (int) $slug);
                }
            })
            ->firstOrFail();

        return view('transformations.show', compact('transformation'));
    }
}
