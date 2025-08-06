<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            // Logout pengguna
            Auth::logout();
            // Hapus sesi
            Session::flush();
            // Flash pesan error
            session()->flash('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            // Arahkan ke halaman login
            return redirect()->back();
        }

        return $next($request);
    }
}
