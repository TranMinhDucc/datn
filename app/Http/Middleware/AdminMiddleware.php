<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Nếu chưa đăng nhập hoặc không phải admin thì redirect
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(404);
        }

        return $next($request);
    }
}
