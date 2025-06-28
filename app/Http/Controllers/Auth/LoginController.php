<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Actions\Fortify\CustomLoginValidation;
use Jenssegers\Agent\Agent;
use App\Models\UserActivityLog;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        (new CustomLoginValidation)($request);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($loginField, $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'login' => 'Tài khoản hoặc mật khẩu không đúng.',
            ])->withInput();
        }
        Auth::login($user, $request->remember);

        // ➤ Ghi lại thiết bị và IP
        $agent = new Agent();
        $deviceInfo = sprintf(
            'Device: %s | OS: %s %s | Browser: %s %s',
            $agent->device() ?: 'Unknown',
            $agent->platform(),
            $agent->version($agent->platform()),
            $agent->browser(),
            $agent->version($agent->browser())
        );

        // ➤ Cập nhật vào bảng users
        $user->update([
            'last_login_ip'     => $request->ip(),
            'last_login_device' => $deviceInfo,
            'last_login_at'     => now(),
        ]);

        // ➤ Ghi vào bảng user_activity_logs
        UserActivityLog::create([
            'username'   => $user->username,
            'action'     => 'Đăng nhập',
            'ip_address' => $request->ip(),
            'user_agent' => $deviceInfo,
        ]);


        return redirect()->intended('/');
    }
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // ➤ Ghi lại hành động đăng xuất
            $agent = new Agent();
            $deviceInfo = sprintf(
                'Device: %s | OS: %s %s | Browser: %s %s',
                $agent->device() ?: 'Unknown',
                $agent->platform(),
                $agent->version($agent->platform()),
                $agent->browser(),
                $agent->version($agent->browser())
            );

            UserActivityLog::create([
                'username'   => $user->username,
                'action'     => 'Đăng xuất',
                'ip_address' => $request->ip(),
                'user_agent' => $deviceInfo,
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // Hoặc route bạn muốn
    }
}
