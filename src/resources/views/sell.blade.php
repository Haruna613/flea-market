<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-Market</title>
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__item">
            <div class="header__item-logo">
                <img class="header-logo" src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="サイトロゴ">
            </div>
            <div><!--検索機能--></div>
            <div class="header__item-logout">
                @if (Auth::check())
                <form class="logout-form" action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="logout-form__button">ログアウト</button>
                </form>
                @endif
            </div>
            <div class="header__item-mypage">
                <a class="mypage-button" href="/mypage">
                    マイページ
                </a>
            </div>
            <div class="header__item-sell">
                <a class="sell-button" href="">
                    出品
                </a>
            </div>
        </div>
    </header>
    <main class="main">
        <div class="main__inner">
            <h2 class="main__inner-title">
                商品の出品
            </h2>
            <div class="main__inner-form">
                <form class="sell__form" method="post"  action="{{ route('item.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="sell__form-item">
                        <label class="sell__form-item__label" for="item-img">
                            商品画像
                        </label>
                        <div class="sell__form-item__image">
                            <img class="preview__image"src="" id="preview-image">
                            <input type="file" name="item_image" id="item_image" accept="image/jpeg, image/png" style="display: none;">
                            <label for="item_image" class="preview__image-file">
                                画像を選択する
                            </label>
                        </div>
                    </div>
                    <div class="error-message">
                        @error('item_image')
                            {{ $message }}
                        @enderror
                    </div>
                    <h3 class="sell__form-title">
                        商品の詳細
                    </h3>
                    <div class="sell__form-item">
                        <label class="sell__form-item__label" for="item-category">
                            カテゴリー
                        </label>
                        <div class="checkbox-group">
                            @foreach($categories as $category)
                                <input type="checkbox" name="item_category[]" id="category_{{ $category->id }}" value="{{ $category->id }}" style="display: none;" {{ in_array($category->id, old('item_category', [])) ? 'checked' : '' }}>
                                <label for="category_{{ $category->id }}" class="category-button">{{ $category->name }}</label>
                            @endforeach
                        </div>
                    </div>
                    <div class="error-message">
                        @error('item_category')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="sell__form-item">
                        <label class="sell__form-item__label" for="item-condition">
                            商品の状態
                        </label>
                        <select class="sell__form-item__select" name="item_condition" id="item-condition">
                            <option value="" disabled selected>選択してください</option>
                            @foreach( $conditions as $condition )
                                <option value="{{ $condition->id }}"
                                    {{ old('item_condition') == $condition->id ? 'selected' : '' }}>
                                    {{$condition->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="error-message">
                        @error('item_condition')
                            {{ $message }}
                        @enderror
                    </div>
                    <h3 class="sell__form-title">
                        商品の説明
                    </h3>
                    <div class="sell__form-item">
                        <label class="sell__form-item__label" for="item-name" value="{{ old('item_name') }}">
                            商品名
                        </label>
                        <input class="sell__form-item__input" type="text" name="item_name" id="item-name" value="">
                    </div>
                    <div class="error-message">
                        @error('item_name')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="sell__form-item">
                        <label class="sell__form-item__label" for="item-brand" value="{{ old('item_brand') }}">
                            ブランド名
                        </label>
                        <input class="sell__form-item__input" type="text" name="item_brand" id="item-brand" value="">
                    </div>
                    <div class="error-message">
                        @error('item_brand')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="sell__form-item">
                        <label class="sell__form-item__label" for="item-description" value="{{ old('item_description') }}">
                            商品の説明
                        </label>
                        <textarea class="sell__form-item__textarea" name="item_description" id="item-description" rows="4"></textarea>
                    </div>
                    <div class="error-message">
                        @error('item_description')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="sell__form-item">
                        <label class="sell__form-item__label" for="item-price" value="{{ old('item_price') }}">
                            販売価格
                        </label>
                        <div class="price-input-wrapper">
                            <input class="sell__form-item__input" type="number" name="item_price" id="item-price" value="{{ old('item_price') }}">
                        </div>
                    </div>
                    <div class="error-message">
                        @error('item_price')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="sell__form-button">
                        <button class="sell__form-button__submit" type="submit">
                            出品する
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function() {
            $('#item_image').on('change', function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result).show();
                    $('#preview-text').hide();
                }
                reader.readAsDataURL(e.target.files[0]);
            });
        });
        </script>
    </main>
</body>
</html>