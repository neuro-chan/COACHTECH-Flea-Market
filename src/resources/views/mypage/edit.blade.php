@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/mypage/profile.css') }}">
@endpush

@section('content')
    <div class="profile">
        <div class="profile__card">
            <h1 class="profile__title">プロフィール設定</h1>
            <form method="POST"
                action="{{ $user->profile ? route('mypage.profile.update') : route('mypage.profile.store') }}"
                class="profile__form" enctype="multipart/form-data" novalidate>
                @csrf
                @if ($user->profile)
                    @method('PUT')
                @endif

                {{-- プロフィールエリア --}}
                <div class="profile__field">
                    <div class="image-upload">
                        <div class="image-upload__preview">
                            @if ($user->profile?->profile_image_url)
                                <img src="{{ $user->profile->profile_image_url }}" alt="プロフィール画像" class="image-upload__img">
                            @else
                                <div class="image-upload__placeholder"></div>
                            @endif
                        </div>

                        <label for="profile_image" class="image-upload__button">
                            画像を選択する
                        </label>
                    </div>

                    <input type="file" id="profile_image" name="profile_image"
                        class="image-upload__input @error('profile_image') profile__input--error @enderror"
                        accept="image/*">

                    @error('profile_image')
                        <span class="profile__error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- フォームエリア --}}
                <div class="profile__field">
                    <label class="profile__label">ユーザー名</label>
                    <input type="text" name="name" class="profile__input" value="{{ $user->name }}">
                </div>

                <div class="profile__field">
                    <label class="profile__label">郵便番号</label>
                    <input type="text" name="postal_code"
                        class="profile__input @error('postal_code') profile__input--error @enderror"
                        value="{{ old('postal_code', $user->profile->postal_code ?? '') }}" placeholder="123-4567">
                    @error('postal_code')
                        <span class="profile__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="profile__field">
                    <label class="profile__label">住所</label>
                    <input type="text" name="address"
                        class="profile__input @error('address') profile__input--error @enderror"
                        value="{{ old('address', $user->profile->address ?? '') }}" placeholder="東京都渋谷区代々木1-2-3">
                    @error('address')
                        <span class="profile__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="profile__field">
                    <label class="profile__label">建物名</label>
                    <input type="text" name="building"
                        class="profile__input @error('building') profile__input--error @enderror"
                        value="{{ old('building', $user->profile->building ?? '') }}" placeholder="代々木ビル101">
                    @error('building')
                        <span class="profile__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="profile__button">
                    {{ $user->profile ? '更新する' : '登録する' }}
                </button>
            </form>
        </div>
    </div>
@endsection
