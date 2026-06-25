<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }


    public function submit(Request $request)
{
    $data = $request->validate([

        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:50',
        'message' => 'required|string',

    ]);

    $lead = \App\Models\ContactLead::create([

        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'] ?? '',
        'message' => $data['message'],
        'status' => 'new',
        'source' => 'website',

    ]);

    \Mail::to(config('mail.from.address'))
        ->send(
            new \App\Mail\ContactLeadNotification($lead)
        );

    \Mail::to($lead->email)
        ->send(
            new \App\Mail\ContactLeadConfirmation($lead)
        );

    return back()->with(
        'success',
        'Message received successfully.'
    );
}
}