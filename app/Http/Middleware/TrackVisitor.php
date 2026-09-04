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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Jangan catat request admin, API, atau aset statis
        if ($request->is('admin*') || $request->is('livewire*') || $request->ajax()) {
            return $response;
        }

        // Dispatch ke background job agar tidak memblokir response
        try {
            TrackVisitorJob::dispatch(
                $request->ip(),
                $request->header('User-Agent'),
                $request->fullUrl(),
                $request->header('referer'),
                $request->session()->getId(),
            );
        } catch (\Throwable $e) {
            // Silent fail
        }

        return $response;
    }
}
