<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Actions\Fortify\CustomLoginValidation;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Gọi validate custom
        (new CustomLoginValidation)($request);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginField, $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'login' => 'Tài khoản hoặc mật khẩu không đúng.',
            ])->withInput();
        }

        Auth::login($user, $request->remember);

        return redirect()->intended('/');
    }
}
