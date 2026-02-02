<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\MypageController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


Route::view('/', 'item.index')->name('item');

// ゲストユーザー専用
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
});

// メール未認証ユーザー専用
Route::view('/auth/verify-email', 'auth.verify-email')
    ->middleware(['auth', 'not_verified'])
    ->name('verification.notice');


// 認証メール再送信
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'ご登録のメールアドレス宛に、認証メールを再送信しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// 認証後プロフ登録画面へリダイレクト
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');


// プロフィール登録未完了ユーザーのリダイレクト
Route::get('/', function () {
    //
})->middleware(['auth', 'verified', 'profile_complete']);


// メール認証済みユーザー専用
Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/mypage', 'mypage.index')->name('mypage.index');
    Route::view('/mypage/edit', 'mypage.edit')->name('mypage.edit');

    Route::view('/settings', 'settings')->name('settings');
});


// // Route::get('/mypage/profile', function () {
// //     return view('mypage.edit');
// // })->middleware(['auth', 'verified'])->name('edit.profiles');


// Route::get('/mypage/profile', [MypageController::class, 'index'])->name('profiles.index');

