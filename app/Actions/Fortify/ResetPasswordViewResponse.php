<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\ResetPasswordViewResponse as ResetPasswordViewResponseContract;

class ResetPasswordViewResponse implements ResetPasswordViewResponseContract
{
    public function toResponse($request)
    {
        return view('auth.reset-password', [
            'token' => $request->route('token'),
            'email' => $request->email,
        ]);
    }
}
