@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/purchase/create.css') }}">
@endpush

@section('content')
    <div class="purchase">
        <div class="purchase__container">
            {{-- 左カラム：商品情報・フォーム --}}
            <div class="purchase__main">
                {{-- 商品情報 --}}
                <div class="purchase__item">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="purchase__item-image">
                    <div class="purchase__item-info">
                        <h2 class="purchase__item-title">{{ $item->title }}</h2>
                        <p class="purchase__item-price">¥ {{ number_format($item->price) }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('purchase.checkout') }}" class="purchase__form" id="purchase__form">
                    @csrf

                    <input type="hidden" name="item_id" value="{{ $item->id }}">

                    {{-- 支払い方法 --}}
                    <div class="purchase__section">
                        <h3 class="purchase__section-title">支払い方法</h3>
                        <select name="payment_method"
                            class="purchase__select @error('payment_method') purchase__select--error @enderror" required>
                            <option value="">選択してください</option>
                            <option value="konbini" {{ old('payment_method') == 'konbini' ? 'selected' : '' }}>コンビニ払い
                            </option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>カード払い</option>
                        </select>
                        @error('payment_method')
                            <span class="purchase__error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 配送先 --}}
                    <div class="purchase__section">
                        <div class="purchase__section-header">
                            <h3 class="purchase__section-title">配送先</h3>
                            <a href="{{ route('address.edit', $item->id) }}" class="purchase__change-link">変更する</a>
                        </div>
                        <div class="purchase__address">
                            @if ($user->profile)
                                <p class="purchase__address-text">〒 {{ $user->profile->postal_code }}</p>
                                <p class="purchase__address-text">{{ $user->profile->address }}</p>
                                @if ($user->profile->building)
                                    <p class="purchase__address-text">{{ $user->profile->building }}</p>
                                @endif
                            @else
                                <p class="purchase__address-text">配送先が登録されていません</p>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- 右カラム：購入情報カード --}}
            <div class="purchase__summary">
                <div class="purchase__summary-card">
                    <table class="purchase__summary-table">
                        <tr class="purchase__summary-row">
                            <td class="purchase__summary-label">商品代金</td>
                            <td class="purchase__summary-value">¥ {{ number_format($item->price) }}</td>
                        </tr>
                        <tr class="purchase__summary-row">
                            <td class="purchase__summary-label">支払い方法</td>
                            <td class="purchase__summary-value" id="paymentMethodDisplay">-</td>
                        </tr>
                    </table>
                </div>
                <button type="submit" form="purchase__form" class="purchase__button">
                    購入する
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const paymentSelect = document.querySelector('select[name="payment_method"]');
        const paymentDisplay = document.getElementById('paymentMethodDisplay');

        if (paymentSelect) {
            paymentSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                paymentDisplay.textContent = selectedOption.value ? selectedOption.text : '-';
            });

            if (paymentSelect.value) {
                const selectedOption = paymentSelect.options[paymentSelect.selectedIndex];
                paymentDisplay.textContent = selectedOption.text;
            }
        }
    </script>
@endpush
