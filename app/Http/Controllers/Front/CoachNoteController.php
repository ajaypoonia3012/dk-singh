<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CoachNote;

class CoachNoteController extends Controller
{
    public function index()
    {
        $notes = CoachNote::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'is_visible',
            true
        )
        ->latest()
        ->get();

        return view(
            'member.coach-notes.index',
            compact('notes')
        );
    }
}