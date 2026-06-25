<?php

namespace App\Http\Controllers;

use App\Models\Program;

class ProgramsPageController extends Controller
{
    public function index()
    {
        $programs = Program::latest()->get();

        return view('programs.index', compact('programs'));
    }

    public function show($slug)
    {
        $program = Program::where('slug', $slug)->firstOrFail();

        return view('programs.show', compact('program'));
    }
}