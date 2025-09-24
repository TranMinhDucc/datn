<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfBanned
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->banned == 1) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'login' => 'Tài khoản của bạn đã bị khóa, vui lòng đăng nhập lại.'
            ]);
        }

        return $next($request);
    }
}