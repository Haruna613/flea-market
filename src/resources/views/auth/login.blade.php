<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-Market</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
</head>
<body>
    <header>
        coachtech
    </header>
    <main>
        <div class="main__inner">
            <form class="form" action="/login" method="post">
                @csrf
                <h2 class="form__title">
                    ログイン
                </h2>
                <div class="form__item">
                    <span class="form__item-title">
                        メールアドレス
                    </span>
                    <input class="form__item-input" type="email" name="email" value="{{ old('email') }}">
                </div>
                <div class="form__item">
                    <span class="form__item-title">
                        パスワード
                    </span>
                    <input class="form__item-input" type="password" name="password">
                </div>
                <div class="form__button">
                    <button class="form__button-submit" type="submit">
                        ログインする
                    </button>
                </div>
            </form>
            <div class="link__register">
                <a href="/register">
                    会員登録はこちら
                </a>
            </div>
        </div>
    </main>
</body>
</html>