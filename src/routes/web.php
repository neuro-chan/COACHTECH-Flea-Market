<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;



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


// // プロフィール登録未完了ユーザーのリダイレクト
// Route::get('/', function () {
//     //
// })->middleware(['auth', 'verified', 'profile_complete']);


// メール認証済みユーザー専用
Route::middleware(['auth', 'verified'])->group(function () {

    Route::view('/mypage', 'mypage.index')->name('mypage.index');
    Route::view('/mypage/edit', 'mypage.edit')->name('mypage.edit');
    Route::post('/item/{item}/comment', [CommentController::class, 'store'])
        ->name('item.comment.store');
    Route::post('/item/{item}/like', [LikeController::class, 'like'])
        ->name('item.like');
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])
        ->name('purchase.show');
});


// 商品一覧・詳細
Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');


Route::get('/mypage/profile', function () {
    return view('mypage.edit');
})->middleware(['auth', 'verified'])->name('edit.profiles');

