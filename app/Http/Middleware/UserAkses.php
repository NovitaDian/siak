<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
{
    // Cek apakah pengguna terautentikasi
    if (!auth()->check()) {
        return redirect()->route('login'); // Redirect ke halaman login jika pengguna belum login
    }

    // Cek apakah role pengguna ada dan sesuai dengan role yang diinginkan
    if (auth()->user()->role === $role) {
        return $next($request); // Jika role sesuai, lanjutkan request
    }

    // Jika role kosong atau tidak sesuai, logout dan redirect ke halaman login
    auth()->logout();
    return redirect()->route('login'); // Arahkan ke halaman login
}
}
