<?php

namespace App\Services;

use App\Models\Message;
use Illuminate\Support\Facades\Cache;

class ContactMessageService
{
    /**
     * @param  array{name: string, email: string, subject?: string|null, message: string}  $data
     */
    public function store(array $data): Message
    {
        $message = Message::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'is_read' => false,
        ]);

        Cache::forget('nav.badge.unread_messages');

        return $message;
    }
}
