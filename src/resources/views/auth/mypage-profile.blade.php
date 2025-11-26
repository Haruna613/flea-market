<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flea-Market</title>
    <link rel="stylesheet" href="{{ asset('css/mypage-profile.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
</head>
<body>
    <header>

    </header>
    <main>
        <div class="main__inner">
            <form action="{{ route('profile.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h2 class="main__inner-title">
                    プロフィール設定
                </h2>
                <div class="main__contents">
                    <div class="main__contents-item" id="image-preview-area" style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; border: 1px solid #ccc; display: flex; justify-content: center; align-items: center;">
                        <img
                            src="{{ Auth::user()->profile_image_path ? Storage::url(Auth::user()->profile_image_path) : '' }}"
                            id="preview-image"
                            alt="プロフィール画像"
                            style="width: 100%; height: auto; border-radius: 50%;
                            {{-- 画像パスがない場合は非表示にする --}}
                            display: {{ Auth::user()->profile_image_path ? 'block' : 'none' }};"
                        >
                        <span id="preview-text" style="display: {{ Auth::user()->profile_image_path ? 'none' : 'block' }};">画像なし</span>
                    </div>
                    <div class="main__contents-item">
                        <input type="file" name="profile_image" id="profile_image" accept="image/jpeg, image/png" style="display: none;">
                        <label for="profile_image" class="file-input-label">画像を選択する</label>
                    </div>
                    <div class="main__contents-item">
                        <label for="username" class="item__name">
                            ユーザー名
                        </label>
                        <input class="item__input" type="text" name="username" id="username" value="{{ old('username', Auth::user()->username ?? Auth::user()->name) }}">
                    </div>
                    <div class="form__error">
                        @error('username')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="main__contents-item">
                        <label for="postal_code" class="item__name">
                            郵便番号
                        </label>
                        <input class="item__input" type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', Auth::user()->postal_code) }}">
                    </div>
                    <div class="form__error">
                        @error('postal_code')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="main__contents-item">
                        <label for="address" class="item__name">
                            住所
                        </label>
                        <input class="item__input" type="text" name="address" id="address" value="{{ old('address', Auth::user()->address) }}">
                    </div>
                    <div class="form__error">
                        @error('address')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="main__contents-item">
                        <label for="building_name" class="item__name">
                            建物名
                        </label>
                        <input class="item__input" type="text" name="building_name" id="building_name" value="{{ old('building_name', Auth::user()->building_name) }}">
                    </div>
                    <div class="form__error">
                        @error('building_name')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="form__button">
                        <button class="form__button-submit" type="submit">
                        更新する
                    </button>
                    </div>
                </div>
            </form>
        </div>
        <script src="https://code.jquery.com"></script>
        <script>
        $(document).ready(function() {
            $('#profile_image').on('change', function(e) {
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