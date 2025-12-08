<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-Market</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__item">
            <div class="header__item-logo">
                <img class="header-logo" src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="サイトロゴ">
            </div>
            <div><!--検索機能--></div>
            @if (Auth::check())
                <div class="header__item-logout">
                    <form class="logout-form" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="logout-form__button">ログアウト</button>
                    </form>
                </div>
                <div class="header__item-mypage">
                    <a class="mypage-button" href="{{ route('profile') }}">
                        マイページ
                    </a>
                </div>
                <div class="header__item-sell">
                    <a class="sell-button" href="{{ route('item.sell.show') }}">
                        出品
                    </a>
                </div>
            @else
                <div class="header__item-login">
                    <a class="login-button" href="{{ route('login') }}">
                        ログイン
                    </a>
                </div>
                <div class="header__item-mypage">
                    <a class="mypage-button" href="{{ route('profile') }}">
                        マイページ
                    </a>
                </div>
                <div class="header__item-sell">
                    <a class="sell-button" href="{{ route('item.sell.show') }}">
                        出品
                    </a>
                </div>
            @endif
        </div>
    </header>
</body>
</html>