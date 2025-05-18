<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserSlugMatches
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeSlug = $request->route('user');
        if ($routeSlug !== auth()->user()->slug) return redirect()->route('show.notification', auth()->user()->slug)->with('error', 'Notifikasi tidak ditemukan.');
        return $next($request);
    }
}
