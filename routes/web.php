<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ✅ トップページ&商品関連
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');
Route::get('/sell', [ItemController::class, 'create'])->middleware('auth', 'verified');
Route::post('/sell', [ItemController::class, 'store'])->middleware('auth', 'verified');

// ✅ いいね関連（LikeController）
Route::post('/item/{item}/like', [LikeController::class, 'store'])->name('item.like')->middleware(['auth', 'verified']);
Route::post('/item/{item}/unlike', [LikeController::class, 'destroy'])->name('item.unlike')->middleware(['auth', 'verified']);

// ✅ コメント
Route::post('/items/{item}/comment', [ItemController::class, 'addComment'])->name('item.comment')->middleware(['auth', 'verified']);

// ✅ 購入関連
Route::get('/purchase/address/{item_id}', [AddressController::class, 'showChangeForm'])->middleware('auth', 'verified');
Route::post('/purchase/address/{item_id}', [AddressController::class, 'updateAddress'])->middleware('auth', 'verified');

// ✅ ユーザープロフィール関連
Route::get('/mypage', [UserController::class, 'show'])->middleware('auth', 'verified');
Route::get('/mypage/profile', [UserController::class, 'edit'])->middleware('auth', 'verified');
Route::post('/mypage/profile', [UserController::class, 'update'])->middleware('auth', 'verified');

// ✅ マイリスト
Route::get('/mylist', [ItemController::class, 'mylist'])->middleware(['auth', 'verified'])->name('items.mylist');

// ✅ Stripe決済
Route::get('/checkout/{item_id}', [StripeController::class, 'checkout'])->middleware(['auth', 'verified'])->name('checkout');
Route::post('/payment', [StripeController::class, 'payment'])->middleware(['auth', 'verified'])->name('payment');
Route::get('/payment/success', [StripeController::class, 'paymentSuccess'])->name('payment.success');

// ✅ メール認証関連
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/login');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('resent', true);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ✅ 会員登録処理
Route::post('/register', [RegisterController::class, 'store']);
