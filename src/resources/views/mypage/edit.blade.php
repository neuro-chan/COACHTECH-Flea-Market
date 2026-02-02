@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/mypage/profile.css') }}">
@endpush

@section('content')
    <div class="auth">
        <div class="auth__card">
            <h1 class="auth__title">プロフィール設定</h1>

            <form method="POST" action="{{ route('register') }}" class="auth__form" novalidate>
                @csrf

                <div class="auth__field">
                    <label class="auth__label">ユーザー名</label>
                    <input type="text" name="name" class="auth__input @error('name') auth__input--error @enderror"
                        value="{{ old('name') }}">
                    @error('name')
                        <span class="auth__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth__field">
                    <label class="auth__label">メールアドレス</label>
                    <input type="email" name="email" class="auth__input @error('email') auth__input--error @enderror"
                        value="{{ old('email') }}">
                    @error('email')
                        <span class="auth__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth__field">
                    <label class="auth__label">パスワード</label>
                    <input type="password" name="password"
                        class="auth__input @error('password') auth__input--error @enderror">
                    @error('password')
                        <span class="auth__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth__field">
                    <label class="auth__label">確認用パスワード</label>
                    <input type="password" name="password_confirmation"
                        class="auth__input @error('password_confirmation') auth__input--error @enderror">
                    @error('password_confirmation')
                        <span class="auth__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="auth__button">更新する</button>
            </form>
        </div>
    </div>
@endsection
