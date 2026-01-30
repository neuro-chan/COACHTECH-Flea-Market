@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/auth/auth.css') }}">
@endpush

@section('content')
    <section class="auth">
        <div class="auth__card">
            <h1 class="auth__title">ログイン</h1>

            <form method="POST" action="{{ route('login') }}" class="auth__form">
                @csrf

                <div class="auth__field">
                    <label class="auth__label">メールアドレス</label>
                    <input type="email" name="email" class="auth__input @error('email') auth__input--error @enderror" value="{{ old('email') }}">
                    @error('email')
                        <span class="auth__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="auth__field">
                    <label class="auth__label">パスワード</label>
                    <input type="password" name="password" class="auth__input @error('password') auth__input--error @enderror">
                    @error('password')
                        <span class="auth__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="auth__button">ログインする</button>

                <div class="auth__links">
                    <a href="{{ route('register') }}" class="auth__link">
                        会員登録はこちら
                    </a>
                </div>
            </form>
        </div>
    </section>
@endsection
