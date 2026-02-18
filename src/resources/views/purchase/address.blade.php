@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/purchase/address.css') }}">
@endpush

@section('content')
    <div class="address">
        <div class="address__card">
            <h1 class="address__title">住所の変更</h1>
            <form method="POST"
                action="{{ $user->profile ? route('mypage.profile.update') : route('mypage.profile.store') }}"
                class="address__form" enctype="multipart/form-data" novalidate>
                @csrf
                @if ($user->profile)
                    @method('PUT')
                @endif

                <div class="address__field">
                    <label class="address__label">郵便番号</label>
                    <input type="text" name="postal_code"
                        class="address__input @error('postal_code') address__input--error @enderror"
                        value="{{ old('postal_code', $user->profile->postal_code ?? '') }}" placeholder="123-4567">
                    @error('postal_code')
                        <span class="address__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="address__field">
                    <label class="address__label">住所</label>
                    <input type="text" name="address"
                        class="address__input @error('address') address__input--error @enderror"
                        value="{{ old('address', $user->profile->address ?? '') }}" placeholder="東京都渋谷区代々木1-2-3">
                    @error('address')
                        <span class="address__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="address__field">
                    <label class="address__label">建物名</label>
                    <input type="text" name="building"
                        class="address__input @error('building') address__input--error @enderror"
                        value="{{ old('building', $user->profile->building ?? '') }}" placeholder="代々木ビル101">
                    @error('building')
                        <span class="address__error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="address__button">
                    {{ $user->profile ? '更新する' : '登録する' }}
                </button>
            </form>
        </div>
    </div>
@endsection
