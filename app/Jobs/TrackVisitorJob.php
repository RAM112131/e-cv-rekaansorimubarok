<?php

namespace App\Jobs;

use App\Models\VisitorEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TrackVisitorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $sessionHash,
        protected string $pagePath,
        protected ?string $referrerHost,
    ) {}

    public function handle(): void
    {
        VisitorEvent::create([
            'session_hash' => $this->sessionHash,
            'page_path' => $this->pagePath,
            'referrer_host' => $this->referrerHost,
            'visited_at' => now(),
        ]);
    }
}
