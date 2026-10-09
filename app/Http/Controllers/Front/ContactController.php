<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\ContactLeadConfirmation;
use App\Mail\ContactLeadNotification;
use App\Models\ContactLead;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $selectedService = null;
        if ($request->filled('service')) {
            $selectedService = \App\Models\Service::where('slug', $request->query('service'))->first();
        }

        return view('contact.index', compact('selectedService'));
    }

    public function submit(Request $request)
    {
        $data = $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'service' => 'nullable|string|max:255',
            'message' => 'required|string',

        ]);

        $serviceTitle = $data['service'] ?? null;
        $source = $serviceTitle ? "service: {$serviceTitle}" : 'website';
        $notes = $serviceTitle ? "Interested Service: {$serviceTitle}" : null;
        $finalMessage = $serviceTitle ? "[Interested Service: {$serviceTitle}]\n\n" . $data['message'] : $data['message'];

        $lead = ContactLead::create([

            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? '',
            'message' => $finalMessage,
            'notes' => $notes,
            'status' => 'new',
            'source' => $source,

        ]);

        \Mail::to(config('mail.from.address'))
            ->send(
                new ContactLeadNotification($lead)
            );

        \Mail::to($lead->email)
            ->send(
                new ContactLeadConfirmation($lead)
            );

        return back()->with(
            'success',
            'Message received successfully.'
        );
    }
}
