<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketRepliedMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public readonly Ticket $ticket,
        public readonly TicketMessage $ticketMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Re: [{$this->ticket->number}] {$this->ticket->subject}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.ticket-replied',
        );
    }
}
