<?php

namespace App\Mail;

use App\Models\ContactLead;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactLeadConfirmation extends Mailable
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
            ->subject('Thank You For Contacting DK Singh Fitness')
            ->view('emails.contact-lead-confirmation');
    }
}