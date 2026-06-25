<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactLead;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',

        ]);

        ContactLead::create([

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,

            'status' => 'new',
            'source' => 'Website',

        ]);

        return back()->with('success', 'Inquiry submitted successfully.');
    }
}