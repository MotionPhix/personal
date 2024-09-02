<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmationMail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(public string $token, public Subscriber $user) {}

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Confirm Subscription',
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {

    $confirmationUrl = url('subscribe/' . $this->token . '/' . $this->user->email);

    // Embed the image and get the CID
    $logoCid = public_path('ultrashots_logo.png');

    return new Content(
      view: 'emails/confirmation-mail',
      with: [
        'confirmationUrl' => $confirmationUrl,
        'logoCid' => $logoCid,
      ]
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
