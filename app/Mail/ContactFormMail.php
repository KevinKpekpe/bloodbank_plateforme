<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $subject;
    public $messageContent;
    public $userType;
    public $dateTime;

    /**
     * Create a new message instance.
     */
    public function __construct($contactData)
    {
        $this->name = $contactData['name'];
        $this->email = $contactData['email'];
        $this->subject = $contactData['subject'];
        $this->messageContent = $contactData['message'];
        $this->userType = $contactData['userType'] ?? 'Visiteur';
        $this->dateTime = Carbon::now()->format('d/m/Y H:i');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouveau message de contact - BloodLink',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-form',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
