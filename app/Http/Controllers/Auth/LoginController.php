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
        // Gọi file validate tùy chỉnh
        (new CustomLoginValidation)($request);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email hoặc mật khẩu không đúng.',
            ])->withInput();
        }

        Auth::login($user, $request->remember);

        return redirect()->intended('/'); // hoặc route('client.home')
    }
}
