{{-- resources/views/mypage/index.blade.php --}}

@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/components/item-card.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/mypage/mypage.css') }}">
@endpush

@section('content')
    <div class="mypage">
        {{-- プロフィールエリア --}}
        <div class="mypage__profile">
            <div class="mypage__profile-avatar">
                @if($user->profile?->profile_image_url)
                    <img src="{{ $user->profile->profile_image_url }}"
                         alt="プロフィール画像"
                         class="mypage__profile-image">
                @else
                    <div class="mypage__profile-placeholder"></div>
                @endif
            </div>

            <h2 class="mypage__username">{{ $user->name }}</h2>

            <a href="{{ route('mypage.profile.edit') }}" class="mypage__profile-edit">
                プロフィールを編集
            </a>
        </div>

        {{-- タブ --}}
        <div class="mypage__tab">
            <ul class="mypage__tab-list">
                <li @class(['mypage__tab-item', 'is-active' => $tab === 'selling'])>
                    <a href="{{ route('mypage.index', ['tab' => 'selling']) }}">
                        出品した商品
                    </a>
                </li>
                <li @class(['mypage__tab-item', 'is-active' => $tab === 'purchased'])>
                    <a href="{{ route('mypage.index', ['tab' => 'purchased']) }}">
                        購入した商品
                    </a>
                </li>
            </ul>
        </div>

        {{-- 商品一覧 --}}
        <div class="mypage__content">
            <div class="items-grid">
                @forelse ($items as $item)
                    <x-item-card :item="$item" />
                @empty
                    <p class="items-empty">
                        @if($tab === 'purchased')
                            購入した商品がありません
                        @else
                            出品した商品がありません
                        @endif
                    </p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
