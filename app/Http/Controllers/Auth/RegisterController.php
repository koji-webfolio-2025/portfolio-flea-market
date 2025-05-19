<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class RegisterController extends Controller
{
    protected $creator;

    public function __construct(CreateNewUser $creator)
    {
        $this->creator = $creator;
    }

    public function store(RegisterRequest $request)
    {
        $user = $this->creator->create($request->all());
        Auth::login($user);

        // VerifyEmailの通知を手動で送信(テストケース用)
        //Notification::send($user, new VerifyEmail($user));

        return redirect()->route('verification.notice');
    }
}
