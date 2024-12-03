<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class kirimEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data_email;
    public function __construct($data_email)
    {
        //
        $this->data_email = $data_email;
    }

    /**
     * Get the message envelope.
     * buat custom sender dan subject email
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->data_email['sender_email'], $this->data_email['sender_name'] ),
            subject: $this->data_email['subject'],
        );
    }

    /**
     * Get the message content definition.
     * disini untuk mengatur konten isi email nnya 
     * mail\kirimEmail, itu folder mail yang ada di resources/view/mail/kirimEmail.blade.php
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail\kirimEmail',
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
