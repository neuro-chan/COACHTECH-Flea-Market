<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('item.index');
});

Route::get('/auth/verify-email', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/mypage/profile', function () {
    return view('mypage.edit');
})->middleware(['auth', 'verified'])->name('edit.profiles');
