<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) return redirect()->route('show.login')->with('error', 'Silakan masuk untuk melanjutkan.');
        if (auth()->user()->role !== $role) return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        return $next($request);
    }
}
