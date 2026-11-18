<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan pengguna sudah login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Pastikan pengguna memiliki role admin
        if ($request->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        return $next($request);
    }
}
