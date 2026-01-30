@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/auth/auth.css') }}">
@endpush

@section('content')
    <div>登録していただいたメールアドレスに認証メールを送付しました。
        <br>メール認証を完了してください。
    </div>
    <button type="submit" class="button">認証はこちらから</button>
    <div class="auth__links">
        <a href="{{ route('login') }}" class="auth__link">
            認証メールを再送する
        </a>
    </div>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="auth__button"><a href="{{ route('edit.profiles') }}" class="auth__button">認証はこちらから</a></button>
    </form>
@endsection
