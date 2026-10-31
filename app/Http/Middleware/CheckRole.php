<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login dan rolenya ada di dalam daftar yang diizinkan
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke fitur ini!');
        }

        return $next($request);
    }
}