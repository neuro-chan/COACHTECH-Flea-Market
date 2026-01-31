@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/components/item-card.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/item/item-list.css') }}">
@endpush

@section('content')
    <main class="item">
        <div class="item__tab">
            <ul class="item__tab-list">
                <li @class(['item__tab-item', 'is-active' => $tab === 'recommend'])>
                    <a href="{{ route('items.index') }}">おすすめ</a>
                </li>
                <li @class(['item__tab-item', 'is-active' => $tab === 'mylist'])>
                    <a href="{{ route('items.index', ['tab' => 'mylist']) }}">マイリスト</a>
                </li>
            </ul>
        </div>
        <div class="item__content">
            <div class="items-grid">
                @forelse ($items as $item)
                    <x-item-card :item="$item" />
                @empty
                    <p class="items-empty">商品がありません</p>
                @endforelse
            </div>
        </div>
    </main>
@endsection
