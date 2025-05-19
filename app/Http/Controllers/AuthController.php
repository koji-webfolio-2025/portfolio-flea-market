<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // メール認証が必要な場合
            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout(); // ログインを解除して
                return redirect('/login')->with('message', 'メール認証が必要です');
            }

            // メール認証済
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);

    }

    public function showRegisterForm()
    {
        // 商品一覧ページ表示
        return view('register');
    }

    public function register()
    {
        return view('register');
    }

    public function showLoginForm()
    {
        return view('login');
    }
}
