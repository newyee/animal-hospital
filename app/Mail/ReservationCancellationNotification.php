<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Pet;
use App\Models\PetType;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class ReservationCancellationNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected Pet $petData;
    protected string $displayDate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Pet $petData, $displayDate,)
    {
        //
        $this->petData = $petData;
        $this->displayDate = $displayDate;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: '予約キャンセル完了のお知らせ',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $petType = PetType::find($this->petData->pet_type_id)->name;
        return new Content(
            text: 'emails.reservations.cancel',
            with: [
                'petName' => $this->petData->name,
                'petType' => $petType,
                'date' => $this->displayDate,
            ]
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
