<?php

namespace App\Jobs;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TrackVisitorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $ipAddress,
        protected ?string $userAgent,
        protected string $pageUrl,
        protected ?string $referrer,
        protected string $sessionId,
    ) {}

    public function handle(): void
    {
        Visitor::create([
            'ip_address'  => $this->ipAddress,
            'user_agent'  => $this->userAgent,
            'page_url'    => $this->pageUrl,
            'referrer'    => $this->referrer,
            'session_id'  => $this->sessionId,
            'visited_at'  => now(),
        ]);
    }
}
