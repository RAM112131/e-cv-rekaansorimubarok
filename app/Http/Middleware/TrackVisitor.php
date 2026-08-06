<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
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

        try {
            Visitor::create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'page_url' => $request->fullUrl(),
                'referrer' => $request->header('referer'),
                'session_id' => $request->session()->getId(),
                'visited_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Log silent jika terjadi kegagalan pencatatan agar tidak mengganggu pengunjung
        }

        return $response;
    }
}
