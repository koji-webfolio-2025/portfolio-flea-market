<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                if (!$user->hasVerifiedEmail()) {
                    // メールが未認証の場合、ログアウトしてリダイレクト
                    Auth::logout();
                    return redirect('/login')->with('message', 'メール認証が必要です');
                }

                // RouteServiceProvider が機能しない場合、直接 "/mypage" にリダイレクト
                return redirect('/mypage');
            }
        }

        return $next($request);
    }
}
