<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NewsletterSubscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $contact;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Contact $contact)
    {
        $this->user = $user;
        $this->contact = $contact;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('🌿 Welcome to Tia Inday Haven Resort Newsletter!')
            ->markdown('notifications.newsletter_welcome')
            ->with(['user' => $this->user, 'contact' => $this->contact]);
    }
}
