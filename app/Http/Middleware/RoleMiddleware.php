<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        // Pastikan pengguna sudah login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Ambil role pengguna
        $userRole = strtolower(trim($request->user()->role));

        // Samakan format role yang diizinkan
        $allowedRoles = array_map(
            fn ($role) => strtolower(trim($role)),
            $roles
        );

        // Periksa hak akses
        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        // Lanjutkan ke halaman tujuan
        return $next($request);
    }
}