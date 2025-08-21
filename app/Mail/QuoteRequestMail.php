<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class QuoteRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Quote $quote
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Quote Request - ' . $this->quote->formatted_project_type,
            replyTo: [
                ['address' => $this->quote->email, 'name' => $this->quote->name],
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.quote-request',
            with: [
                'quote' => $this->quote,
                'files' => $this->quote->getMedia('reference_files'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->quote->getMedia('reference_files') as $media) {
            $attachments[] = Attachment::fromPath($media->getPath())
                ->as($media->name)
                ->withMime($media->mime_type);
        }

        return $attachments;
    }
}
