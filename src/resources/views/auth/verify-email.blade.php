<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-Market</title>
    <link rel="stylesheet" href="{{ asset('css/email.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
</head>
<body>
    <div>メールを確認してください</div>
    <div class="mt-4">
    <p>メールが届かない、またはリンクの期限が切れてしまった場合：</p>
    
    {{-- Laravel Fortifyが提供する再送ルートを使用します --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">
            認証メールを再送する
        </button>
    </form>
</div>
</body>
</html>