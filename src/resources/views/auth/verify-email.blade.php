@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/auth/auth.css') }}">
@endpush

@section('content')
    <div class="auth">
        <div class="email-verify__actions">
            <p class="email-verify__message">登録していただいたメールアドレスに認証メールを送付しました。
                <br>メール認証を完了してください。
            </p>
            <a href="http://localhost:8025" target="_blank" class="email-verify__button">
                認証はこちらから
            </a>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="email-verify__resend">認証メールを再送する</button>
            </form>
            @if (session('message'))
                <p class="email-verify__notice success">
                    {{ session('message') }}
                </p>
            @endif
        </div>

    </div>
    </div>
@endsection
