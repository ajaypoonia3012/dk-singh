<?php

namespace App\Mail;

use App\Models\ContactLead;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactLeadNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;

    /**
     * Create a new message instance.
     */
    public function __construct(ContactLead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject('New Fitness Inquiry Received')
            ->view('emails.contact-lead-notification');
    }
}