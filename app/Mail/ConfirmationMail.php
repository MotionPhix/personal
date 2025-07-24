<?php

namespace App\Mail;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class ConfirmationMail extends Mailable implements ShouldQueue
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(
    public string $token,
    public Subscriber $user
  ) {
    // Set queue connection and delay if needed
    $this->onQueue('emails');
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      from: new Address(config('mail.from.address'), config('mail.from.name')),
      replyTo: [
        new Address(config('mail.from.address'), config('mail.from.name')),
      ],
      subject: 'Confirm your newsletter subscription',
      tags: ['newsletter', 'confirmation', 'subscription'],
      metadata: [
        'subscriber_email' => $this->user->email,
        'confirmation_token' => $this->token,
        'timestamp' => now()->toISOString(),
      ],
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    // Generate confirmation URL
    $confirmationUrl = url('subscribe/' . $this->token . '/' . $this->user->email);

    // Get logo path with fallback
    $logoPath = public_path('ultrashots_logo.png');
    if (!file_exists($logoPath)) {
      $logoPath = public_path('images/logo.png'); // Fallback
    }

    return new Content(
      view: 'emails.confirmation-mail',
      with: [
        'confirmationUrl' => $confirmationUrl,
        'logoCid' => $logoPath,
        'subscriber' => $this->user,
        'token' => $this->token,
        'app_name' => config('app.name'),
        'app_url' => config('app.url'),
        'expires_at' => now()->addHours(24), // Link expires in 24 hours
        'support_email' => config('mail.from.address'),
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

  /**
   * Determine the time at which the job should timeout.
   */
  public function retryUntil(): \DateTime
  {
    return now()->addMinutes(5);
  }

  /**
   * Calculate the number of seconds to wait before retrying the job.
   */
  public function backoff(): array
  {
    return [1, 5, 10];
  }
}
