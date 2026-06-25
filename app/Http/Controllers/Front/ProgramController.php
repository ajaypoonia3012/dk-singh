<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Program;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::latest()->get();

        return view(
            'programs.index',
            compact('programs')
        );
    }

    public function show($slug)
    {
        $program = Program::where(
            'slug',
            $slug
        )->firstOrFail();

        return view(
            'programs.show',
            compact('program')
        );
    }
}