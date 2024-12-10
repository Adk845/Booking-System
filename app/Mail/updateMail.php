<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class updateMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data_email;
    public function __construct($data_email, $ics_filename)
    {
        $this->data_email = $data_email;
        $this->ics_filename = $ics_filename; // Menyimpan path file ICS
    }

    public function build()
    {
        return $this->subject($this->data_email['subject'])
                    ->from($this->data_email['sender_email'], $this->data_email['sender_name'])
                    ->view('mail.updateEmail')
                    ->attach($this->ics_filename, [
                        'as' => 'meeting_event.ics',
                        'mime' => 'text/calendar',
                    ]);
    }

    /**
     * Get the message envelope.
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
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail\updateEmail',
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
