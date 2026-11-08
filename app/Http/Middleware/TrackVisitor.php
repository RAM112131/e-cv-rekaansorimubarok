<?php

namespace App\Http\Middleware;

use App\Jobs\TrackVisitorJob;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Jangan catat request admin, API, atau aset statis
        $requestPurpose = strtolower(implode(' ', [
            (string) $request->header('Purpose'),
            (string) $request->header('Sec-Purpose'),
        ]));

        if (
            $request->is('admin*')
            || $request->is('livewire*')
            || $request->ajax()
            || str_contains($requestPurpose, 'prefetch')
        ) {
            return $response;
        }

        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');
        $pageUrl = $request->fullUrl();
        $referrer = $request->header('referer');
        $sessionId = $request->session()->getId();

        TrackVisitorJob::dispatch(
            $ipAddress,
            $userAgent,
            $pageUrl,
            $referrer,
            $sessionId,
        )->onConnection('deferred');

        return $response;
    }
}
