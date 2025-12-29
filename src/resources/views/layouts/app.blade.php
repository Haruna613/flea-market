<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-Market</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__item">
            <div class="header__item-logo">
                <a href="/">
                    <img class="header__item-logo--img" src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="サイトロゴ">
                </a>
            </div>
            <div class="header__item-search">
                <form action="{{ route('top') }}" method="GET" class="search-form">
                    <input class="header__item-search--input" type="text" name="keyword" placeholder="なにをお探しですか？" value="{{ $keyword ?? '' }}">
                    <input type="hidden" name="tab" value="{{ $currentTab ?? 'all' }}">
                </form>
            </div>
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
    <main>
        @yield('content')
    </main>
    @yield('scripts')
</body>

</html>