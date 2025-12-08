<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-Market</title>
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}" />
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
            <div class="main__content">
                <p class="main__content-item">登録していただいたメールアドレスに認証メールを送付しました。</p>
                <p class="main__content-item">メール認証を完了してください。</p>
            </div>
            <div class="main__content">
                <a class="mailhog-link" href="http://localhost:8026" rel="noopener noreferrer">
                    認証はこちらから
                </a>
                <form class="mail__form" method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button class="mail__form-submit" type="submit">
                        認証メールを再送する
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>