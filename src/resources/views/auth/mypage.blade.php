<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-Market</title>
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}" />
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
                <a class="sell-button" href="/sell">
                    出品
                </a>
            </div>
        </div>
    </header>
    <main class="main">
        <div class="main__inner">
            <div class="user">
                <div class="user__image-preview" id="image-preview-area">
                    <img class="user__image-preview__img"
                    src="{{ Auth::user()->profile_image_path ? Storage::url(Auth::user()->profile_image_path) : '' }}"
                    id="preview-image"
                    alt="プロフィール画像"
                    style="display: {{ Auth::user()->profile_image_path ? 'block' : 'none' }};">
                </div>
                <div class="user__name">
                    <p>{{ Auth::user()->username }}</p>
                </div>
                <div class="user__profile-update">
                    <a class="update__link" href="/mypage/profile">
                        プロフィールを編集
                    </a>
                </div>
            </div>
            <div class="tabs">
                <a href="/mypage?page=sell" class="tab-button @if(request('page') == 'sell' || !request('page')) is-active @endif">出品した商品</a>
                <a href="/mypage?page=buy" class="tab-button @if(request('page') == 'buy') is-active @endif">購入した商品</a>
            </div>
            @if(request('page') == 'sell')
                <div id="listed" class="tab-content">
                    @if($listedItems->isEmpty())
                        <p class="tab-content__message">出品した商品はありません</p>
                    @else
                        <ul class="tab-content__item">
                            @foreach($listedItems as $item)
                                <li class="tab-content__item-list">
                                    @if($item->image_path)
                                        @if (Str::startsWith($item->image_path, 'https://'))
                                            <img class="tab-content__item--img" src="{{ $item->image_path }}" alt="{{ $item->name }}" width="100">
                                        @else
                                            <img class="tab-content__item--img" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" width="100">
                                        @endif
                                    @endif
                                    <p>{{ $item->name }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
            @if(request('page') == 'buy' || !request('page'))
                <div id="purchased" class="tab-content">
                    @if($purchasedItems->isEmpty())
                        <p class="tab-content__message">購入した商品はありません</p>
                    @else
                        <ul class="tab-content__item">
                            @foreach($purchasedItems as $item)
                                <li class="tab-content__item-list">
                                    @if($item->image_path)
                                        @if (Str::startsWith($item->image_path, 'https://'))
                                            <img class="tab-content__item--img" src="{{ $item->image_path }}" alt="{{ $item->name }}" width="100">
                                        @else
                                            <img class="tab-content__item--img" src="{{ Storage::url($item->image_path) }}" alt="{{ $item->name }}" width="100">
                                        @endif
                                    @endif
                                    <p>{{ $item->name }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
        </div>
    </main>
</body>
</html>