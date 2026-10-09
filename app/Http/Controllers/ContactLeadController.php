<?php

namespace App\Http\Controllers;

use App\Models\ContactLead;
use Illuminate\Http\Request;

class ContactLeadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([

            'name' => 'required',

            'email' => 'required|email',

            'phone' => 'required',

            'message' => 'required',

        ]);

        ContactLead::create([

            'name' => $request->name,

            'email' => $request->email,

            'phone' => $request->phone,

            'message' => $request->message,

            'status' => 'new',

        ]);

        return back()->with(
            'success',
            'Thank you! Your inquiry has been submitted successfully.'
        );
    }
}
