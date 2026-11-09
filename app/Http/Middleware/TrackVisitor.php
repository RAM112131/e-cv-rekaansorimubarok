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
            || ! $request->isMethod('GET')
            || str_contains($requestPurpose, 'prefetch')
        ) {
            return $response;
        }

        $sessionHash = hash_hmac('sha256', $request->session()->getId(), (string) config('app.key'));
        $referrerHost = parse_url((string) $request->header('referer'), PHP_URL_HOST);

        TrackVisitorJob::dispatch(
            $sessionHash,
            $request->getPathInfo(),
            is_string($referrerHost) ? $referrerHost : null,
        )->onConnection('deferred');

        return $response;
    }
}
