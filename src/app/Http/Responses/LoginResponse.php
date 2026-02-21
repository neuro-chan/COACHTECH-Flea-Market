<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // 未認証なら認証誘導画面へ
        if ($request->user() && ! $request->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice'); // /auth/verify-email
        }

        // 認証済みなら通常の遷移先へ
        return redirect()->intended(config('fortify.home'));
    }
}
