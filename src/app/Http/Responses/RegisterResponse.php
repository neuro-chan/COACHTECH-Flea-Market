<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        // 登録直後は認証誘導へ
        return redirect()->route('verification.notice'); // /auth/verify-email
    }
}
