<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-Market</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <img class="header__inner-logo" src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="サイトロゴ">
        </div>
    </header>
    <main class="main">
        <div class="main__inner">
            <form class="form" action="/register" method="post">
                @csrf
                <h2 class="form__title">
                    会員登録
                </h2>
                <div class="form__item">
                    <span class="form__item-title">
                        ユーザー名
                    </span>
                    <input class="form__item-input" type="text" name="name" value="{{ old('name') }}">
                    <div class="form__error">
                        @error('name')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form__item">
                    <span class="form__item-title">
                        メールアドレス
                    </span>
                    <input class="form__item-input" type="text" name="email" value="{{ old('email') }}">
                    <div class="form__error">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form__item">
                    <span class="form__item-title">
                        パスワード
                    </span>
                    <input class="form__item-input" type="password" name="password">
                    <div class="form__error">
                        @error('password')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form__item">
                    <span class="form__item-title">
                        確認用パスワード
                    </span>
                    <input class="form__item-input" type="password" name="password_confirmation">
                    <div class="form__error">
                        @error('password_confirmation')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="form__button">
                    <button class="form__button-submit" type="submit">
                        登録する
                    </button>
                </div>
            </form>
            <div class="link">
                <a  class="link__login" href="/login">
                    ログインはこちら
                </a>
            </div>
        </div>
    </main>
</body>
</html>