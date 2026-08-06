<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MessageReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Message $messageRecord,
        public string $salutation,
        public string $replyContent,
        public string $closing,
        public string $senderName = 'Ram',
        public ?string $senderHeadline = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: ' . ($this->messageRecord->subject ?? 'Pesan Kontak Portofolio'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.message-reply',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
