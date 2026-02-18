@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/item/item-create.css') }}">
@endpush

@section('content')
    <div class="sell">
        <div class="sell__card">
            <h1 class="sell__title">商品の出品</h1>

            <form method="POST" action="{{ route('items.store') }}" class="sell__form" enctype="multipart/form-data"
                novalidate>
                @csrf

                {{-- 商品画像 --}}
                <div class="sell__field">
                    <label class="sell__label">商品画像</label>

                    <div class="image-upload">
                        <div class="image-upload__preview" id="imagePreview" style="display: none;">
                            <img id="previewImg" src="" alt="商品画像プレビュー" class="image-upload__img">
                        </div>

                        <div class="image-upload__box" id="uploadBox">
                            <label for="item_image" class="image-upload__button">
                                画像を選択する
                            </label>
                        </div>
                    </div>

                    <input type="file" id="item_image" name="item_image" class="image-upload__input"
                        accept="image/jpeg,image/png">

                    @error('item_image')
                        <span class="sell__error-message">{{ $message }}</span>
                    @enderror
                </div>

                {{-- 商品の詳細 --}}
                <section class="sell__details-section">
                    <h2 class="sell__section-title">商品の詳細</h2>

                    {{-- カテゴリー --}}
                    <div class="sell__field">
                        <label class="sell__label">カテゴリー</label>
                        <div class="category-tags">
                            @foreach ($categories as $category)
                                <label class="category-tag">
                                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                        {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}
                                        class="category-tag__input">
                                    <span class="category-tag__label">{{ $category->category_name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('category_ids')
                            <span class="sell__error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 商品の状態 --}}
                    <div class="sell__field">
                        <label class="sell__label">商品の状態</label>
                        <select name="condition_id"
                            class="sell__select @error('condition_id') sell__select--error @enderror">
                            @foreach ($conditions as $condition)
                                <option value="{{ $condition->id }}"
                                    {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                                    {{ $condition->condition_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('condition_id')
                            <span class="sell__error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </section>

                {{-- 商品名と説明 --}}
                <section class="sell__description-section">
                    <h2 class="sell__section-title">商品名と説明</h2>

                    {{-- 商品名 --}}
                    <div class="sell__field">
                        <label class="sell__label">商品名</label>
                        <input type="text" name="title"
                            class="sell__input @error('title') sell__input--error @enderror" value="{{ old('title') }}"
                            placeholder="">
                        @error('title')
                            <span class="sell__error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- ブランド名 --}}
                    <div class="sell__field">
                        <label class="sell__label">ブランド名</label>
                        <input type="text" name="brand_name"
                            class="sell__input @error('brand_name') sell__input--error @enderror"
                            value="{{ old('brand_name') }}" placeholder="">
                        @error('brand_name')
                            <span class="sell__error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 商品の説明 --}}
                    <div class="sell__field">
                        <label class="sell__label">商品の説明</label>
                        <textarea name="description" id="description" rows="6"
                            class="sell__textarea @error('description') sell__textarea--error @enderror" placeholder="">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="sell__error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- 販売価格 --}}
                    <div class="sell__field">
                        <label class="sell__label">販売価格</label>
                        <div class="price-input">
                            <span class="price-input__symbol">¥</span>
                            <input type="number" name="price"
                                class="price-input__field @error('price') price-input__field--error @enderror"
                                value="{{ old('price') }}">
                        </div>
                        @error('price')
                            <span class="sell__error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </section>


                {{-- 出品ボタン --}}
                <button type="submit" class="sell__button">
                    出品する
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // 画像プレビュー機能
        document.getElementById('item_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                    document.getElementById('uploadBox').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
