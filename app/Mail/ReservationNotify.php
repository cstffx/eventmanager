<?php

namespace App\Mail;

use App\Models\Reservations;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationNotify extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(private readonly Reservations $reservation)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservation Notify',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build(): ReservationNotify
    {
        $event = $this->reservation->event()->first();

        $content = trans("message.reservation", [
            'username' => $this->reservation->username,
            'title' => $event->title,
        ]);

        // Important note: I chose to use a simple text instead a
        // more complex view for simplicity.
        return $this->text('emails.plain_text')->with(['content' => $content]);
    }
}
