@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/components/item-card.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/item/item-show.css') }}">
@endpush

@section('content')
    <article class="item__detail">

        <!-- 左カラム：商品画像 -->
        <figure class="item__detail-image">
            <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
        </figure>

        <!-- 右カラム：商品情報 -->
        <div class="item__detail-info">

            <!-- セクション１：商品名・価格・購入導線 -->
            <section class="item__top">
                <h1 class="item__detail-title">{{ $item->title }}</h1>
                <p class="item__detail-brand">{{ $item->brand_name }}</p>
                <p class="item__detail-price">
                    ¥{{ number_format($item->price) }}
                    <span class="item__detail-tax">(税込)</span>
                </p>

                <div class="item__detail-actions">
                    <div class="item__detail-action">
                        <form action="{{ route('item.like', $item) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:none; border:none; padding:0; cursor:pointer;">
                                @if (auth()->check() && auth()->user()->likes()->where('item_id', $item->id)->exists())
                                    <img src="{{ asset('asset/images/heart-logo-pink.png') }}" alt="いいね済み"
                                        class="item__detail-favorite">
                                @else
                                    <img src="{{ asset('asset/images/heart-logo-default.png') }}" alt="いいね"
                                        class="item__detail-favorite">
                                @endif
                            </button>
                        </form>
                        <span class="item__detail-count">{{ $item->likes->count() }}</span>
                    </div>

                    <div class="item__detail-action">
                        <img src="{{ asset('asset/images/speech-bubble-logo.png') }}" alt="コメント"
                            class="item__detail-comment-icon">
                        <span class="item__detail-count">{{ $item->comments_count }}</span>
                    </div>
                </div>

                <a href="{{ route('purchase.show', ['item' => $item->id]) }}" class="item__purchase-btn">
                    購入手続きへ
                </a>
            </section>

            <!-- セクション２：商品説明・詳細情報 -->
            <section class="item__detail-section">
                <h2>商品説明</h2>
                <p class="item__detail-description">{{ $item->description }}</p>

                <h2>商品の情報</h2>
                <dl class="item__detail-list">
                    <div class="item__detail-list-item">
                        <dt>カテゴリー</dt>
                        <dd>
                            <ul class="item__category">
                                @foreach ($item->categories as $category)
                                    <li>
                                        <span class="item__category-tag">{{ $category->category_name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                    <div class="item__detail-list-item">
                        <dt>商品の状態</dt>
                        <dd>{{ $item->condition->condition_name }}</dd>
                    </div>
                </dl>
            </section>

            <!-- セクション３：コメント -->
            <section class="item__comments">
                <h2>コメント（{{ $item->comments_count }}）</h2>

                <div class="item__comments-list">
                    @foreach ($item->comments as $comment)
                        <article class="item__comment">
                            <header class="item__comment-user">
                                <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}"
                                    class="item__comment-avatar">
                                <span class="item__comment-username">{{ $comment->user->name }}</span>
                            </header>

                            <p class="item__comment-body">{{ $comment->comment_text }}</p>
                        </article>
                    @endforeach
                </div>

                <h3 class="item__comment-title">商品へのコメント</h3>
                <form action="{{ route('item.comment.store', $item) }}" method="POST">
                    @csrf
                    <textarea name="comment_text" class="item__comment-textarea"></textarea>
                    <button type="submit" class="item__comment-btn">コメントを送信する</button>
                </form>
            </section>

        </div>
    </article>
@endsection
