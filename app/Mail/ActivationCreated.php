<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Activation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;


class ActivationCreated extends Mailable
{
    use Queueable, SerializesModels;

    protected $activation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Activation $activation)
    {
        //
        $this->activation = $activation;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'アカウント登録のご案内',
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
        $activation_created_at = Carbon::parse($this->activation->created_at);
        $expire_at = $activation_created_at->addDay(1);
        return new Content(
            text: 'emails.activations.created',
            with: [
                'url' => $apiUrl . "/register/verify?code={$this->activation->code}",
                'user_name' => $this->activation->user_name,
                'expire_at' => $expire_at,
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
