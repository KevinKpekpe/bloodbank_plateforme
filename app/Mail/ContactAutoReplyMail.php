<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class ContactAutoReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $subject;
    public $messageContent;
    public $dateTime;
    public $referenceNumber;

    /**
     * Create a new message instance.
     */
    public function __construct($contactData)
    {
        $this->name = $contactData['name'];
        $this->subject = $contactData['subject'];
        $this->messageContent = $contactData['message'];
        $this->dateTime = Carbon::now()->format('d/m/Y à H:i');
        $this->referenceNumber = strtoupper(substr(md5($contactData['name'] . $contactData['subject'] . Carbon::now()->timestamp), 0, 8));
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de votre message - BloodLink',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-auto-reply',
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
