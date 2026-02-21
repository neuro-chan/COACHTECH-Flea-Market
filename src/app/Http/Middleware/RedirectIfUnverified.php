<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfUnverified
{
    public function handle(Request $request, Closure $next)
    {
        // ログイン済み かつ 未認証なら認証誘導へ
        if ($request->user() && ! $request->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice'); // /auth/verify-email
        }

        // ゲスト or 認証済みは通す
        return $next($request);
    }
}
