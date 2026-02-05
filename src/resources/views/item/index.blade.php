@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/components/item-card.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/item/item-index.css') }}">
@endpush

@section('content')
    <div class="item">
        {{-- タブ --}}
        <div class="item__tab">
            <ul class="item__tab-list">
                <li @class(['item__tab-item', 'is-active' => $tab === 'recommend'])>
                    <a href="{{ route('items.index', ['tab' => 'recommend', 'keyword' => $keyword]) }}">
                        おすすめ
                    </a>
                </li>
                <li @class(['item__tab-item', 'is-active' => $tab === 'mylist'])>
                    <a href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => $keyword]) }}">
                        マイリスト
                    </a>
                </li>
            </ul>
        </div>

        {{-- 商品一覧 --}}
        <div class="item__content">
            <div class="items-grid">
                @forelse ($items as $item)
                    <x-item-card :item="$item" />
                @empty
                    <p class="items-empty">
                        @if($keyword)
                            「{{ $keyword }}」に一致する商品が見つかりませんでした
                        @elseif($tab === 'mylist')
                            @auth
                                いいねした商品がありません
                            @else
                                マイリストを表示するにはログインしてください
                            @endauth
                        @else
                            商品がありません
                        @endif
                    </p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
