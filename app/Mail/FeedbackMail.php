<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeedbackMail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(
    public $data
  ) {}

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(

      subject: 'Thank you for contacting me!',

    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    // Embed the image and get the CID
    $logo = public_path('ultrashots_logo.png');

    return new Content(
      view: 'emails.feedback-mail',

      with: [
        'name' => $this->data['name'],

        'phone' => $this->data['phone'],

        'email' => $this->data['email'],

        'company' => $this->data['company'],

        'logo' => $logo,
      ],
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
