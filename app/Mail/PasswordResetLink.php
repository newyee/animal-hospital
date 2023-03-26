<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetLink extends Mailable
{
    use Queueable, SerializesModels;

    protected $userName;
    protected $code;
    protected $expiresAt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userName, $code, $expiresAt)
    {
        //
        $this->userName = $userName;
        $this->code = $code;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'パスワード再設定のご案内',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {

        $apiUrl = config('app.url');

        return new Content(
            text: 'emails.activations.password_reset',
            with: [
                'url' => $apiUrl . "/user/password/verify?token={$this->code}",
                'user_name' => $this->userName,
                'expires_at' => $this->expiresAt,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
